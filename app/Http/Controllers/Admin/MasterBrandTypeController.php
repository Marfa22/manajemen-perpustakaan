<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Services\SmartSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MasterBrandTypeController extends Controller
{
    public function index(Request $request, SmartSearchService $smartSearchService)
    {
        $qBrand = trim((string) $request->query('q_brand', ''));

        if ($qBrand === '') {
            $brands = Brand::query()
                ->with('category')
                ->orderByDesc('id')
                ->paginate(10, ['*'], 'brands_page')
                ->withQueryString();
        } else {
            $searchPool = Brand::query()
                ->with('category')
                ->orderByDesc('id')
                ->get();

            $rankedBrands = $smartSearchService->search(
                $qBrand,
                $searchPool,
                function ($item): string {
                    return implode(' ', [
                        (string) ($item->name ?? ''),
                        (string) ($item->slug ?? ''),
                        (string) optional($item->category)->name,
                    ]);
                }
            );

            $brands = $smartSearchService->paginate(
                $rankedBrands,
                10,
                $request->query(),
                'brands_page'
            );
        }

        return view('pages.master-brand-type.index', [
            'brands' => $brands,
            'qBrand' => $qBrand,
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function create()
    {
        return view('pages.master-brand-type.create', [
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function storeBrand(Request $request)
    {
        $validated = $request->validate([
            'brand_name' => [
                'required',
                'max:100',
                Rule::unique('brands', 'name')->where(function ($query) use ($request) {
                    $query->where('category_id', (int) $request->input('category_id'));
                }),
            ],
            'category_id' => 'required|exists:categories,id',
        ], [
            'brand_name.required' => 'Nama merk harus diisi!',
            'brand_name.unique' => 'Nama merk sudah ada di kategori ini!',
            'category_id.required' => 'Kategori harus dipilih!',
            'category_id.exists' => 'Kategori tidak ditemukan!',
        ]);

        Brand::create([
            'name' => $validated['brand_name'],
            'slug' => Str::slug($validated['brand_name']),
            'category_id' => (int) $validated['category_id'],
        ]);

        return redirect('/merek')->with('success', 'Berhasil menambahkan merk');
    }

    public function updateBrand(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'max:100',
                Rule::unique('brands', 'name')
                    ->where(function ($query) use ($request) {
                        $query->where('category_id', (int) $request->input('category_id'));
                    })
                    ->ignore($brand->id),
            ],
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.required' => 'Nama merk harus diisi!',
            'name.unique' => 'Nama merk sudah ada di kategori ini!',
            'category_id.required' => 'Kategori harus dipilih!',
            'category_id.exists' => 'Kategori tidak ditemukan!',
        ]);

        $brand->name = $validated['name'];
        $brand->slug = Str::slug($validated['name']);
        $brand->category_id = (int) $validated['category_id'];
        $brand->save();

        return redirect('/merek')->with('success', 'Berhasil mengubah merk');
    }

    public function deleteBrand($id)
    {
        $brand = Brand::withCount('inventories')->findOrFail($id);

        if ($brand->inventories_count > 0) {
            return redirect('/merek')->with('error', 'Merk tidak bisa dihapus karena sudah dipakai pada inventaris.');
        }

        $brand->delete();

        return redirect('/merek')->with('success', 'Berhasil menghapus merk');
    }
}
