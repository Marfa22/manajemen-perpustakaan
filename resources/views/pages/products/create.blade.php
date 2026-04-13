@extends('layouts.main')

@section('header')
    <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tambah Produk/Barang</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Beranda</a></li>
              <li class="breadcrumb-item active">Produk/Barang</li>
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
        <form action="{{ request()->getBaseUrl() }}/products/store" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card crud-form-card">
            <div class="card-header">
              <h3>Form Barang Temuan</h3>
              <p>Masukkan detail barang dengan informasi yang lengkap.</p>
            </div>
            <div class="card-body">
              <div class="crud-form-grid">
                <div class="form-group">
                  <label for="name" class="form-label">Nama Produk</label>
                  <input type="text" name="name" id="name" autocomplete="off" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Dompet Kulit">
                  @error('name')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="sku" class="form-label">Kode Produk</label>
                  <input type="text" name="sku" id="sku" autocomplete="off" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}" placeholder="Contoh: BRG-001">
                  @error('sku')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="found_location" class="form-label">Lokasi Ditemukan</label>
                  <input type="text" name="found_location" id="found_location" autocomplete="off" class="form-control @error('found_location') is-invalid @enderror" value="{{ old('found_location') }}" placeholder="Contoh: Ruang Tunggu Lt.1">
                  @error('found_location')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="found_at" class="form-label">Tanggal Ditemukan</label>
                  <input type="date" name="found_at" id="found_at" class="form-control @error('found_at') is-invalid @enderror" value="{{ old('found_at') }}">
                  @error('found_at')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="category_id" class="form-label">Kategori</label>
                  <select name="category_id" id="category_id" class="form-control js-category-select @error('category_id') is-invalid @enderror">
                    <option value=""></option>
                    @foreach ($categories as $category)
                      <option value="{{ $category->id }}" {{ (string) old('category_id') === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                      </option>
                    @endforeach
                  </select>
                  @error('category_id')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group">
                  <label class="form-label">Status Pengambilan</label>
                  <input type="text" class="form-control" value="Belum Diambil (otomatis)" readonly>
                  <small class="form-text text-muted">Status berubah ke "Sudah Diambil" melalui proses pengembalian di halaman daftar barang.</small>
                </div>

                <div class="form-group full-width">
                  <label for="description" class="form-label">Deskripsi</label>
                  <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="Tulis deskripsi barang">{{ old('description') }}</textarea>
                  @error('description')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group full-width">
                  <label for="photo" class="form-label">Foto Barang</label>
                  <div class="custom-file">
                    <input type="file" name="photo" id="photo" data-max-size="2097152" data-default-label="Pilih foto barang" class="custom-file-input @error('photo') is-invalid @enderror" accept="image/*">
                    <label class="custom-file-label" for="photo">Pilih foto barang</label>
                  </div>
                  <small class="form-text text-muted">Maks 2MB (jpg, jpeg, png, webp).</small>
                  @error('photo')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>
            <div class="card-footer">
              <div class="d-flex justify-content-end">
                <a href="{{ request()->getBaseUrl() }}/products" class="btn btn-sm btn-outline-secondary mr-2">Batal</a>
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('templates/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('templates/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('scripts')
  <script src="{{ asset('templates/plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('templates/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var $category = $('.js-category-select');
      if (!$category.length || typeof $category.select2 !== 'function') {
        if (typeof bsCustomFileInput !== 'undefined') {
          bsCustomFileInput.init();
        }
        return;
      }

      $category.select2({
        theme: 'bootstrap4',
        width: '100%',
        allowClear: true,
        placeholder: 'Pilih kategori'
      });

      if (typeof bsCustomFileInput !== 'undefined') {
        bsCustomFileInput.init();
      }

      var photoInput = document.getElementById('photo');
      if (photoInput) {
        photoInput.addEventListener('change', function () {
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
              label.textContent = this.getAttribute('data-default-label') || 'Pilih foto barang';
            }

            Swal.fire({
              title: 'Ukuran Foto Terlalu Besar',
              text: 'Maksimal ukuran foto adalah 2MB.',
              icon: 'warning',
            });
          }
        });
      }
    });
  </script>
@endpush
