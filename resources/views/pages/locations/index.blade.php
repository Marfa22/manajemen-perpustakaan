@extends('layouts.main')

@section('header')
    <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Lokasi</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Beranda</a></li>
              <li class="breadcrumb-item active">Lokasi</li>
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

    @if (session('error'))
      <script>
        Swal.fire({
          title: "Tidak Dapat Diproses",
          text: "{{ session('error') }}",
          icon: "error",
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

    <div class="row master-grid-row">
      <div class="col-lg-5">
        <div class="table-shell mb-3">
          <div class="table-topbar">
            <div class="table-title">
              <h3>Data Lokasi</h3>
              <span class="table-subtitle">Kelola daftar lokasi untuk barang kantor.</span>
            </div>
            <a href="{{ request()->getBaseUrl() }}/locations/create" class="btn btn-sm btn-primary">+ Tambah Lokasi</a>
          </div>

          <form class="table-filter mb-3" method="GET" action="{{ request()->getBaseUrl() }}/locations">
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama / slug lokasi">
            <input type="hidden" name="q_sub" value="{{ $qSub }}">
            <button class="btn btn-outline-primary" type="submit">Search</button>
            @if ($q !== '')
              <a href="{{ request()->getBaseUrl() }}/locations?q_sub={{ urlencode($qSub) }}" class="btn btn-outline-secondary">Reset</a>
            @endif
          </form>

          <div class="table-responsive">
            <table class="table table-modern">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Lokasi</th>
                  <th>Slug</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($locations as $location)
                  <tr>
                    <td>{{ ($locations->currentPage() - 1) * $locations->perPage() + $loop->iteration }}</td>
                    <td>{{ $location->name }}</td>
                    <td>{{ $location->slug ?: '-' }}</td>
                    <td>
                      <div class="d-flex action-stack">
                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modal-edit-location-{{ $location->id }}">
                          Ubah
                        </button>
                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#modal-delete-location-{{ $location->id }}">
                          Hapus
                        </button>
                      </div>
                    </td>
                  </tr>

                  <div class="modal fade" id="modal-edit-location-{{ $location->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content modern-edit-modal">
                        <form action="{{ request()->getBaseUrl() }}/locations/{{ $location->id }}" method="POST" class="modern-edit-form">
                          @csrf
                          @method('PUT')
                          <div class="modal-header">
                            <div class="modern-edit-title-wrap">
                              <span class="modern-edit-icon">
                                <i class="fas fa-pen"></i>
                              </span>
                              <h5 class="modal-title">Ubah Lokasi</h5>
                            </div>
                            <button type="button" class="modern-edit-close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="form-group mb-0">
                              <label class="modern-edit-label" for="location-name-{{ $location->id }}">Nama Lokasi</label>
                              <input type="text" name="name" id="location-name-{{ $location->id }}" value="{{ $location->name }}" class="form-control modern-edit-input" required>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary modern-edit-btn-cancel" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary modern-edit-btn-save">Simpan</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                  <div class="modal fade delete-modal" id="modal-delete-location-{{ $location->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content modern-delete-modal">
                        <form action="{{ request()->getBaseUrl() }}/locations/{{ $location->id }}" method="post" class="delete-modal-form">
                          @csrf
                          @method('DELETE')
                          <div class="modal-header">
                            <span class="delete-modal-icon">
                              <i class="fas fa-trash-alt"></i>
                            </span>
                            <button type="button" class="delete-modal-close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <h5 class="delete-modal-title">Hapus lokasi?</h5>
                            <p class="delete-modal-text">
                              Lokasi <strong>{{ $location->name }}</strong> akan dihapus permanen dari sistem.
                            </p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-cancel" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-confirm">
                              <i class="fas fa-trash-alt mr-1"></i> Hapus
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">Data lokasi tidak ditemukan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="table-footer">
            {{ $locations->links('pagination::bootstrap-5') }}
          </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="table-shell mb-3">
          <div class="table-topbar">
            <div class="table-title">
              <h3>Data Sub Lokasi</h3>
              <span class="table-subtitle">Kelola sub lokasi berdasarkan lokasi induk.</span>
            </div>
          </div>

          <form action="{{ request()->getBaseUrl() }}/locations/sub-locations" method="POST" class="mb-3 sub-location-form">
            @csrf
            <div class="row sub-location-row">
              <div class="col-md-5 mb-2">
                <input type="text" name="sub_location_name" value="{{ old('sub_location_name') }}" class="form-control @error('sub_location_name') is-invalid @enderror" placeholder="Nama sub lokasi">
                @error('sub_location_name')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-md-5 mb-2">
                <select name="sub_location_location_id" class="form-control @error('sub_location_location_id') is-invalid @enderror">
                  <option value="">Pilih lokasi induk</option>
                  @foreach ($locationOptions as $locationOption)
                    <option value="{{ $locationOption->id }}" {{ (string) old('sub_location_location_id') === (string) $locationOption->id ? 'selected' : '' }}>
                      {{ $locationOption->name }}
                    </option>
                  @endforeach
                </select>
                @error('sub_location_location_id')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-md-2 mb-2">
                <button class="btn btn-primary w-100 sub-location-submit" type="submit">Simpan</button>
              </div>
            </div>
          </form>

          <form class="table-filter mb-3" method="GET" action="{{ request()->getBaseUrl() }}/locations">
            <input type="hidden" name="q" value="{{ $q }}">
            <input type="text" name="q_sub" value="{{ $qSub }}" class="form-control" placeholder="Cari sub lokasi / lokasi / slug">
            <button class="btn btn-outline-primary" type="submit">Search</button>
            @if ($qSub !== '')
              <a href="{{ request()->getBaseUrl() }}/locations?q={{ urlencode($q) }}" class="btn btn-outline-secondary">Reset</a>
            @endif
          </form>

          <div class="table-responsive">
            <table class="table table-modern">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Sub Lokasi</th>
                  <th>Lokasi Induk</th>
                  <th>Slug</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($subLocations as $subLocation)
                  <tr>
                    <td>{{ ($subLocations->currentPage() - 1) * $subLocations->perPage() + $loop->iteration }}</td>
                    <td>{{ $subLocation->name }}</td>
                    <td>{{ $subLocation->location->name ?? '-' }}</td>
                    <td>{{ $subLocation->slug ?: '-' }}</td>
                    <td>
                      <div class="d-flex action-stack">
                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modal-edit-sub-location-{{ $subLocation->id }}">
                          Ubah
                        </button>
                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#modal-delete-sub-location-{{ $subLocation->id }}">
                          Hapus
                        </button>
                      </div>
                    </td>
                  </tr>

                  <div class="modal fade" id="modal-edit-sub-location-{{ $subLocation->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content modern-edit-modal">
                        <form action="{{ request()->getBaseUrl() }}/locations/sub-locations/{{ $subLocation->id }}" method="POST" class="modern-edit-form">
                          @csrf
                          @method('PUT')
                          <div class="modal-header">
                            <div class="modern-edit-title-wrap">
                              <span class="modern-edit-icon">
                                <i class="fas fa-pen"></i>
                              </span>
                              <h5 class="modal-title">Ubah Sub Lokasi</h5>
                            </div>
                            <button type="button" class="modern-edit-close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="form-group">
                              <label class="modern-edit-label" for="sub-location-name-{{ $subLocation->id }}">Nama Sub Lokasi</label>
                              <input type="text" name="name" id="sub-location-name-{{ $subLocation->id }}" value="{{ $subLocation->name }}" class="form-control modern-edit-input" required>
                            </div>
                            <div class="form-group mb-0 mt-3">
                              <label class="modern-edit-label" for="sub-location-parent-{{ $subLocation->id }}">Lokasi Induk</label>
                              <select name="location_id" id="sub-location-parent-{{ $subLocation->id }}" class="form-control modern-edit-input" required>
                                <option value="">Pilih lokasi induk</option>
                                @foreach ($locationOptions as $locationOption)
                                  <option value="{{ $locationOption->id }}" {{ (string) $locationOption->id === (string) $subLocation->location_id ? 'selected' : '' }}>
                                    {{ $locationOption->name }}
                                  </option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary modern-edit-btn-cancel" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary modern-edit-btn-save">Simpan</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                  <div class="modal fade delete-modal" id="modal-delete-sub-location-{{ $subLocation->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content modern-delete-modal">
                        <form action="{{ request()->getBaseUrl() }}/locations/sub-locations/{{ $subLocation->id }}" method="POST" class="delete-modal-form">
                          @csrf
                          @method('DELETE')
                          <div class="modal-header">
                            <span class="delete-modal-icon">
                              <i class="fas fa-trash-alt"></i>
                            </span>
                            <button type="button" class="delete-modal-close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <h5 class="delete-modal-title">Hapus sub lokasi?</h5>
                            <p class="delete-modal-text">
                              Sub lokasi <strong>{{ $subLocation->name }}</strong> akan dihapus permanen dari sistem.
                            </p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-cancel" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-confirm">
                              <i class="fas fa-trash-alt mr-1"></i> Hapus
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">Data sub lokasi tidak ditemukan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="table-footer">
            {{ $subLocations->links('pagination::bootstrap-5') }}
          </div>
        </div>
      </div>
    </div>
@endsection

@push('styles')
  <style>
    .master-grid-row > [class*='col-'] {
      margin-bottom: 12px;
    }

    .sub-location-form {
      padding-top: 2px;
    }

    .sub-location-row {
      margin-left: -6px;
      margin-right: -6px;
    }

    .sub-location-row > [class*='col-'] {
      padding-left: 6px;
      padding-right: 6px;
    }

    .sub-location-submit {
      min-height: 42px;
    }

    .modern-edit-modal {
      border: 0;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 22px 48px rgba(15, 23, 42, 0.28);
    }

    .modern-edit-form .modal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid #e2e8f0;
      background: linear-gradient(135deg, #f8fbff 0%, #eef5ff 100%);
      padding: 16px 20px;
    }

    .modern-edit-title-wrap {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .modern-edit-form .modal-title {
      margin: 0;
      font-size: 1.1rem;
      font-weight: 700;
      color: #1e293b;
      letter-spacing: 0.01em;
    }

    .modern-edit-icon {
      width: 34px;
      height: 34px;
      border-radius: 10px;
      background: #dbeafe;
      color: #2563eb;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 0.95rem;
    }

    .modern-edit-close {
      border: 0;
      background: transparent;
      color: #64748b;
      font-size: 1.7rem;
      line-height: 1;
      padding: 0 2px;
    }

    .modern-edit-close:hover {
      color: #334155;
    }

    .modern-edit-form .modal-body {
      padding: 18px 20px;
    }

    .modern-edit-label {
      display: block;
      margin: 0 0 8px;
      font-size: 0.78rem;
      font-weight: 700;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      color: #475569;
    }

    .modern-edit-input {
      border: 1px solid #cbd5e1;
      border-radius: 12px;
      min-height: 44px;
      padding: 10px 14px;
      font-size: 1rem;
      color: #1f2937;
      box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.03);
    }

    .modern-edit-input:focus {
      border-color: #60a5fa;
      box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.14);
    }

    .modern-edit-form .modal-footer {
      border-top: 1px solid #e2e8f0;
      background: #fafcff;
      padding: 14px 20px;
    }

    .modern-edit-btn-cancel,
    .modern-edit-btn-save {
      min-width: 92px;
      border-radius: 10px;
      font-weight: 600;
      padding: 8px 16px;
    }
  </style>
@endpush
