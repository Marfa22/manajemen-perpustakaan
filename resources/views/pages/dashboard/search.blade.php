@extends('layouts.main')

@section('header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Hasil Pencarian Admin</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ request()->getBaseUrl() }}/dashboard-admin">Dashboard</a></li>
        <li class="breadcrumb-item active">Pencarian</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  @php
    $totalResults = $foundItemsTotal + $inventoriesTotal;
  @endphp

  <div class="card mb-3 search-summary-card">
    <div class="card-body">
      @if ($q === '')
        <h3 class="summary-title mb-1">Masukkan kata kunci pencarian</h3>
        <p class="summary-sub mb-0">Gunakan search di navbar untuk mencari data sesuai akses menu Anda.</p>
      @else
        <h3 class="summary-title mb-1">Pencarian: "{{ $q }}"</h3>
        <p class="summary-sub mb-0">Total ditemukan: <strong>{{ $totalResults }}</strong> data (Barang Temuan: {{ $foundItemsTotal }}, Inventaris: {{ $inventoriesTotal }}).</p>
      @endif
    </div>
  </div>

  @if ($q !== '' && (($canFoundItems && $topFoundItem) || ($canInventory && $topInventoryItem)))
    <div class="row mb-3">
      @if ($canFoundItems && $topFoundItem)
        <div class="col-lg-6 mb-2 mb-lg-0">
          <div class="quick-jump-card">
            <div>
              <small class="quick-jump-label">Paling Relevan - Barang Temuan</small>
              <h4 class="quick-jump-title">{{ $topFoundItem->name }}</h4>
              <p class="quick-jump-meta mb-0">{{ $topFoundItem->category?->name ?: '-' }} - {{ $topFoundItem->found_location ?: '-' }}</p>
            </div>
            <a href="{{ url('/products/edit/' . $topFoundItem->id) }}" class="btn btn-sm btn-primary">Buka Barang Ini</a>
          </div>
        </div>
      @endif

      @if ($canInventory && $topInventoryItem)
        <div class="col-lg-6">
          <div class="quick-jump-card">
            <div>
              <small class="quick-jump-label">Paling Relevan - Inventaris</small>
              <h4 class="quick-jump-title">{{ $topInventoryItem->nama }}</h4>
              <p class="quick-jump-meta mb-0">{{ $topInventoryItem->kode_barang ?: '-' }} - {{ $topInventoryItem->location?->name ?: '-' }}</p>
            </div>
            <a href="{{ url('/inventory/edit/' . $topInventoryItem->id) }}" class="btn btn-sm btn-primary">Buka Barang Ini</a>
          </div>
        </div>
      @endif
    </div>
  @endif

  <div class="row" id="hasil-pencarian">
    @if ($canFoundItems)
      <div class="col-lg-6 mb-3">
        <div class="card h-100">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title font-weight-bold mb-0">Barang Temuan</h3>
            @if ($q !== '')
              <a href="{{ url('/products?q=' . urlencode($q)) }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            @endif
          </div>
          <div class="card-body p-0">
            @if ($q === '')
              <p class="empty-text m-3">Belum ada kata kunci pencarian.</p>
            @elseif ($foundItems->isEmpty())
              <p class="empty-text m-3">Tidak ada hasil barang temuan untuk kata kunci ini.</p>
            @else
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead>
                    <tr>
                      <th>Nama</th>
                      <th>Kategori</th>
                      <th>Lokasi</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($foundItems as $item)
                      <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category?->name ?: '-' }}</td>
                        <td>{{ $item->found_location ?: '-' }}</td>
                        <td>
                          <span class="badge {{ $item->pickup_status === 'sudah_diambil' ? 'badge-secondary' : 'badge-success' }}">
                            {{ $item->pickup_status === 'sudah_diambil' ? 'Sudah Diambil' : 'Belum Diambil' }}
                          </span>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>
        </div>
      </div>
    @endif

    @if ($canInventory)
      <div class="col-lg-6 mb-3">
        <div class="card h-100">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title font-weight-bold mb-0">Inventaris</h3>
            @if ($q !== '')
              <a href="{{ url('/inventory?q=' . urlencode($q)) }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            @endif
          </div>
          <div class="card-body p-0">
            @if ($q === '')
              <p class="empty-text m-3">Belum ada kata kunci pencarian.</p>
            @elseif ($inventories->isEmpty())
              <p class="empty-text m-3">Tidak ada hasil inventaris untuk kata kunci ini.</p>
            @else
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead>
                    <tr>
                      <th>Nama</th>
                      <th>Kode</th>
                      <th>Lokasi</th>
                      <th>Kondisi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($inventories as $inventory)
                      <tr>
                        <td>{{ $inventory->nama }}</td>
                        <td>{{ $inventory->kode_barang }}</td>
                        <td>{{ $inventory->location?->name ?: '-' }}</td>
                        <td>{{ $inventory->kondisi ?: '-' }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>
        </div>
      </div>
    @endif

    @if (!$canFoundItems && !$canInventory)
      <div class="col-12">
        <div class="card h-100">
          <div class="card-body">
            <p class="empty-text mb-0">Akun Anda tidak memiliki akses menu pencarian.</p>
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection

@push('styles')
  <style>
    .search-summary-card {
      border: 1px solid #dbe4f0;
      border-radius: 14px;
      box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
    }

    .summary-title {
      font-size: 1.06rem;
      font-weight: 800;
      color: #0f172a;
      margin: 0;
    }

    .summary-sub {
      font-size: 0.9rem;
      color: #64748b;
    }

    .empty-text {
      color: #64748b;
      font-size: 0.92rem;
    }

    .table thead th {
      border-top: 0;
      font-size: 0.82rem;
      color: #475569;
      text-transform: uppercase;
      letter-spacing: 0.03em;
      white-space: nowrap;
    }

    .table tbody td {
      font-size: 0.9rem;
      color: #1e293b;
      vertical-align: middle;
    }

    .quick-jump-card {
      border: 1px solid #bfdbfe;
      border-radius: 12px;
      background: linear-gradient(120deg, #eff6ff 0%, #f8fbff 100%);
      padding: 0.9rem 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.8rem;
      flex-wrap: wrap;
    }

    .quick-jump-label {
      color: #1d4ed8;
      font-weight: 700;
      font-size: 0.74rem;
      text-transform: uppercase;
      letter-spacing: 0.03em;
    }

    .quick-jump-title {
      margin: 0.22rem 0 0;
      font-size: 0.98rem;
      font-weight: 800;
      color: #0f172a;
    }

    .quick-jump-meta {
      color: #475569;
      font-size: 0.85rem;
    }
  </style>
@endpush

@push('scripts')
  <script>
    (function () {
      const query = @json($q);
      if (!query) return;

      const targetSection = document.getElementById('hasil-pencarian');
      if (!targetSection) return;

      const offsetTop = 86;
      const targetY = targetSection.getBoundingClientRect().top + window.pageYOffset - offsetTop;

      window.requestAnimationFrame(function () {
        window.scrollTo({
          top: Math.max(0, targetY),
          behavior: 'smooth'
        });
      });

      if (window.history && typeof window.history.replaceState === 'function') {
        window.history.replaceState(null, '', window.location.pathname + window.location.search + '#hasil-pencarian');
      }
    })();
  </script>
@endpush
