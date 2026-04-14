@extends('layouts.main')

@section('header')
    <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Barang</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Beranda</a></li>
              <li class="breadcrumb-item active">Barang</li>
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

    @if ($errors->any())
      <script>
          Swal.fire({
            title: "Terjadi Kesalahan",
            text: "@foreach ($errors->all() as $error) {{ $error }} @endforeach",
            icon: "error",
           });
        </script>
    @endif

    <div class="row">
      <div class="col-lg-4 col-md-6 mb-3">
        <div class="dash-card dash-blue">
          <i class="dash-icon fas fa-box-open"></i>
          <h3 class="dash-countup" data-target="{{ $foundTotalCount }}">0</h3>
          <p>Jumlah Barang</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 mb-3">
        <div class="dash-card dash-slate">
          <i class="dash-icon fas fa-hourglass-half"></i>
          <h3 class="dash-countup" data-target="{{ $foundNotPickedCount }}">0</h3>
          <p>Barang Belum Diambil</p>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 mb-3">
        <div class="dash-card dash-gold">
          <i class="dash-icon fas fa-check-circle"></i>
          <h3 class="dash-countup" data-target="{{ $foundPickedCount }}">0</h3>
          <p>Barang Sudah Diambil</p>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="table-shell">
          <div class="table-topbar">
            <div class="table-title">
              <h3>Data Barang Temuan</h3>
              <span class="table-subtitle">Daftar barang temuan beserta status dan data pengembalian.</span>
            </div>
            <a href="{{ request()->getBaseUrl() }}/products/create" class="btn btn-sm btn-primary">+ Tambah Barang</a>
          </div>

          <form class="table-filter" method="GET" action="{{ request()->getBaseUrl() }}/products">
            <input type="text" name="q" value="{{ $q }}" class="form-control" style="max-width: 360px;" placeholder="Cari nama / kode / kategori / lokasi / status / pengambil">
            <button class="btn btn-outline-primary" type="submit">Search</button>
            @if ($q !== '')
              <a href="{{ request()->getBaseUrl() }}/products" class="btn btn-outline-secondary">Reset</a>
            @endif
          </form>

          @if ($q !== '' && $topMatchedProduct)
            <div class="top-match-card">
              <div>
                <small class="top-match-label">Paling Relevan</small>
                <h4 class="top-match-title">{{ $topMatchedProduct->name }}</h4>
                <p class="top-match-meta mb-0">{{ $topMatchedProduct->category?->name ?: '-' }} • {{ $topMatchedProduct->found_location ?: '-' }}</p>
              </div>
              <a href="{{ request()->getBaseUrl() }}/products/edit/{{ $topMatchedProduct->id }}" class="btn btn-sm btn-primary">Buka Barang Ini</a>
            </div>
          @endif

          <div class="table-responsive">
            <table class="table table-modern">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Barang</th>
                  <th>Kode Barang</th>
                  <th>Kategori</th>
                  <th>Lokasi Ditemukan</th>
                  <th>Tanggal Ditemukan</th>
                  <th>Status</th>
                  <th>Data Pengambilan</th>
                  <th>Foto Barang</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($products as $product)
                  <tr>
                    <td>{{ ($products->currentPage() -1) * $products->perPage() + $loop->iteration }}</td>
                    <td>
                      <div class="cell-primary">{{ $product->name }}</div>
                      <p class="cell-secondary">{{ $product->description ?: '-' }}</p>
                    </td>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>{{ $product->found_location ?: '-' }}</td>
                    <td>
                      @if ($product->found_at)
                        {{ \Carbon\Carbon::parse($product->found_at)->format('d M Y') }}
                      @else
                        -
                      @endif
                    </td>
                    <td>
                      @if ($product->pickup_status === 'sudah_diambil')
                        <span class="status-pill picked">Sudah Diambil</span>
                      @else
                        <span class="status-pill pending">Belum Diambil</span>
                      @endif
                    </td>
                    <td>
                      @if ($product->pickup_status === 'sudah_diambil' && $product->returnRecord)
                        <div class="cell-primary">{{ $product->returnRecord->receiver_name }}</div>
                        <p class="cell-secondary mb-1">Telp: {{ $product->returnRecord->receiver_phone }}</p>
                        <p class="cell-secondary mb-1">Alamat: {{ $product->returnRecord->receiver_address ?: '-' }}</p>
                        <p class="cell-secondary mb-1">
                          Diambil:
                          {{ $product->returnRecord->returned_at ? $product->returnRecord->returned_at->format('d M Y H:i') : '-' }}
                        </p>
                        @if ($product->returnRecord->notes)
                          <p class="cell-secondary mt-1 mb-0">Catatan: {{ $product->returnRecord->notes }}</p>
                        @endif
                      @elseif ($product->pickup_status === 'sudah_diambil')
                        <span class="text-warning">Data pengambilan belum diisi.</span>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>
                    <td>
                      @if ($product->photo_path)
                        <img src="{{ route('products.photo.preview', $product->id) }}" alt="Foto {{ $product->name }}" class="item-photo-thumb mb-2">
                        <a href="{{ route('products.photo.show', $product->id) }}" class="btn btn-outline-primary btn-sm">
                          Lihat
                        </a>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>
                    <td>
                      <div class="d-flex action-stack">
                        @if ($product->pickup_status !== 'sudah_diambil')
                          <button
                            type="button"
                            class="btn btn-outline-success js-return-trigger"
                            data-toggle="modal"
                            data-target="#modal-return"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            data-return-url="/products/{{ $product->id }}/return"
                          >
                            Proses Pengembalian
                          </button>
                        @endif
                        <a href="{{ request()->getBaseUrl() }}/products/edit/{{ $product->id }}" class="btn btn-outline-primary">Ubah</a>
                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#modal-delete-{{ $product->id }}">
                          Hapus
                        </button>
                      </div>
                    </td>
                  </tr>
                  @include('pages.products.delete-confirmation')
                @empty
                  <tr>
                    <td colspan="10" class="text-center text-muted py-4">Data barang tidak ditemukan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="table-footer">
            {{ $products->links('pagination::bootstrap-5') }}
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade return-modal" id="modal-return" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-return-modal">
          <form id="form-return" method="POST">
            @csrf
            <input type="hidden" name="return_product_id" id="return_product_id" value="{{ old('return_product_id') }}">
            <input type="hidden" name="return_product_name" id="return_product_name" value="{{ old('return_product_name') }}">

            <div class="modal-header">
              <h5 class="return-modal-title mb-0">Proses Pengembalian Barang</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <p class="return-modal-subtitle">
                Barang: <strong id="return-product-label">{{ old('return_product_name', '-') }}</strong>
              </p>

              <div class="form-group">
                <label for="receiver_name">Nama Pengambil</label>
                <input
                  type="text"
                  name="receiver_name"
                  id="receiver_name"
                  class="form-control"
                  value="{{ old('receiver_name') }}"
                  placeholder="Contoh: Budi Santoso"
                  required
                >
              </div>

              <div class="form-group">
                <label for="receiver_phone">Nomor Telepon</label>
                <input
                  type="text"
                  name="receiver_phone"
                  id="receiver_phone"
                  class="form-control"
                  value="{{ old('receiver_phone') }}"
                  placeholder="Contoh: 0812xxxxxx"
                  required
                >
              </div>

              <div class="form-group">
                <label for="receiver_address">Alamat Pengambil</label>
                <textarea
                  name="receiver_address"
                  id="receiver_address"
                  rows="3"
                  class="form-control"
                  placeholder="Contoh: Jl. Merdeka No. 10, Jakarta"
                  required
                >{{ old('receiver_address') }}</textarea>
              </div>

              <div class="form-group mb-0">
                <label for="return_notes">Catatan (Opsional)</label>
                <textarea
                  name="return_notes"
                  id="return_notes"
                  rows="3"
                  class="form-control"
                  placeholder="Tambahkan catatan pengambilan jika diperlukan"
                >{{ old('return_notes') }}</textarea>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-success">
                <i class="fas fa-check-circle mr-1"></i>Simpan Pengembalian
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
@endsection

@push('styles')
  <style>
    .return-modal .modal-dialog {
      max-width: 560px;
    }

    .return-modal .modern-return-modal {
      border: 0;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 24px 40px rgba(15, 23, 42, 0.2);
    }

    .return-modal .modal-header {
      border-bottom: 1px solid #eef2f7;
      background: #f8fbff;
      padding: 0.85rem 1rem;
    }

    .return-modal .modal-body {
      padding: 0.95rem 1rem 0.6rem;
    }

    .return-modal .modal-footer {
      border-top: 1px solid #eef2f7;
      background: #f8fbff;
      padding: 0.75rem 1rem;
      gap: 8px;
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

    .return-modal-title {
      font-weight: 800;
      font-size: 1rem;
      color: #0f172a;
    }

    .return-modal-subtitle {
      font-size: 0.88rem;
      color: #475569;
      margin-bottom: 0.8rem;
    }

    .return-modal label {
      font-size: 0.8rem;
      font-weight: 700;
      color: #475569;
      margin-bottom: 0.35rem;
    }
  </style>
@endpush

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
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

      var modalEl = document.getElementById('modal-return');
      var returnForm = document.getElementById('form-return');
      var returnProductLabel = document.getElementById('return-product-label');
      var returnProductIdInput = document.getElementById('return_product_id');
      var returnProductNameInput = document.getElementById('return_product_name');

      if (modalEl && returnForm) {
        $(modalEl).on('show.bs.modal', function (event) {
          var button = event.relatedTarget;
          if (!button) {
            return;
          }

          var productId = button.getAttribute('data-product-id') || '';
          var productName = button.getAttribute('data-product-name') || '-';
          var actionUrl = button.getAttribute('data-return-url') || '';

          if (actionUrl) {
            returnForm.setAttribute('action', actionUrl);
          }

          if (returnProductLabel) {
            returnProductLabel.textContent = productName;
          }

          if (returnProductIdInput) {
            returnProductIdInput.value = productId;
          }

          if (returnProductNameInput) {
            returnProductNameInput.value = productName;
          }
        });

        @if ($errors->any() && old('return_product_id'))
          returnForm.setAttribute('action', '/products/{{ old('return_product_id') }}/return');
          if (returnProductLabel) {
            returnProductLabel.textContent = @json(old('return_product_name', '-'));
          }
          $('#modal-return').modal('show');
        @endif
      }
    });
  </script>
@endpush
