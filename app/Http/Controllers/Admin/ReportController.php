<?php

namespace App\Http\Controllers\Admin;

use App\Exports\InventoryReportExport;
use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Services\SmartSearchService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function inventory(Request $request, SmartSearchService $smartSearchService)
    {
        $q = trim((string) $request->query('q', ''));

        if ($q === '') {
            $inventories = $this->buildInventoryReportBaseQuery()
                ->latest()
                ->paginate(15)
                ->withQueryString();
        } else {
            $searchPool = $this->buildInventoryReportBaseQuery()
                ->latest()
                ->get();

            $rankedInventories = $smartSearchService->search(
                $q,
                $searchPool,
                function ($item): string {
                    return implode(' ', [
                        (string) ($item->nama ?? ''),
                        (string) ($item->kode_barang ?? ''),
                        (string) ($item->serial_number ?? ''),
                        (string) ($item->penanggung_jawab ?? ''),
                        (string) ($item->kondisi ?? ''),
                        (string) ($item->deskripsi ?? ''),
                        (string) optional($item->brand)->name,
                        (string) optional($item->location)->name,
                        (string) optional($item->subLocation)->name,
                        (string) optional($item->category)->name,
                    ]);
                }
            );

            $inventories = $smartSearchService->paginate($rankedInventories, 15, $request->query());
        }

        return view('pages.reports.inventory', [
            'inventories' => $inventories,
            'q' => $q,
        ]);
    }

    public function exportInventory(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $filename = 'laporan-barang-kantor-' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new InventoryReportExport($q), $filename);
    }

    private function buildInventoryReportBaseQuery()
    {
        return Inventory::query()
            ->with(['category', 'location', 'subLocation', 'brand']);
    }
}
