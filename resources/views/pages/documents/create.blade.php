@extends('layouts.main')

@section('header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Tambah Dokumen</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ request()->getBaseUrl() }}/dashboard-admin">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ request()->getBaseUrl() }}/documents">Dokumen</a></li>
        <li class="breadcrumb-item active">Tambah</li>
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
      <form action="{{ request()->getBaseUrl() }}/documents/store" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card crud-form-card">
          <div class="card-header">
            <h3>Form Dokumen</h3>
            <p>Isi data dokumen dengan lengkap dan jelas.</p>
          </div>
          <div class="card-body">
            <div class="crud-form-grid">
              <div class="form-group full-width">
                <label for="nama_dokumen" class="form-label">Nama Dokumen</label>
                <input type="text" name="nama_dokumen" id="nama_dokumen" class="form-control @error('nama_dokumen') is-invalid @enderror" value="{{ old('nama_dokumen') }}" placeholder="Contoh: SOP Pengelolaan Inventaris">
                @error('nama_dokumen')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="kategori" class="form-label">Kategori</label>
                <input type="text" name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror" value="{{ old('kategori') }}" placeholder="Contoh: SOP">
                @error('kategori')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="tahun" class="form-label">Tahun</label>
                <input type="number" name="tahun" id="tahun" class="form-control @error('tahun') is-invalid @enderror" value="{{ old('tahun') }}" min="1900" max="{{ date('Y') + 1 }}" placeholder="Contoh: {{ date('Y') }}">
                @error('tahun')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group full-width">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Deskripsi singkat dokumen...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group full-width">
                <label for="file_pendukung" class="form-label">File Pendukung</label>
                <div class="custom-file">
                  <input
                    type="file"
                    name="file_pendukung"
                    id="file_pendukung"
                    data-max-size="2097152"
                    data-default-label="Pilih file pendukung"
                    class="custom-file-input @error('file_pendukung') is-invalid @enderror"
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                  >
                  <label class="custom-file-label" for="file_pendukung">Pilih file pendukung</label>
                </div>
                <small class="form-text text-muted">Maks 2MB (pdf, doc, docx, jpg, jpeg, png).</small>
                @error('file_pendukung')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="d-flex justify-content-end">
              <a href="{{ request()->getBaseUrl() }}/documents" class="btn btn-sm btn-outline-secondary mr-2">Batal</a>
              <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('templates/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (typeof bsCustomFileInput !== 'undefined') {
        bsCustomFileInput.init();
      }

      var supportingFileInput = document.getElementById('file_pendukung');
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
              label.textContent = this.getAttribute('data-default-label') || 'Pilih file pendukung';
            }

            Swal.fire({
              title: 'Ukuran File Terlalu Besar',
              text: 'Maksimal ukuran file adalah 2MB.',
              icon: 'warning',
            });
          }
        });
      }
    });
  </script>
@endpush
