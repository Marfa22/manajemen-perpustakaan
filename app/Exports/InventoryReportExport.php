<?php

namespace App\Exports;

use App\Models\Inventory;
use App\Services\SmartSearchService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventoryReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    private int $rowNumber = 0;

    public function __construct(private string $q = '')
    {
    }

    public function collection()
    {
        $q = trim($this->q);
        $baseQuery = Inventory::query()
            ->with(['category', 'location', 'subLocation', 'brand'])
            ->latest();

        if ($q === '') {
            return $baseQuery->get();
        }

        $searchPool = $baseQuery->get();
        $smartSearchService = new SmartSearchService();

        return $smartSearchService->search(
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
        )->values();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Kode Barang',
            'Serial Number',
            'Kategori',
            'Merk',
            'Penanggung Jawab',
            'Kondisi',
            'Lokasi',
            'Deskripsi',
            'Update Terakhir',
        ];
    }

    public function map($row): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $row->nama,
            $row->kode_barang,
            $row->serial_number ?: '-',
            $row->category->name ?? '-',
            $row->brand->name ?? '-',
            $row->penanggung_jawab ?: '-',
            $row->kondisi,
            $this->formatLocationLabel($row),
            $row->deskripsi ?: '-',
            optional($row->updated_at)->format('d-m-Y H:i') ?: '-',
        ];
    }

    private function formatLocationLabel($row): string
    {
        $locationName = $row->location->name ?? '-';
        $subLocationName = $row->subLocation->name ?? '';

        if ($subLocationName === '') {
            return $locationName;
        }

        return $locationName . ' -> ' . $subLocationName;
    }
}
