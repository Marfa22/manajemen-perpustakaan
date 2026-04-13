<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\User;
use App\Services\SmartSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error-unauthorized', 'Silakan login terlebih dahulu');
        }

        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error-unauthorized', 'Silakan login terlebih dahulu');
        }

        $canManageMasterData = $user->isSuperAdmin();
        $canDocuments = $user->hasAccess(User::ACCESS_DOCUMENTS);
        $canInventory = $user->hasAccess(User::ACCESS_INVENTORY);
        $canFoundItems = $user->hasAccess(User::ACCESS_FOUND_ITEMS);

        $categoryCount = $canManageMasterData ? Category::count() : 0;
        $documentCount = $canDocuments ? Document::count() : 0;
        $inventoryCount = $canInventory ? Inventory::count() : 0;

        $productCount = 0;
        $foundNotPickedCount = 0;
        $foundPickedCount = 0;
        if ($canFoundItems) {
            $foundItemsQuery = Product::query()->where('item_type', 'found');
            $productCount = (clone $foundItemsQuery)->count();
            $foundNotPickedCount = (clone $foundItemsQuery)
                ->where(function ($query) {
                    $query->where('pickup_status', 'belum_diambil')
                        ->orWhereNull('pickup_status');
                })
                ->count();
            $foundPickedCount = (clone $foundItemsQuery)
                ->where('pickup_status', 'sudah_diambil')
                ->count();
        }

        $foundStatusTotal = $foundNotPickedCount + $foundPickedCount;
        $foundNotPickedRatio = $foundStatusTotal > 0
            ? round(($foundNotPickedCount / $foundStatusTotal) * 100, 1)
            : 0;
        $foundPickedRatio = $foundStatusTotal > 0
            ? round(($foundPickedCount / $foundStatusTotal) * 100, 1)
            : 0;

        $inventoryConditionStats = null;
        if ($canInventory) {
            $inventoryConditionStats = Inventory::query()
                ->selectRaw("
                    SUM(CASE WHEN kondisi = 'Baik' THEN 1 ELSE 0 END) as baik_count,
                    SUM(CASE WHEN kondisi = 'Rusak Ringan' THEN 1 ELSE 0 END) as rusak_ringan_count,
                    SUM(CASE WHEN kondisi = 'Rusak Berat' THEN 1 ELSE 0 END) as rusak_berat_count
                ")
                ->first();
        }

        $inventoryBaikCount = (int) ($inventoryConditionStats?->baik_count ?? 0);
        $inventoryRusakRinganCount = (int) ($inventoryConditionStats?->rusak_ringan_count ?? 0);
        $inventoryRusakBeratCount = (int) ($inventoryConditionStats?->rusak_berat_count ?? 0);

        return view('pages.dashboard.admin', compact(
            'productCount',
            'categoryCount',
            'documentCount',
            'inventoryCount',
            'foundNotPickedCount',
            'foundPickedCount',
            'foundNotPickedRatio',
            'foundPickedRatio',
            'inventoryBaikCount',
            'inventoryRusakRinganCount',
            'inventoryRusakBeratCount',
            'canManageMasterData',
            'canDocuments',
            'canInventory',
            'canFoundItems'
        ));
    }

    public function search(Request $request, SmartSearchService $smartSearchService)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error-unauthorized', 'Silakan login terlebih dahulu');
        }

        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error-unauthorized', 'Silakan login terlebih dahulu');
        }

        $canInventory = $user->hasAccess(User::ACCESS_INVENTORY);
        $canFoundItems = $user->hasAccess(User::ACCESS_FOUND_ITEMS);

        if (!$canInventory && !$canFoundItems) {
            return redirect()->route('dashboard.admin')->with('error', 'Anda tidak memiliki akses untuk fitur pencarian ini.');
        }

        $q = trim((string) $request->query('q', ''));

        $foundItems = collect();
        $inventories = collect();
        $foundItemsTotal = 0;
        $inventoriesTotal = 0;
        $topFoundItem = null;
        $topInventoryItem = null;

        if ($q !== '') {
            if ($canFoundItems) {
                $foundItemsPool = Product::query()
                    ->with(['category', 'returnRecord'])
                    ->where('item_type', 'found')
                    ->latest()
                    ->get();

                $rankedFoundItems = $smartSearchService->search(
                    $q,
                    $foundItemsPool,
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

                $foundItemsTotal = $rankedFoundItems->count();
                $topFoundItem = $rankedFoundItems->first();
                $foundItems = $rankedFoundItems->take(10)->values();
            }

            if ($canInventory) {
                $inventoryPool = Inventory::query()
                    ->with(['category', 'brand', 'location', 'subLocation'])
                    ->latest()
                    ->get();

                $rankedInventories = $smartSearchService->search(
                    $q,
                    $inventoryPool,
                    function ($item): string {
                        return implode(' ', [
                            (string) ($item->nama ?? ''),
                            (string) ($item->kode_barang ?? ''),
                            (string) ($item->serial_number ?? ''),
                            (string) ($item->penanggung_jawab ?? ''),
                            (string) ($item->kondisi ?? ''),
                            (string) optional($item->category)->name,
                            (string) optional($item->brand)->name,
                            (string) optional($item->location)->name,
                            (string) optional($item->subLocation)->name,
                        ]);
                    }
                );

                $inventoriesTotal = $rankedInventories->count();
                $topInventoryItem = $rankedInventories->first();
                $inventories = $rankedInventories->take(10)->values();
            }
        }

        return view('pages.dashboard.search', [
            'q' => $q,
            'foundItems' => $foundItems,
            'inventories' => $inventories,
            'topFoundItem' => $topFoundItem,
            'topInventoryItem' => $topInventoryItem,
            'foundItemsTotal' => $foundItemsTotal,
            'inventoriesTotal' => $inventoriesTotal,
            'canInventory' => $canInventory,
            'canFoundItems' => $canFoundItems,
        ]);
    }
}
