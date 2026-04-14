@extends('layouts.main')

@section('header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Preview File Pendukung</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ request()->getBaseUrl() }}/dashboard-admin">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ request()->getBaseUrl() }}/inventory">Inventaris</a></li>
        <li class="breadcrumb-item active">Preview</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card crud-form-card">
        <div class="card-header d-flex justify-content-between align-items-start flex-wrap">
          <div>
            <h3 class="mb-1">{{ $inventory->nama }}</h3>
            <p class="mb-0 text-muted">
              {{ $inventory->kode_barang ?: '-' }} | {{ strtoupper($fileExtension ?: 'FILE') }}
            </p>
          </div>
          <div class="d-flex flex-wrap mt-2 mt-md-0">
            <a href="{{ $downloadUrl }}" class="btn btn-sm btn-primary mr-2">Download File</a>
            <a href="{{ request()->getBaseUrl() }}/inventory" class="btn btn-sm btn-outline-secondary">Kembali</a>
          </div>
        </div>

        <div class="card-body">
          @if (!empty($inventory->deskripsi))
            <p class="text-muted mb-4">{{ $inventory->deskripsi }}</p>
          @endif

          @if ($canPreviewInline)
            @if (str_starts_with($mimeType, 'image/'))
              <div class="inventory-file-frame image-frame">
                <img src="{{ $previewUrl }}" alt="File {{ $inventory->nama }}" class="inventory-file-image">
              </div>
            @else
              <div class="inventory-file-frame">
                <iframe src="{{ $previewUrl }}" title="Preview {{ $inventory->nama }}" class="inventory-file-iframe"></iframe>
              </div>
            @endif
          @else
            <div class="alert alert-info mb-0">
              File dengan format <strong>{{ strtoupper($fileExtension ?: 'unknown') }}</strong> tidak bisa dipreview langsung di browser ini.
              Silakan gunakan tombol <strong>Download File</strong> untuk membuka atau menyimpannya.
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .inventory-file-frame {
      width: 100%;
      min-height: 75vh;
      border: 1px solid #dbe3f0;
      border-radius: 12px;
      overflow: hidden;
      background: #f8fafc;
    }

    .inventory-file-iframe {
      width: 100%;
      min-height: 75vh;
      border: 0;
      background: #fff;
    }

    .inventory-file-frame.image-frame {
      min-height: auto;
      padding: 16px;
      text-align: center;
    }

    .inventory-file-image {
      max-width: 100%;
      height: auto;
      border-radius: 10px;
      box-shadow: 0 12px 28px rgba(15, 23, 42, 0.12);
    }
  </style>
@endpush
