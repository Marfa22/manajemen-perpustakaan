<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\SmartSearchService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class PublicDashboardController extends Controller
{
    public function index(Request $request, SmartSearchService $smartSearchService)
    {
        $q = trim((string) $request->query('q', ''));

        $foundItemsBaseQuery = Product::query()->where('item_type', 'found');

        $filteredFoundItemsQuery = Product::with('category')
            ->where('item_type', 'found')
            ->where(function ($query) {
                $query->where('pickup_status', 'belum_diambil')
                    ->orWhereNull('pickup_status');
            });
        $topSearchItem = null;

        if ($q === '') {
            $latestItems = Cache::remember('public_dashboard:slider_items:v1', now()->addMinutes(5), function () {
                return Product::with('category')
                    ->where('item_type', 'found')
                    ->where(function ($query) {
                        $query->where('pickup_status', 'belum_diambil')
                            ->orWhereNull('pickup_status');
                    })
                    ->orderByDesc('found_at')
                    ->latest()
                    ->get();
            });

            $foundItems = (clone $filteredFoundItemsQuery)
                ->orderByDesc('found_at')
                ->latest()
                ->paginate(8)
                ->withQueryString();
        } else {
            $searchPool = (clone $filteredFoundItemsQuery)
                ->orderByDesc('found_at')
                ->latest()
                ->limit(500)
                ->get();

            $rankedItems = $smartSearchService->search($q, $searchPool);
            $topSearchItem = $rankedItems->first();

            $latestItems = $rankedItems
                ->sortByDesc(function ($item) {
                    if (!empty($item->found_at)) {
                        $time = strtotime((string) $item->found_at);
                        if ($time !== false) {
                            return $time;
                        }
                    }

                    if (!empty($item->created_at)) {
                        $time = strtotime((string) $item->created_at);
                        if ($time !== false) {
                            return $time;
                        }
                    }

                    return 0;
                })
                ->values();

            $perPage = 8;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $currentItems = $rankedItems
                ->slice(($currentPage - 1) * $perPage, $perPage)
                ->values();

            $foundItems = new LengthAwarePaginator(
                $currentItems,
                $rankedItems->count(),
                $perPage,
                $currentPage,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'query' => $request->query(),
                ]
            );
        }

        $totalFoundCount = (clone $foundItemsBaseQuery)->count();

        $pendingFoundCount = (clone $foundItemsBaseQuery)
            ->where(function ($query) {
                $query->where('pickup_status', 'belum_diambil')
                    ->orWhereNull('pickup_status');
            })
            ->count();

        $pickedFoundCount = (clone $foundItemsBaseQuery)
            ->where('pickup_status', 'sudah_diambil')
            ->count();

        return view('pages.dashboard.public', [
            'q' => $q,
            'latestItems' => $latestItems,
            'foundItems' => $foundItems,
            'topSearchItem' => $topSearchItem,
            'totalFoundCount' => $totalFoundCount,
            'pendingFoundCount' => $pendingFoundCount,
            'pickedFoundCount' => $pickedFoundCount,
        ]);
    }

    public function show(int $id)
    {
        $item = Product::with('category')
            ->where('item_type', 'found')
            ->where(function ($query) {
                $query->where('pickup_status', 'belum_diambil')
                    ->orWhereNull('pickup_status');
            })
            ->findOrFail($id);

        $relatedItems = Product::with('category')
            ->where('item_type', 'found')
            ->where('id', '!=', $item->id)
            ->where(function ($query) {
                $query->where('pickup_status', 'belum_diambil')
                    ->orWhereNull('pickup_status');
            })
            ->when($item->category_id, function ($query) use ($item) {
                $query->where('category_id', $item->category_id);
            })
            ->orderByDesc('found_at')
            ->latest()
            ->take(4)
            ->get();

        if ($relatedItems->isEmpty()) {
            $relatedItems = Product::with('category')
                ->where('item_type', 'found')
                ->where('id', '!=', $item->id)
                ->where(function ($query) {
                    $query->where('pickup_status', 'belum_diambil')
                        ->orWhereNull('pickup_status');
                })
                ->orderByDesc('found_at')
                ->latest()
                ->take(4)
                ->get();
        }

        return view('pages.dashboard.public-detail', [
            'item' => $item,
            'relatedItems' => $relatedItems,
        ]);
    }
}
