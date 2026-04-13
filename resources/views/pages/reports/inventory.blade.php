@extends('layouts.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Laporan Barang Kantor</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                <li class="breadcrumb-item active">Laporan Barang Kantor</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="table-shell">
                <div class="table-topbar">
                    <div class="table-title">
                        <h3>Data Laporan Inventaris</h3>
                        <span class="table-subtitle">Unduh laporan barang kantor dalam format Excel (.xlsx).</span>
                    </div>
                    <a href="{{ request()->getBaseUrl() }}/reports/inventory/export{{ $q !== '' ? '?q=' . urlencode($q) : '' }}" class="btn btn-sm btn-success">
                        <i class="fas fa-file-download mr-1"></i> Download Excel
                    </a>
                </div>

                <form class="table-filter" method="GET" action="{{ request()->getBaseUrl() }}/reports/inventory">
                    <input
                        type="text"
                        name="q"
                        value="{{ $q }}"
                        class="form-control"
                        style="max-width: 320px;"
                        placeholder="Cari nama / kode / serial / merk / kategori / lokasi / sub lokasi"
                    >
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                    @if ($q !== '')
                        <a href="{{ request()->getBaseUrl() }}/reports/inventory" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>BARANG</th>
                                <th>Kode Barang</th>
                                <th>Serial Number</th>
                                <th>Kategori</th>
                                <th>Merk</th>
                                <th>Penanggung Jawab</th>
                                <th>Kondisi</th>
                                <th>Lokasi</th>
                                <th>Update Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inventories as $inventory)
                                <tr>
                                    <td>{{ ($inventories->currentPage() - 1) * $inventories->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div class="cell-primary">{{ $inventory->nama }}</div>
                                        <p class="cell-secondary">{{ $inventory->deskripsi ?: '-' }}</p>
                                    </td>
                                    <td>{{ $inventory->kode_barang }}</td>
                                    <td>{{ $inventory->serial_number ?: '-' }}</td>
                                    <td>{{ $inventory->category->name ?? '-' }}</td>
                                    <td>{{ $inventory->brand->name ?? '-' }}</td>
                                    <td>{{ $inventory->penanggung_jawab ?: '-' }}</td>
                                    <td>{{ $inventory->kondisi }}</td>
                                    @php
                                        $lokasiLabel = $inventory->location->name ?? '-';
                                        $subLokasiLabel = $inventory->subLocation->name ?? '';
                                    @endphp
                                    <td>{{ $subLokasiLabel !== '' ? $lokasiLabel . ' -> ' . $subLokasiLabel : $lokasiLabel }}</td>
                                    <td>{{ optional($inventory->updated_at)->format('d-m-Y H:i') ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">Data tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    {{ $inventories->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
