<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\SmartSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request, SmartSearchService $smartSearchService)
    {
        $q = trim((string) $request->query('q', ''));

        if ($q === '') {
            $categories = Category::query()
                ->orderBy('id', 'desc')
                ->paginate(10)
                ->withQueryString();
        } else {
            $searchPool = Category::query()
                ->orderByDesc('id')
                ->get();

            $rankedCategories = $smartSearchService->search(
                $q,
                $searchPool,
                function ($item): string {
                    return implode(' ', [
                        (string) ($item->name ?? ''),
                        (string) ($item->slug ?? ''),
                    ]);
                }
            );

            $categories = $smartSearchService->paginate($rankedCategories, 10, $request->query());
        }

        return view('pages.categories.index', compact('categories', 'q'));
    }
    
    public function create()
    {
        return view('pages.categories.create');
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "name" => "required|unique:categories,name",
        ], [
            "name.required" => "Nama kategori harus diisi",
            "name.unique" => "Nama kategori sudah ada!",
        ]);

        Category::create([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
        ]);

        return redirect ('/categories')->with('success', 'Berhasil menambahkan kategori');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validatedData = $request->validate([
            "name" => "required|unique:categories,name," . $category->id,
        ], [
            "name.required" => "Nama kategori harus diisi",
            "name.unique" => "Nama kategori sudah ada!",
        ]);

        $category->name = $validatedData['name'];
        $category->slug = Str::slug($validatedData['name']);
        $category->save();

        return redirect ('/categories')->with('success', 'Berhasil mengubah kategori');
    }

    public function delete($id)
    {
        Category::where('id', $id)->delete();

        return redirect('/categories')->with('success', 'Berhasil menghapus kategori');
    }
    

}
