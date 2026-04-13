<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Services\SmartSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request, SmartSearchService $smartSearchService) {
        $q = trim((string) $request->query('q', ''));

        $foundItemsBaseQuery = Product::query()->where('item_type', 'found');

        $foundTotalCount = (clone $foundItemsBaseQuery)->count();

        $foundNotPickedCount = (clone $foundItemsBaseQuery)
            ->where(function ($query) {
                $query->where('pickup_status', 'belum_diambil')
                    ->orWhereNull('pickup_status');
            })
            ->count();

        $foundPickedCount = (clone $foundItemsBaseQuery)
            ->where('pickup_status', 'sudah_diambil')
            ->count();
        $topMatchedProduct = null;

        if ($q === '') {
            $products = Product::with(['category', 'returnRecord'])
                ->where('item_type', 'found')
                ->latest()
                ->paginate(10)
                ->withQueryString();
        } else {
            $searchPool = Product::with(['category', 'returnRecord'])
                ->where('item_type', 'found')
                ->latest()
                ->get();

            $rankedItems = $smartSearchService->search(
                $q,
                $searchPool,
                function ($item): string {
                    return implode(' ', [
                        (string) ($item->name ?? ''),
                        (string) ($item->sku ?? ''),
                        (string) ($item->found_location ?? ''),
                        (string) ($item->pickup_status ?? ''),
                        (string) optional($item->category)->name,
                        (string) optional($item->returnRecord)->receiver_name,
                        (string) optional($item->returnRecord)->receiver_phone,
                        (string) optional($item->returnRecord)->receiver_address,
                    ]);
                }
            );
            $topMatchedProduct = $rankedItems->first();

            $products = $smartSearchService->paginate($rankedItems, 10, $request->query());
        }
        
        return view('pages.products.index', [
            "products" => $products,
            "q" => $q,
            "topMatchedProduct" => $topMatchedProduct,
            "foundTotalCount" => $foundTotalCount,
            "foundNotPickedCount" => $foundNotPickedCount,
            "foundPickedCount" => $foundPickedCount,
        ]);
    }
    public function create() {  
        $categories = Category::orderBy('name')->get();
        return view('pages.products.create', [
            "categories" => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated =$request->validate([
            "name" => "required|min:3",
            "description" => "nullable",
            "category_id" => "required|exists:categories,id",
            "sku" => "required",
            "found_location" => "required|string|max:255",
            "found_at" => "required|date",
            "photo" => "nullable|image|mimes:jpg,jpeg,png,webp|max:2048",
        ], [
            "name.required" => "Nama barang harus diisi!",
            "name.min" => "Minimal 3 karakter!",
            "category_id.required" => "Kategori harus diisi!",
            "sku.required" => "Kode barang harus diisi!",
            "found_location.required" => "Lokasi ditemukan harus diisi!",
            "found_at.required" => "Tanggal ditemukan harus diisi!",
            "photo.image" => "Foto harus berupa gambar.",
            "photo.max" => "Ukuran foto maksimal 2MB.",
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('products', 'public');
        }

        unset($validated['photo']);
        $validated['item_type'] = 'found';
        $validated['price'] = 0;
        $validated['stock'] = 0;
        $validated['pickup_status'] = 'belum_diambil';

        Product::create($validated);

        return redirect('/products')->with('success', 'Berhasil menambahkan barang');
    }
    public function edit($id) {  
        $categories = Category::orderBy('name')->get();
        $product = Product::where('item_type', 'found')->findOrFail($id);
        
        return view('pages.products.edit', [
            "categories" => $categories,
            "product" => $product,
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('id', $id)
            ->where('item_type', 'found')
            ->firstOrFail();

        $validated = $request->validate([
            "name" => "required|min:3",
            "description" => "nullable",
            "category_id" => "required|exists:categories,id",
            "sku" => "required",
            "found_location" => "required|string|max:255",
            "found_at" => "required|date",
            "photo" => "nullable|image|mimes:jpg,jpeg,png,webp|max:2048",
        ], [
            "name.required" => "Nama barang harus diisi!",
            "name.min" => "Minimal 3 karakter!",
            "category_id.required" => "Kategori harus dipilih!",
            "sku.required" => "Kode barang harus diisi!",
            "found_location.required" => "Lokasi ditemukan harus diisi!",
            "found_at.required" => "Tanggal ditemukan harus diisi!",
            "photo.image" => "Foto harus berupa gambar.",
            "photo.max" => "Ukuran foto maksimal 2MB.",
        ]);

        if ($request->hasFile('photo')) {
            if (!empty($product->photo_path) && Storage::disk('public')->exists($product->photo_path)) {
                Storage::disk('public')->delete($product->photo_path);
            }

            $validated['photo_path'] = $request->file('photo')->store('products', 'public');
        }

        unset($validated['photo']);

        $product->update($validated);

        return redirect('/products')->with('success', 'Berhasil mengubah barang');
    }

    public function processReturn(Request $request, $id)
    {
        $product = Product::with('returnRecord')
            ->where('id', $id)
            ->where('item_type', 'found')
            ->firstOrFail();

        $validated = $request->validate([
            'receiver_name' => 'required|string|min:3|max:100',
            'receiver_phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+()\-\s]{8,30}$/'],
            'receiver_address' => 'required|string|min:5|max:500',
            'return_notes' => 'nullable|string|max:1000',
        ], [
            'receiver_name.required' => 'Nama pengambil harus diisi.',
            'receiver_name.min' => 'Nama pengambil minimal 3 karakter.',
            'receiver_phone.required' => 'Nomor telepon harus diisi.',
            'receiver_phone.regex' => 'Format nomor telepon tidak valid.',
            'receiver_address.required' => 'Alamat pengambil harus diisi.',
            'receiver_address.min' => 'Alamat pengambil minimal 5 karakter.',
        ]);

        $existingReturn = $product->returnRecord;
        if ($existingReturn && !empty($existingReturn->receiver_ktp_path) && Storage::disk('public')->exists($existingReturn->receiver_ktp_path)) {
            Storage::disk('public')->delete($existingReturn->receiver_ktp_path);
        }

        ProductReturn::updateOrCreate(
            ['product_id' => $product->id],
            [
                'receiver_name' => $validated['receiver_name'],
                'receiver_phone' => $validated['receiver_phone'],
                'receiver_address' => $validated['receiver_address'],
                'receiver_ktp_path' => '',
                'notes' => $validated['return_notes'] ?? null,
                'returned_at' => now(),
                'processed_by' => Auth::id(),
            ]
        );

        $product->update([
            'pickup_status' => 'sudah_diambil',
        ]);

        return redirect('/products')->with('success', 'Pengembalian barang berhasil diproses.');
    }


    public function delete($id)
    {
        $product = Product::with('returnRecord')
            ->where('id', $id)
            ->where('item_type', 'found')
            ->firstOrFail();

        if (!empty($product->photo_path) && Storage::disk('public')->exists($product->photo_path)) {
            Storage::disk('public')->delete($product->photo_path);
        }

        if ($product->returnRecord && !empty($product->returnRecord->receiver_ktp_path) && Storage::disk('public')->exists($product->returnRecord->receiver_ktp_path)) {
            Storage::disk('public')->delete($product->returnRecord->receiver_ktp_path);
        }

        $product->delete();

        return redirect('/products')->with('success', 'Berhasil menghapus barang');
    }
}
