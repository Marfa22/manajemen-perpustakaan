<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Location;
use App\Services\SmartSearchService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    public function index(Request $request, SmartSearchService $smartSearchService)
    {
        $q = trim((string) $request->query('q', ''));
        $selectedCategoryIds = $this->sanitizeIntFilters($request->query('category_ids', []));
        $selectedBrandIds = $this->sanitizeIntFilters($request->query('brand_ids', []));
        $selectedLocationIds = $this->sanitizeIntFilters($request->query('location_ids', []));
        $selectedConditions = $this->sanitizeStringFilters($request->query('conditions', []));
        $topMatchedInventory = null;

        $inventoryQuery = Inventory::query()->with(['category', 'location', 'subLocation', 'brand']);
        $this->applyStructuredFilters(
            $inventoryQuery,
            $selectedCategoryIds,
            $selectedBrandIds,
            $selectedLocationIds,
            $selectedConditions
        );

        if ($q === '') {
            $conditionStats = (clone $inventoryQuery)
                ->selectRaw("
                    SUM(CASE WHEN kondisi = 'Baik' THEN 1 ELSE 0 END) as baik_count,
                    SUM(CASE WHEN kondisi = 'Rusak Ringan' THEN 1 ELSE 0 END) as rusak_ringan_count,
                    SUM(CASE WHEN kondisi = 'Rusak Berat' THEN 1 ELSE 0 END) as rusak_berat_count
                ")
                ->first();

            $inventories = (clone $inventoryQuery)
                ->latest()
                ->paginate(10)
                ->withQueryString();

            $baikCount = (int) ($conditionStats->baik_count ?? 0);
            $rusakRinganCount = (int) ($conditionStats->rusak_ringan_count ?? 0);
            $rusakBeratCount = (int) ($conditionStats->rusak_berat_count ?? 0);
        } else {
            $searchPool = (clone $inventoryQuery)
                ->latest()
                ->get();

            $rankedInventories = $smartSearchService->search(
                $q,
                $searchPool,
                function ($item): string {
                    return $this->buildInventorySearchText($item);
                }
            );
            $topMatchedInventory = $rankedInventories->first();

            $inventories = $smartSearchService->paginate($rankedInventories, 10, $request->query());

            $conditionCounts = $this->summarizeConditionStats($rankedInventories);
            $baikCount = $conditionCounts['baik'];
            $rusakRinganCount = $conditionCounts['rusak_ringan'];
            $rusakBeratCount = $conditionCounts['rusak_berat'];
        }

        $categoryOptions = Category::query()
            ->whereIn('id', Inventory::query()->select('category_id')->whereNotNull('category_id')->distinct())
            ->orderBy('name')
            ->get(['id', 'name']);

        $brandOptions = Brand::query()
            ->with('category')
            ->whereIn('id', Inventory::query()->select('brand_id')->whereNotNull('brand_id')->distinct())
            ->orderBy('name')
            ->orderBy('category_id')
            ->get(['id', 'name', 'category_id']);

        $locationOptions = Location::query()
            ->whereIn('id', Inventory::query()->select('location_id')->whereNotNull('location_id')->distinct())
            ->orderBy('name')
            ->get(['id', 'name']);

        $conditionOptions = Inventory::query()
            ->whereNotNull('kondisi')
            ->select('kondisi')
            ->distinct()
            ->orderBy('kondisi')
            ->pluck('kondisi')
            ->values();

        $activeFilterCount = count($selectedCategoryIds)
            + count($selectedBrandIds)
            + count($selectedLocationIds)
            + count($selectedConditions);

        return view('pages.inventory.index', [
            'inventories' => $inventories,
            'q' => $q,
            'topMatchedInventory' => $topMatchedInventory,
            'baikCount' => $baikCount,
            'rusakRinganCount' => $rusakRinganCount,
            'rusakBeratCount' => $rusakBeratCount,
            'categoryOptions' => $categoryOptions,
            'brandOptions' => $brandOptions,
            'locationOptions' => $locationOptions,
            'conditionOptions' => $conditionOptions,
            'selectedCategoryIds' => $selectedCategoryIds,
            'selectedBrandIds' => $selectedBrandIds,
            'selectedLocationIds' => $selectedLocationIds,
            'selectedConditions' => $selectedConditions,
            'hasActiveFilters' => $activeFilterCount > 0,
            'activeFilterCount' => $activeFilterCount,
        ]);
    }

    public function create()
    {
        return view('pages.inventory.create', [
            'categories' => Category::query()
                ->with(['brands' => function ($query) {
                    $query->orderBy('name');
                }])
                ->orderBy('name')
                ->get(),
            'locations' => Location::query()
                ->with(['subLocations' => function ($query) {
                    $query->orderBy('name');
                }])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|min:3',
            'deskripsi' => 'nullable',
            'kode_barang' => 'required',
            'serial_number' => 'nullable|string|max:100',
            'penanggung_jawab' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => [
                'required',
                Rule::exists('brands', 'id')->where(function ($query) use ($request) {
                    $query->where('category_id', $request->input('category_id'));
                }),
            ],
            'location_id' => 'required|exists:locations,id',
            'sub_location_id' => [
                'required',
                Rule::exists('sub_locations', 'id')->where(function ($query) use ($request) {
                    $query->where('location_id', $request->input('location_id'));
                }),
            ],
            'kondisi' => 'required',
            'supporting_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ], [
            'nama.required' => 'Nama barang harus diisi!',
            'nama.min' => 'Minimal 3 karakter!',
            'kode_barang.required' => 'Kode barang harus diisi!',
            'category_id.required' => 'Kategori harus diisi!',
            'brand_id.required' => 'Merk harus dipilih!',
            'brand_id.exists' => 'Merk tidak sesuai dengan kategori yang dipilih!',
            'location_id.required' => 'Lokasi harus dipilih!',
            'sub_location_id.required' => 'Sub lokasi harus dipilih!',
            'sub_location_id.exists' => 'Sub lokasi tidak sesuai dengan lokasi yang dipilih!',
            'kondisi.required' => 'Kondisi harus diisi!',
            'supporting_file.file' => 'Dokumen pendukung tidak valid.',
            'supporting_file.mimes' => 'Format dokumen harus pdf, doc, docx, jpg, jpeg, atau png.',
            'supporting_file.max' => 'Ukuran dokumen maksimal 2MB.',
        ]);

        if ($request->hasFile('supporting_file')) {
            $validated['photo_path'] = $request->file('supporting_file')->store('inventories/supporting-files', 'public');
        }

        unset($validated['supporting_file']);

        Inventory::create($validated);

        return redirect('/inventory')->with('success', 'Berhasil menambahkan barang kantor');
    }

    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);

        return view('pages.inventory.edit', [
            'inventory' => $inventory,
            'categories' => Category::query()
                ->with(['brands' => function ($query) {
                    $query->orderBy('name');
                }])
                ->orderBy('name')
                ->get(),
            'locations' => Location::query()
                ->with(['subLocations' => function ($query) {
                    $query->orderBy('name');
                }])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|min:3',
            'deskripsi' => 'nullable',
            'kode_barang' => 'required',
            'serial_number' => 'nullable|string|max:100',
            'penanggung_jawab' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => [
                'required',
                Rule::exists('brands', 'id')->where(function ($query) use ($request) {
                    $query->where('category_id', $request->input('category_id'));
                }),
            ],
            'location_id' => 'required|exists:locations,id',
            'sub_location_id' => [
                'required',
                Rule::exists('sub_locations', 'id')->where(function ($query) use ($request) {
                    $query->where('location_id', $request->input('location_id'));
                }),
            ],
            'kondisi' => 'required',
            'supporting_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ], [
            'nama.required' => 'Nama barang harus diisi!',
            'nama.min' => 'Minimal 3 karakter!',
            'kode_barang.required' => 'Kode barang harus diisi!',
            'category_id.required' => 'Kategori harus diisi!',
            'brand_id.required' => 'Merk harus dipilih!',
            'brand_id.exists' => 'Merk tidak sesuai dengan kategori yang dipilih!',
            'location_id.required' => 'Lokasi harus dipilih!',
            'sub_location_id.required' => 'Sub lokasi harus dipilih!',
            'sub_location_id.exists' => 'Sub lokasi tidak sesuai dengan lokasi yang dipilih!',
            'kondisi.required' => 'Kondisi harus diisi!',
            'supporting_file.file' => 'Dokumen pendukung tidak valid.',
            'supporting_file.mimes' => 'Format dokumen harus pdf, doc, docx, jpg, jpeg, atau png.',
            'supporting_file.max' => 'Ukuran dokumen maksimal 2MB.',
        ]);

        if ($request->hasFile('supporting_file')) {
            if (!empty($inventory->photo_path) && Storage::disk('public')->exists($inventory->photo_path)) {
                Storage::disk('public')->delete($inventory->photo_path);
            }

            $validated['photo_path'] = $request->file('supporting_file')->store('inventories/supporting-files', 'public');
        }

        unset($validated['supporting_file']);

        $inventory->update($validated);

        return redirect('/inventory')->with('success', 'Berhasil mengubah barang kantor');
    }

    public function delete($id)
    {
        $inventory = Inventory::findOrFail($id);

        if (!empty($inventory->photo_path) && Storage::disk('public')->exists($inventory->photo_path)) {
            Storage::disk('public')->delete($inventory->photo_path);
        }

        $inventory->delete();

        return redirect('/inventory')->with('success', 'Berhasil menghapus barang kantor');
    }

    private function applyStructuredFilters(
        Builder $query,
        array $selectedCategoryIds,
        array $selectedBrandIds,
        array $selectedLocationIds,
        array $selectedConditions
    ): void {
        $query
            ->when(!empty($selectedCategoryIds), function ($builder) use ($selectedCategoryIds) {
                $builder->whereIn('category_id', $selectedCategoryIds);
            })
            ->when(!empty($selectedBrandIds), function ($builder) use ($selectedBrandIds) {
                $builder->whereIn('brand_id', $selectedBrandIds);
            })
            ->when(!empty($selectedLocationIds), function ($builder) use ($selectedLocationIds) {
                $builder->whereIn('location_id', $selectedLocationIds);
            })
            ->when(!empty($selectedConditions), function ($builder) use ($selectedConditions) {
                $builder->whereIn('kondisi', $selectedConditions);
            });
    }

    private function buildInventorySearchText(mixed $item): string
    {
        return implode(' ', [
            (string) ($item->nama ?? ''),
            (string) ($item->kode_barang ?? ''),
            (string) ($item->serial_number ?? ''),
            (string) ($item->penanggung_jawab ?? ''),
            (string) ($item->kondisi ?? ''),
            (string) ($item->deskripsi ?? ''),
            (string) optional($item->category)->name,
            (string) optional($item->brand)->name,
            (string) optional($item->location)->name,
            (string) optional($item->subLocation)->name,
        ]);
    }

    /**
     * @param \Illuminate\Support\Collection<int, mixed> $items
     * @return array{baik:int, rusak_ringan:int, rusak_berat:int}
     */
    private function summarizeConditionStats($items): array
    {
        $counts = [
            'baik' => 0,
            'rusak_ringan' => 0,
            'rusak_berat' => 0,
        ];

        foreach ($items as $item) {
            $condition = strtolower(trim((string) ($item->kondisi ?? '')));

            if ($condition === 'baik') {
                $counts['baik']++;
                continue;
            }

            if ($condition === 'rusak ringan') {
                $counts['rusak_ringan']++;
                continue;
            }

            if ($condition === 'rusak berat') {
                $counts['rusak_berat']++;
            }
        }

        return $counts;
    }

    private function sanitizeIntFilters($values): array
    {
        return collect((array) $values)
            ->map(function ($value) {
                return (int) $value;
            })
            ->filter(function ($value) {
                return $value > 0;
            })
            ->unique()
            ->values()
            ->all();
    }

    private function sanitizeStringFilters($values): array
    {
        return collect((array) $values)
            ->map(function ($value) {
                return trim((string) $value);
            })
            ->filter(function ($value) {
                return $value !== '';
            })
            ->unique()
            ->values()
            ->all();
    }
}
