<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\SubLocation;
use App\Services\SmartSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LocationController extends Controller
{
    public function index(Request $request, SmartSearchService $smartSearchService)
    {
        $q = trim((string) $request->query('q', ''));
        $qSub = trim((string) $request->query('q_sub', ''));

        if ($q === '') {
            $locations = Location::query()
                ->orderByDesc('id')
                ->paginate(10, ['*'], 'locations_page')
                ->withQueryString();
        } else {
            $locationPool = Location::query()
                ->orderByDesc('id')
                ->get();

            $rankedLocations = $smartSearchService->search(
                $q,
                $locationPool,
                function ($item): string {
                    return implode(' ', [
                        (string) ($item->name ?? ''),
                        (string) ($item->slug ?? ''),
                    ]);
                }
            );

            $locations = $smartSearchService->paginate(
                $rankedLocations,
                10,
                $request->query(),
                'locations_page'
            );
        }

        if ($qSub === '') {
            $subLocations = SubLocation::query()
                ->with('location')
                ->orderByDesc('id')
                ->paginate(10, ['*'], 'sub_locations_page')
                ->withQueryString();
        } else {
            $subLocationPool = SubLocation::query()
                ->with('location')
                ->orderByDesc('id')
                ->get();

            $rankedSubLocations = $smartSearchService->search(
                $qSub,
                $subLocationPool,
                function ($item): string {
                    return implode(' ', [
                        (string) ($item->name ?? ''),
                        (string) ($item->slug ?? ''),
                        (string) optional($item->location)->name,
                    ]);
                }
            );

            $subLocations = $smartSearchService->paginate(
                $rankedSubLocations,
                10,
                $request->query(),
                'sub_locations_page'
            );
        }

        return view('pages.locations.index', [
            'locations' => $locations,
            'subLocations' => $subLocations,
            'locationOptions' => Location::query()->orderBy('name')->get(['id', 'name']),
            'q' => $q,
            'qSub' => $qSub,
        ]);
    }

    public function create()
    {
        return view('pages.locations.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:locations,name',
        ], [
            'name.required' => 'Nama lokasi harus diisi',
            'name.unique' => 'Nama lokasi sudah ada!',
        ]);

        Location::create([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
        ]);

        return redirect('/locations')->with('success', 'Berhasil menambahkan lokasi');
    }

    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|unique:locations,name,' . $location->id,
        ], [
            'name.required' => 'Nama lokasi harus diisi',
            'name.unique' => 'Nama lokasi sudah ada!',
        ]);

        $location->name = $validatedData['name'];
        $location->slug = Str::slug($validatedData['name']);
        $location->save();

        return redirect('/locations')->with('success', 'Berhasil mengubah lokasi');
    }

    public function delete($id)
    {
        $location = Location::withCount(['inventories', 'subLocations'])->findOrFail($id);

        if ($location->inventories_count > 0 || $location->sub_locations_count > 0) {
            return redirect('/locations')->with('error', 'Lokasi tidak bisa dihapus karena sudah dipakai pada sub lokasi atau inventaris.');
        }

        $location->delete();

        return redirect('/locations')->with('success', 'Berhasil menghapus lokasi');
    }

    public function storeSubLocation(Request $request)
    {
        $validatedData = $request->validate([
            'sub_location_name' => [
                'required',
                'max:100',
                Rule::unique('sub_locations', 'name')->where(function ($query) use ($request) {
                    $query->where('location_id', $request->input('sub_location_location_id'));
                }),
            ],
            'sub_location_location_id' => 'required|exists:locations,id',
        ], [
            'sub_location_name.required' => 'Nama sub lokasi harus diisi!',
            'sub_location_name.unique' => 'Nama sub lokasi sudah ada pada lokasi tersebut!',
            'sub_location_location_id.required' => 'Lokasi induk harus dipilih!',
        ]);

        SubLocation::create([
            'name' => $validatedData['sub_location_name'],
            'slug' => Str::slug($validatedData['sub_location_name']),
            'location_id' => $validatedData['sub_location_location_id'],
        ]);

        return redirect('/locations')->with('success', 'Berhasil menambahkan sub lokasi');
    }

    public function updateSubLocation(Request $request, $id)
    {
        $subLocation = SubLocation::findOrFail($id);

        $validatedData = $request->validate([
            'name' => [
                'required',
                'max:100',
                Rule::unique('sub_locations', 'name')
                    ->ignore($subLocation->id)
                    ->where(function ($query) use ($request) {
                        $query->where('location_id', $request->input('location_id'));
                    }),
            ],
            'location_id' => 'required|exists:locations,id',
        ], [
            'name.required' => 'Nama sub lokasi harus diisi!',
            'name.unique' => 'Nama sub lokasi sudah ada pada lokasi tersebut!',
            'location_id.required' => 'Lokasi induk harus dipilih!',
        ]);

        $subLocation->name = $validatedData['name'];
        $subLocation->slug = Str::slug($validatedData['name']);
        $subLocation->location_id = $validatedData['location_id'];
        $subLocation->save();

        return redirect('/locations')->with('success', 'Berhasil mengubah sub lokasi');
    }

    public function deleteSubLocation($id)
    {
        $subLocation = SubLocation::withCount('inventories')->findOrFail($id);

        if ($subLocation->inventories_count > 0) {
            return redirect('/locations')->with('error', 'Sub lokasi tidak bisa dihapus karena sudah dipakai pada inventaris.');
        }

        $subLocation->delete();

        return redirect('/locations')->with('success', 'Berhasil menghapus sub lokasi');
    }
}
