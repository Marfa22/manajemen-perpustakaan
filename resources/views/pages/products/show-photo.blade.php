@extends('layouts.main')

@section('header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Lihat Foto Barang</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ request()->getBaseUrl() }}/dashboard-admin">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ request()->getBaseUrl() }}/products">Barang Temuan</a></li>
        <li class="breadcrumb-item active">Foto</li>
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
            <h3 class="mb-1">{{ $product->name }}</h3>
            <p class="mb-0 text-muted">
              {{ $product->sku ?: '-' }} | {{ $product->category?->name ?: '-' }}
            </p>
          </div>
          <div class="d-flex flex-wrap mt-2 mt-md-0">
            <a href="{{ request()->getBaseUrl() }}/products" class="btn btn-sm btn-outline-secondary">Kembali</a>
          </div>
        </div>

        <div class="card-body">
          <div class="product-photo-frame">
            <img src="{{ $previewUrl }}" alt="Foto {{ $product->name }}" class="product-photo-image">
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    .product-photo-frame {
      width: 100%;
      border: 1px solid #dbe3f0;
      border-radius: 12px;
      overflow: hidden;
      background: #f8fafc;
      text-align: center;
      padding: 16px;
    }

    .product-photo-image {
      max-width: 100%;
      max-height: 75vh;
      height: auto;
      border-radius: 10px;
      box-shadow: 0 12px 28px rgba(15, 23, 42, 0.12);
    }
  </style>
@endpush
