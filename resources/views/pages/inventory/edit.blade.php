@extends('layouts.main')

@section('header')
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Ubah Inventaris Barang Kantor</h1>
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
      <div class="col">
        <form action="{{ request()->getBaseUrl() }}/inventory/{{ $inventory->id }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="card crud-form-card">
            <div class="card-header">
              <h3>Form Ubah Inventaris Kantor</h3>
              <p>Perbarui detail barang kantor sesuai kondisi saat ini.</p>
            </div>
            <div class="card-body">
              <div class="crud-form-grid">
                <div class="form-group">
                  <label for="nama" class="form-label">Nama</label>
                  <input type="text" name="nama" id="nama" autocomplete="off" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $inventory->nama) }}" placeholder="Contoh: Laptop Dell">
                  @error('nama')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="kode_barang" class="form-label">Kode Barang</label>
                  <input type="text" name="kode_barang" id="kode_barang" autocomplete="off" class="form-control @error('kode_barang') is-invalid @enderror" value="{{ old('kode_barang', $inventory->kode_barang) }}" placeholder="Contoh: INV-001">
                  @error('kode_barang')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="serial_number" class="form-label">Serial Number</label>
                  <input type="text" name="serial_number" id="serial_number" autocomplete="off" class="form-control @error('serial_number') is-invalid @enderror" value="{{ old('serial_number', $inventory->serial_number) }}" placeholder="Contoh: SN-ABC-001">
                  @error('serial_number')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="penanggung_jawab" class="form-label">Penanggung Jawab</label>
                  <input type="text" name="penanggung_jawab" id="penanggung_jawab" autocomplete="off" class="form-control @error('penanggung_jawab') is-invalid @enderror" value="{{ old('penanggung_jawab', $inventory->penanggung_jawab) }}" placeholder="Contoh: Budi Santoso">
                  @error('penanggung_jawab')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group full-width">
                  <label class="form-label">Kategori / Merk</label>
                  <input type="hidden" name="category_id" id="category_id" value="{{ old('category_id', $inventory->category_id) }}">
                  <input type="hidden" name="brand_id" id="brand_id" value="{{ old('brand_id', $inventory->brand_id) }}">

                  <div class="hier-picker" id="hierPickerEdit">
                    <button type="button" class="form-control hier-trigger @if($errors->has('category_id') || $errors->has('brand_id')) is-invalid @endif">
                      <span class="hier-trigger-text">Pilih kategori -> merk</span>
                      <span class="hier-trigger-arrow">></span>
                    </button>

                    <div class="hier-menu">
                      <ul class="hier-level">
                        @foreach ($categories as $category)
                          @php
                            $categoryBrands = $category->brands;
                          @endphp
                          <li class="hier-item">
                            <button type="button" class="hier-action">
                              <span>{{ $category->name }}</span>
                              <span class="hier-arrow">></span>
                            </button>
                            <div class="hier-submenu">
                              <ul class="hier-level">
                                @forelse ($categoryBrands as $brand)
                                  <li class="hier-item">
                                    <button
                                      type="button"
                                      class="hier-action hier-select-option"
                                      data-category-id="{{ $category->id }}"
                                      data-brand-id="{{ $brand->id }}"
                                      data-category-name="{{ $category->name }}"
                                      data-brand-name="{{ $brand->name }}"
                                    >
                                      {{ $brand->name }}
                                    </button>
                                  </li>
                                @empty
                                  <li class="hier-item">
                                    <span class="hier-empty">Belum ada merk untuk kategori ini.</span>
                                  </li>
                                @endforelse
                              </ul>
                            </div>
                          </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>

                  <small class="form-text text-muted">Pilih item dengan urutan kategori lalu merk.</small>
                  @error('category_id')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                  @error('brand_id')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group full-width">
                  <label class="form-label">Lokasi / Sub Lokasi</label>
                  <input type="hidden" name="location_id" id="location_id" value="{{ old('location_id', $inventory->location_id) }}">
                  <input type="hidden" name="sub_location_id" id="sub_location_id" value="{{ old('sub_location_id', $inventory->sub_location_id) }}">

                  <div class="hier-picker" id="locationPickerEdit">
                    <button type="button" class="form-control hier-trigger @if($errors->has('location_id') || $errors->has('sub_location_id')) is-invalid @endif">
                      <span class="hier-trigger-text">Pilih lokasi -> sub lokasi</span>
                      <span class="hier-trigger-arrow">></span>
                    </button>

                    <div class="hier-menu">
                      <ul class="hier-level">
                        @foreach ($locations as $location)
                          @php
                            $subLocations = $location->subLocations;
                          @endphp
                          <li class="hier-item">
                            <button type="button" class="hier-action">
                              <span>{{ $location->name }}</span>
                              <span class="hier-arrow">></span>
                            </button>
                            <div class="hier-submenu">
                              <ul class="hier-level">
                                @forelse ($subLocations as $subLocation)
                                  <li class="hier-item">
                                    <button
                                      type="button"
                                      class="hier-action location-select-option"
                                      data-location-id="{{ $location->id }}"
                                      data-sub-location-id="{{ $subLocation->id }}"
                                      data-location-name="{{ $location->name }}"
                                      data-sub-location-name="{{ $subLocation->name }}"
                                    >
                                      {{ $subLocation->name }}
                                    </button>
                                  </li>
                                @empty
                                  <li class="hier-item">
                                    <span class="hier-empty">Belum ada sub lokasi.</span>
                                  </li>
                                @endforelse
                              </ul>
                            </div>
                          </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>

                  <small class="form-text text-muted">Pilih item dengan urutan lokasi lalu sub lokasi.</small>
                  @error('location_id')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                  @error('sub_location_id')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="kondisi" class="form-label">Kondisi</label>
                  <select name="kondisi" id="kondisi" class="form-control @error('kondisi') is-invalid @enderror">
                    <option value="">Pilih Kondisi</option>
                    <option value="Baik" {{ old('kondisi', $inventory->kondisi) === 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak Ringan" {{ old('kondisi', $inventory->kondisi) === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ old('kondisi', $inventory->kondisi) === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                  </select>
                  @error('kondisi')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group full-width">
                  <label for="deskripsi" class="form-label">Deskripsi</label>
                  <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Tulis deskripsi per poin (satu baris satu poin)">{{ old('deskripsi', $inventory->deskripsi) }}</textarea>
                  <small class="form-text text-muted">Isi deskripsi per baris agar tampil sebagai poin-poin pada tabel.</small>
                  @error('deskripsi')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group full-width">
                  <label for="supporting_file" class="form-label">File Pendukung</label>
                  <div class="custom-file">
                    <input
                      type="file"
                      name="supporting_file"
                      id="supporting_file"
                      data-max-size="2097152"
                      data-default-label="Pilih dokumen pendukung (opsional)"
                      class="custom-file-input @error('supporting_file') is-invalid @enderror"
                      accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                    >
                    <label class="custom-file-label" for="supporting_file">Pilih file pendukung (opsional)</label>
                  </div>
                  <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti file.</small>
                  @error('supporting_file')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror

                  @if ($inventory->photo_path)
                    @php
                      $existingFileExt = strtolower(pathinfo($inventory->photo_path, PATHINFO_EXTENSION));
                      $existingFileIsImage = in_array($existingFileExt, ['jpg', 'jpeg', 'png'], true);
                    @endphp
                    <div class="mt-3">
                      @if ($existingFileIsImage)
                        <img src="{{ asset('storage/' . $inventory->photo_path) }}" alt="Dokumen {{ $inventory->nama }}" class="item-photo-preview">
                      @else
                        <a href="{{ asset('storage/' . $inventory->photo_path) }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">
                          Lihat
                        </a>
                      @endif
                    </div>
                  @endif
                </div>
              </div>
            </div>

            <div class="card-footer">
              <div class="d-flex justify-content-end">
                <a href="{{ request()->getBaseUrl() }}/inventory" class="btn btn-sm btn-outline-secondary mr-2">Batal</a>
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
@endsection

@push('styles')
  <style>
    .hier-picker {
      position: relative;
    }

    .hier-trigger {
      display: flex;
      align-items: center;
      justify-content: space-between;
      text-align: left;
      cursor: pointer;
      background: #fff;
    }

    .hier-trigger-arrow,
    .hier-arrow {
      font-size: 12px;
      color: #6c757d;
      margin-left: 8px;
    }

    .hier-menu {
      display: none;
      position: absolute;
      top: calc(100% + 6px);
      left: 0;
      z-index: 30;
      min-width: 280px;
      background: #fff;
      border: 1px solid rgba(0, 0, 0, .12);
      border-radius: 10px;
      box-shadow: 0 10px 28px rgba(0, 0, 0, .12);
      padding: 8px;
      overflow: visible;
    }

    .hier-picker.is-open .hier-menu {
      display: block;
    }

    .hier-level {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .hier-item {
      position: relative;
    }

    .hier-action {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      border: 0;
      background: transparent;
      text-align: left;
      padding: 8px 10px;
      border-radius: 8px;
      color: #2f3541;
      font-size: .92rem;
    }

    .hier-action:hover {
      background: #f1f4f9;
    }

    .hier-submenu {
      display: none;
      position: absolute;
      top: 0;
      left: 100%;
      z-index: 31;
      min-width: 260px;
      background: #fff;
      border: 1px solid rgba(0, 0, 0, .12);
      border-radius: 10px;
      box-shadow: 0 10px 28px rgba(0, 0, 0, .12);
      padding: 8px;
      overflow: visible;
    }

    .hier-item:hover > .hier-submenu {
      display: block;
    }

    .hier-item.is-open > .hier-submenu {
      display: block;
    }

    .hier-empty {
      display: block;
      padding: 8px 10px;
      font-size: .84rem;
      color: #94a3b8;
    }
  </style>
@endpush

@push('scripts')
  <script src="{{ asset('templates/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var pickerRoot = document.getElementById('hierPickerEdit');
      var categoryInput = document.getElementById('category_id');
      var brandInput = document.getElementById('brand_id');
      if (pickerRoot && categoryInput && brandInput) {
        var trigger = pickerRoot.querySelector('.hier-trigger');
        var triggerText = pickerRoot.querySelector('.hier-trigger-text');
        var selectButtons = pickerRoot.querySelectorAll('.hier-select-option');
        var parentButtons = pickerRoot.querySelectorAll('.hier-item > .hier-action:not(.hier-select-option)');

        function updateTriggerTextFromSelection() {
          var selected = pickerRoot.querySelector(
            '.hier-select-option[data-category-id=\"' + categoryInput.value + '\"][data-brand-id=\"' + brandInput.value + '\"]'
          );

          if (!selected) {
            triggerText.textContent = 'Pilih kategori -> merk';
            return;
          }

          triggerText.textContent =
            selected.getAttribute('data-category-name') + ' -> ' +
            selected.getAttribute('data-brand-name');
        }

        trigger.addEventListener('click', function () {
          pickerRoot.classList.toggle('is-open');
        });

        parentButtons.forEach(function (button) {
          button.addEventListener('click', function (event) {
            event.stopPropagation();
            var currentItem = this.closest('.hier-item');
            var siblingItems = currentItem.parentElement
              ? Array.from(currentItem.parentElement.children).filter(function (child) {
                  return child.classList.contains('hier-item');
                })
              : [];

            siblingItems.forEach(function (item) {
              if (item !== currentItem) {
                item.classList.remove('is-open');
              }
            });

            currentItem.classList.toggle('is-open');
          });
        });

        selectButtons.forEach(function (button) {
          button.addEventListener('click', function () {
            categoryInput.value = this.getAttribute('data-category-id');
            brandInput.value = this.getAttribute('data-brand-id');
            updateTriggerTextFromSelection();
            pickerRoot.classList.remove('is-open');
            pickerRoot.querySelectorAll('.hier-item.is-open').forEach(function (item) {
              item.classList.remove('is-open');
            });
          });
        });

        document.addEventListener('click', function (event) {
          if (!pickerRoot.contains(event.target)) {
            pickerRoot.classList.remove('is-open');
            pickerRoot.querySelectorAll('.hier-item.is-open').forEach(function (item) {
              item.classList.remove('is-open');
            });
          }
        });

        updateTriggerTextFromSelection();
      }

      var locationPickerRoot = document.getElementById('locationPickerEdit');
      var locationInput = document.getElementById('location_id');
      var subLocationInput = document.getElementById('sub_location_id');
      if (locationPickerRoot && locationInput && subLocationInput) {
        var locationTrigger = locationPickerRoot.querySelector('.hier-trigger');
        var locationTriggerText = locationPickerRoot.querySelector('.hier-trigger-text');
        var locationSelectButtons = locationPickerRoot.querySelectorAll('.location-select-option');
        var locationParentButtons = locationPickerRoot.querySelectorAll('.hier-item > .hier-action:not(.location-select-option)');

        function updateLocationTriggerText() {
          var selected = locationPickerRoot.querySelector(
            '.location-select-option[data-location-id=\"' + locationInput.value + '\"][data-sub-location-id=\"' + subLocationInput.value + '\"]'
          );

          if (!selected) {
            locationTriggerText.textContent = 'Pilih lokasi -> sub lokasi';
            return;
          }

          locationTriggerText.textContent =
            selected.getAttribute('data-location-name') + ' -> ' +
            selected.getAttribute('data-sub-location-name');
        }

        locationTrigger.addEventListener('click', function () {
          locationPickerRoot.classList.toggle('is-open');
        });

        locationParentButtons.forEach(function (button) {
          button.addEventListener('click', function (event) {
            event.stopPropagation();
            var currentItem = this.closest('.hier-item');
            var siblingItems = currentItem.parentElement
              ? Array.from(currentItem.parentElement.children).filter(function (child) {
                  return child.classList.contains('hier-item');
                })
              : [];

            siblingItems.forEach(function (item) {
              if (item !== currentItem) {
                item.classList.remove('is-open');
              }
            });

            currentItem.classList.toggle('is-open');
          });
        });

        locationSelectButtons.forEach(function (button) {
          button.addEventListener('click', function () {
            locationInput.value = this.getAttribute('data-location-id');
            subLocationInput.value = this.getAttribute('data-sub-location-id');
            updateLocationTriggerText();
            locationPickerRoot.classList.remove('is-open');
            locationPickerRoot.querySelectorAll('.hier-item.is-open').forEach(function (item) {
              item.classList.remove('is-open');
            });
          });
        });

        document.addEventListener('click', function (event) {
          if (!locationPickerRoot.contains(event.target)) {
            locationPickerRoot.classList.remove('is-open');
            locationPickerRoot.querySelectorAll('.hier-item.is-open').forEach(function (item) {
              item.classList.remove('is-open');
            });
          }
        });

        updateLocationTriggerText();
      }

      if (typeof bsCustomFileInput !== 'undefined') {
        bsCustomFileInput.init();
      }

      var supportingFileInput = document.getElementById('supporting_file');
      if (supportingFileInput) {
        supportingFileInput.addEventListener('change', function () {
          var file = this.files && this.files[0];
          if (!file) {
            return;
          }

          var maxSize = Number(this.getAttribute('data-max-size') || 2097152);
          if (file.size > maxSize) {
            this.value = '';

            var wrapper = this.parentElement;
            var label = wrapper ? wrapper.querySelector('.custom-file-label') : null;
            if (label) {
              label.textContent = this.getAttribute('data-default-label') || 'Pilih dokumen pendukung (opsional)';
            }

            Swal.fire({
              title: 'Ukuran Dokumen Terlalu Besar',
              text: 'Maksimal ukuran dokumen adalah 2MB.',
              icon: 'warning',
            });
          }
        });
      }
    });
  </script>
@endpush
