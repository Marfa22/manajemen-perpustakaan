@extends('layouts.main')

@section('header')
    <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Inventaris Barang Kantor</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Beranda</a></li>
              <li class="breadcrumb-item active">Inventaris</li>
            </ol>
          </div>
        </div>
@endsection

@section('content')
    @if (session('success'))
      <script>
          Swal.fire({
            title: "Berhasil",
            text: "{{ session('success') }}",
            icon: "success",
           });
        </script> 
    @endif
    <div class="row">
      <div class="col-lg-4 col-md-6 mb-3">
        <div class="dash-card dash-emerald">
          <i class="dash-icon fas fa-check-circle"></i>
          <h3 class="dash-countup" data-target="{{ $baikCount }}">0</h3>
          <p>Barang Baik</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 mb-3">
        <div class="dash-card dash-amber">
          <i class="dash-icon fas fa-tools"></i>
          <h3 class="dash-countup" data-target="{{ $rusakRinganCount }}">0</h3>
          <p>Rusak Ringan</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 mb-3">
        <div class="dash-card dash-slate">
          <i class="dash-icon fas fa-exclamation-triangle"></i>
          <h3 class="dash-countup" data-target="{{ $rusakBeratCount }}">0</h3>
          <p>Rusak Berat</p>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="table-shell">
          <div class="table-topbar">
            <div class="table-title">
              <h3>Data Inventaris</h3>
              <span class="table-subtitle">Daftar barang kantor dan kondisi terkini.</span>
            </div>
            <a href="{{ request()->getBaseUrl() }}/inventory/create" class="btn btn-sm btn-primary">+ Tambah Barang Kantor</a>
          </div>

          <form class="table-filter" method="GET" action="{{ request()->getBaseUrl() }}/inventory">
            <input type="text" name="q" value="{{ $q }}" class="form-control" style="max-width: 260px;" placeholder="Cari nama / kode / serial / merk / kategori / lokasi / sub lokasi">
            <button class="btn btn-outline-primary" type="submit">Search</button>
            @if ($q !== '' || $hasActiveFilters)
              <a href="{{ request()->getBaseUrl() }}/inventory" class="btn btn-outline-secondary">Reset</a>
            @endif

            <div class="filter-dropdown" id="inventoryFilterDropdown">
              <button type="button" id="inventoryFilterToggle" class="btn btn-outline-warning">
                <i class="fas fa-sliders-h mr-1"></i>
                Filter
                @if ($activeFilterCount > 0)
                  <span class="filter-count">{{ $activeFilterCount }}</span>
                @endif
              </button>

              <div id="inventoryFilterMenu" class="filter-dropdown-menu">
                <div class="filter-dropdown-head">
                  <strong>Filters</strong>
                  <a href="{{ request()->getBaseUrl() }}/inventory" class="filter-clear-all">Clear all</a>
                </div>

                <div class="filter-grid">
                  <div class="filter-group">
                    <p>Kategori</p>
                    @forelse ($categoryOptions as $categoryOption)
                      <label class="filter-checkbox">
                        <input
                          type="checkbox"
                          name="category_ids[]"
                          value="{{ $categoryOption->id }}"
                          {{ in_array((int) $categoryOption->id, $selectedCategoryIds, true) ? 'checked' : '' }}
                        >
                        <span>{{ $categoryOption->name }}</span>
                      </label>
                    @empty
                      <span class="chip-empty-text">Belum ada data.</span>
                    @endforelse
                  </div>

                  <div class="filter-group">
                    <p>Merk</p>
                    @forelse ($brandOptions as $brandOption)
                      <label class="filter-checkbox">
                        <input
                          type="checkbox"
                          name="brand_ids[]"
                          value="{{ $brandOption->id }}"
                          {{ in_array((int) $brandOption->id, $selectedBrandIds, true) ? 'checked' : '' }}
                        >
                        <span>{{ $brandOption->name }}{{ $brandOption->category?->name ? ' - ' . $brandOption->category->name : '' }}</span>
                      </label>
                    @empty
                      <span class="chip-empty-text">Belum ada data.</span>
                    @endforelse
                  </div>

                  <div class="filter-group">
                    <p>Kondisi</p>
                    @forelse ($conditionOptions as $conditionOption)
                      <label class="filter-checkbox">
                        <input
                          type="checkbox"
                          name="conditions[]"
                          value="{{ $conditionOption }}"
                          {{ in_array((string) $conditionOption, $selectedConditions, true) ? 'checked' : '' }}
                        >
                        <span>{{ $conditionOption }}</span>
                      </label>
                    @empty
                      <span class="chip-empty-text">Belum ada data.</span>
                    @endforelse
                  </div>

                  <div class="filter-group">
                    <p>Lokasi</p>
                    @forelse ($locationOptions as $locationOption)
                      <label class="filter-checkbox">
                        <input
                          type="checkbox"
                          name="location_ids[]"
                          value="{{ $locationOption->id }}"
                          {{ in_array((int) $locationOption->id, $selectedLocationIds, true) ? 'checked' : '' }}
                        >
                        <span>{{ $locationOption->name }}</span>
                      </label>
                    @empty
                      <span class="chip-empty-text">Belum ada data.</span>
                    @endforelse
                  </div>
                </div>

                <div class="filter-dropdown-foot">
                  <button type="submit" class="btn btn-warning btn-sm">Apply Filters</button>
                </div>
              </div>
            </div>
          </form>

          @if ($q !== '' && $topMatchedInventory)
            <div class="top-match-card">
              <div>
                <small class="top-match-label">Paling Relevan</small>
                <h4 class="top-match-title">{{ $topMatchedInventory->nama }}</h4>
                <p class="top-match-meta mb-0">
                  {{ $topMatchedInventory->kode_barang ?: '-' }}
                  • {{ $topMatchedInventory->brand?->name ?: '-' }}
                  • {{ $topMatchedInventory->location?->name ?: '-' }}
                </p>
              </div>
              <a href="{{ request()->getBaseUrl() }}/inventory/edit/{{ $topMatchedInventory->id }}" class="btn btn-sm btn-primary">Buka Barang Ini</a>
            </div>
          @endif

          <div class="table-responsive">
            <table class="table table-modern">
              <thead>
                <tr>
                  <th>NO</th>
                  <th>BARANG</th>
                  <th>Kode Barang</th>
                  <th>Serial Number</th>
                  <th>Merk</th>
                  <th>Penanggung Jawab</th>
                  <th>Kategori</th>
                  <th>Kondisi</th>
                  <th>Lokasi</th>
                  <th>Dokumen</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($inventories as $inventory)
                  <tr>
                    <td>{{ ($inventories->currentPage() - 1) * $inventories->perPage() + $loop->iteration }}</td>
                    <td>
                      <div class="cell-primary">{{ $inventory->nama }}</div>
                      @php
                        $descriptionRaw = trim((string) ($inventory->deskripsi ?? ''));
                        $descriptionItems = collect(preg_split('/\r\n|\r|\n/', $descriptionRaw))
                          ->map(function ($item) {
                              return trim((string) preg_replace('/^\s*(?:[-*]|\d+[.)])\s*/', '', (string) $item));
                          })
                          ->filter(function ($item) {
                              return $item !== '';
                          })
                          ->values();
                      @endphp

                      @if ($descriptionItems->isNotEmpty())
                        <ul class="inventory-desc-list cell-secondary mb-0">
                          @foreach ($descriptionItems as $descriptionItem)
                            <li>{{ $descriptionItem }}</li>
                          @endforeach
                        </ul>
                      @else
                        <p class="cell-secondary">-</p>
                      @endif
                    </td>
                    <td>{{ $inventory->kode_barang }}</td>
                    <td>{{ $inventory->serial_number ?: '-' }}</td>
                    <td>{{ $inventory->brand->name ?? '-' }}</td>
                    <td>{{ $inventory->penanggung_jawab ?: '-' }}</td>
                    <td>{{ $inventory->category->name ?? '-' }}</td>
                    <td>
                      @php
                        $kondisiClass = 'success';
                        if ($inventory->kondisi === 'Rusak Ringan') {
                          $kondisiClass = 'warning';
                        } elseif ($inventory->kondisi === 'Rusak Berat') {
                          $kondisiClass = 'danger';
                        }
                      @endphp
                      <span class="badge-soft {{ $kondisiClass }}">{{ $inventory->kondisi }}</span>
                    </td>
                    <td>
                      @php
                        $lokasiLabel = $inventory->location->name ?? '-';
                        $subLokasiLabel = $inventory->subLocation->name ?? '';
                      @endphp
                      {{ $subLokasiLabel !== '' ? $lokasiLabel . ' -> ' . $subLokasiLabel : $lokasiLabel }}
                    </td>
                    <td>
                      @if ($inventory->photo_path)
                        @php
                          $fileExt = strtolower(pathinfo($inventory->photo_path, PATHINFO_EXTENSION));
                          $isImageFile = in_array($fileExt, ['jpg', 'jpeg', 'png'], true);
                          $fileUrl = asset('storage/' . $inventory->photo_path);
                        @endphp
                        <div class="doc-preview-stack">
                          @if ($isImageFile)
                            <img src="{{ $fileUrl }}" alt="Dokumen {{ $inventory->nama }}" class="item-photo-thumb mb-2">
                          @endif
                          <a href="{{ $fileUrl }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">
                            Lihat
                          </a>
                        </div>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>
                    <td>
                      <div class="d-flex action-stack">
                        <a href="{{ request()->getBaseUrl() }}/inventory/edit/{{ $inventory->id }}" class="btn btn-outline-primary">Ubah</a>
                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#modal-delete-{{ $inventory->id }}">
                          Hapus
                        </button>
                      </div>
                    </td>
                  </tr>
                  @include('pages.inventory.delete-confirmation')
                @empty
                  <tr>
                    <td colspan="11" class="text-center text-muted py-4">
                      Data tidak ditemukan.
                    </td>
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

@push('styles')
  <style>
    .filter-count {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 20px;
      height: 20px;
      border-radius: 999px;
      background: #f59e0b;
      color: #fff;
      font-size: 0.72rem;
      font-weight: 700;
      margin-left: 4px;
      padding: 0 6px;
    }

    .top-match-card {
      border: 1px solid #bfdbfe;
      border-radius: 12px;
      background: linear-gradient(120deg, #eff6ff 0%, #f8fbff 100%);
      padding: 0.85rem 0.95rem;
      margin-bottom: 0.95rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.8rem;
      flex-wrap: wrap;
    }

    .top-match-label {
      color: #1d4ed8;
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.03em;
    }

    .top-match-title {
      margin: 0.2rem 0 0;
      font-size: 0.98rem;
      font-weight: 800;
      color: #0f172a;
    }

    .top-match-meta {
      color: #475569;
      font-size: 0.84rem;
    }

    .table-shell {
      overflow: visible !important;
    }

    .filter-dropdown {
      position: relative;
    }

    .filter-dropdown-menu {
      display: none;
      position: absolute;
      right: 0;
      top: calc(100% + 8px);
      z-index: 1050;
      width: min(92vw, 560px);
      max-height: 68vh;
      overflow-y: auto;
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      box-shadow: 0 18px 32px rgba(15, 23, 42, 0.18);
      padding: 12px;
    }

    .filter-dropdown-menu.is-open {
      display: block;
    }

    .filter-dropdown-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    .filter-clear-all {
      font-size: 0.82rem;
      font-weight: 700;
      color: #f59e0b;
    }

    .filter-clear-all:hover {
      color: #d97706;
      text-decoration: none;
    }

    .filter-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 10px 16px;
    }

    .filter-group p {
      margin: 0 0 6px;
      font-size: 0.78rem;
      font-weight: 700;
      color: #475569;
    }

    .filter-group {
      min-height: 0;
    }

    .filter-checkbox {
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 0 0 5px;
      cursor: pointer;
      font-size: 0.83rem;
      color: #334155;
    }

    .filter-checkbox input {
      width: 14px;
      height: 14px;
      accent-color: #f59e0b;
    }

    .filter-checkbox span {
      line-height: 1.35;
    }

    .chip-empty-text {
      color: #94a3b8;
      font-size: 0.8rem;
    }

    .doc-preview-stack {
      display: inline-flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 6px;
    }

    .inventory-desc-list {
      margin: 4px 0 0 16px;
      padding: 0;
    }

    .inventory-desc-list li {
      margin-bottom: 2px;
      line-height: 1.35;
    }

    .filter-dropdown-foot {
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px solid #f1f5f9;
      display: flex;
      justify-content: flex-end;
    }

    @media (max-width: 768px) {
      .filter-dropdown-menu {
        left: 0;
        right: auto;
        width: min(92vw, 96vw);
      }

      .filter-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
@endpush

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var filterToggle = document.getElementById('inventoryFilterToggle');
      var filterMenu = document.getElementById('inventoryFilterMenu');
      var filterDropdown = document.getElementById('inventoryFilterDropdown');
      if (filterToggle && filterMenu && filterDropdown) {
        filterToggle.addEventListener('click', function (event) {
          event.preventDefault();
          event.stopPropagation();
          filterMenu.classList.toggle('is-open');
        });

        filterMenu.addEventListener('click', function (event) {
          event.stopPropagation();
        });

        document.addEventListener('click', function (event) {
          if (!filterDropdown.contains(event.target)) {
            filterMenu.classList.remove('is-open');
          }
        });
      }

      var counters = document.querySelectorAll('.dash-countup');
      counters.forEach(function (counter, index) {
        var target = Number(counter.getAttribute('data-target') || 0);
        var startTime = null;
        var duration = 850 + (index * 90);

        function tick(timestamp) {
          if (startTime === null) {
            startTime = timestamp;
          }

          var progress = Math.min((timestamp - startTime) / duration, 1);
          var current = Math.round(target * progress);
          counter.textContent = current.toLocaleString('id-ID');

          if (progress < 1) {
            requestAnimationFrame(tick);
          }
        }

        requestAnimationFrame(tick);
      });
    });
  </script>
@endpush
