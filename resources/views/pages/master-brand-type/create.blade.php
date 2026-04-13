@extends('layouts.main')

@section('header')
    <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Tambah Merk</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Beranda</a></li>
              <li class="breadcrumb-item active">Master Merk</li>
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
        <form action="{{ request()->getBaseUrl() }}/merek" method="POST">
          @csrf
          <div class="card crud-form-card">
            <div class="card-header">
              <h3>Form Merk</h3>
              <p>Isi nama merk yang jelas agar data lebih mudah dicari.</p>
            </div>
            <div class="card-body">
              <div class="crud-form-grid">
                <div class="form-group full-width">
                  <label for="category_id" class="form-label">Kategori</label>
                  <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                    <option value="">Pilih Kategori</option>
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

                <div class="form-group full-width">
                  <label for="brand_name" class="form-label">Nama Merk</label>
                  <input type="text" name="brand_name" id="brand_name" autocomplete="off" class="form-control @error('brand_name') is-invalid @enderror" value="{{ old('brand_name') }}" placeholder="Contoh: Bantex">
                  @error('brand_name')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>
            <div class="card-footer">
              <div class="d-flex justify-content-end">
                <a href="{{ request()->getBaseUrl() }}/merek" class="btn btn-sm btn-outline-secondary mr-2">Batal</a>
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
@endsection
