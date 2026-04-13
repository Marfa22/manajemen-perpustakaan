@extends('layouts.main')

@section('header')
    <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tambah Lokasi</h1>
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
        <form action="{{ request()->getBaseUrl() }}/locations/store" method="POST">
          @csrf
          <div class="card crud-form-card">
            <div class="card-header">
              <h3>Form Lokasi</h3>
              <p>Isi nama lokasi yang jelas agar data inventaris mudah dipetakan.</p>
            </div>
            <div class="card-body">
              <div class="crud-form-grid">
                <div class="form-group full-width">
                <label for="name" class="form-label">Nama Lokasi</label>
                <input type="text" name="name" id="name" autocomplete="off" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Gudang A">
                @error('name')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
                </div>
              </div>
            </div>
            <div class="card-footer">
              <div class="d-flex justify-content-end">
                <a href="{{ request()->getBaseUrl() }}/locations" class="btn btn-sm btn-outline-secondary mr-2">Batal</a>
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
@endsection
