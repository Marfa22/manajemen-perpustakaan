@extends('layouts.main')

@section('header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Preview Dokumen</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ request()->getBaseUrl() }}/dashboard-admin">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ request()->getBaseUrl() }}/documents">Dokumen</a></li>
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
            <h3 class="mb-1">{{ $document->nama_dokumen }}</h3>
            <p class="mb-0 text-muted">
              {{ $document->kategori }} | {{ $document->tahun }} | {{ strtoupper($fileExtension ?: 'FILE') }}
            </p>
          </div>
          <div class="d-flex flex-wrap mt-2 mt-md-0">
            <a href="{{ $downloadUrl }}" class="btn btn-sm btn-primary mr-2">Download File</a>
            <a href="{{ request()->getBaseUrl() }}/documents" class="btn btn-sm btn-outline-secondary">Kembali</a>
          </div>
        </div>

        <div class="card-body">
          @if (!empty($document->deskripsi))
            <p class="text-muted mb-4">{{ $document->deskripsi }}</p>
          @endif

          @if ($canPreviewInline)
            @if (str_starts_with($mimeType, 'image/'))
              <div class="document-preview-frame image-frame">
                <img src="{{ $previewUrl }}" alt="Dokumen {{ $document->nama_dokumen }}" class="document-preview-image">
              </div>
            @else
              <div class="document-preview-frame">
                <iframe src="{{ $previewUrl }}" title="Preview {{ $document->nama_dokumen }}" class="document-preview-iframe"></iframe>
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
    .document-preview-frame {
      width: 100%;
      min-height: 75vh;
      border: 1px solid #dbe3f0;
      border-radius: 12px;
      overflow: hidden;
      background: #f8fafc;
    }

    .document-preview-iframe {
      width: 100%;
      min-height: 75vh;
      border: 0;
      background: #fff;
    }

    .document-preview-frame.image-frame {
      min-height: auto;
      padding: 16px;
      text-align: center;
    }

    .document-preview-image {
      max-width: 100%;
      height: auto;
      border-radius: 10px;
      box-shadow: 0 12px 28px rgba(15, 23, 42, 0.12);
    }
  </style>
@endpush
