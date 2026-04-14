@extends('layouts.public')

@push('styles')
  <style>
    .detail-shell {
      max-width: 1180px;
      margin: 0 auto;
      padding: 1.2rem 1rem 2rem;
      display: grid;
      gap: 1.2rem;
    }

    .detail-hero {
      background: linear-gradient(135deg, #0f6bff 0%, #1d4ed8 100%);
      border-radius: 16px;
      color: #fff;
      padding: 1rem 1.05rem;
      display: grid;
      gap: 0.5rem;
    }

    .detail-back {
      display: inline-flex;
      align-items: center;
      gap: 0.42rem;
      color: rgba(255, 255, 255, 0.92);
      text-decoration: none;
      font-size: 0.9rem;
      width: fit-content;
    }

    .detail-back:hover {
      color: #fff;
      text-decoration: underline;
    }

    .detail-hero h1 {
      margin: 0;
      font-size: 1.6rem;
      font-weight: 800;
      letter-spacing: -0.02em;
    }

    .detail-hero p {
      margin: 0;
      color: rgba(255, 255, 255, 0.9);
      font-size: 0.95rem;
    }

    .detail-grid {
      display: grid;
      grid-template-columns: minmax(0, 0.95fr) minmax(0, 1.05fr);
      gap: 1rem;
    }

    .detail-photo-wrap,
    .detail-info {
      background: #fff;
      border: 1px solid #dbe4f3;
      border-radius: 14px;
      box-shadow: 0 8px 24px rgba(15, 23, 42, 0.07);
      overflow: hidden;
    }

    .detail-photo {
      width: 100%;
      height: 350px;
      object-fit: contain;
      background: #f8fafc;
      display: block;
    }

    .detail-photo-fallback {
      width: 100%;
      height: 350px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(140deg, #dbeafe, #bfdbfe);
      color: #1d4ed8;
      font-size: 2.25rem;
    }

    .detail-info {
      padding: 1rem;
      display: grid;
      gap: 0.9rem;
    }

    .detail-title-wrap {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 0.6rem;
      flex-wrap: wrap;
    }

    .detail-title {
      margin: 0;
      font-size: 1.35rem;
      color: #0f172a;
      font-weight: 800;
      letter-spacing: -0.01em;
    }

    .detail-badge {
      border-radius: 999px;
      padding: 0.24rem 0.58rem;
      font-size: 0.76rem;
      font-weight: 700;
      white-space: nowrap;
    }

    .detail-badge.pending {
      background: #ecfdf3;
      color: #047857;
    }

    .detail-badge.picked {
      background: #e2e8f0;
      color: #334155;
    }

    .detail-desc {
      margin: 0;
      color: #475569;
      font-size: 0.95rem;
      line-height: 1.6;
    }

    .detail-meta {
      display: grid;
      gap: 0.5rem;
    }

    .detail-meta-item {
      display: grid;
      grid-template-columns: 20px 132px 1fr;
      align-items: start;
      gap: 0.5rem;
      font-size: 0.9rem;
    }

    .detail-meta-item i {
      margin-top: 0.12rem;
      color: #2563eb;
      text-align: center;
    }

    .detail-meta-item .label {
      color: #64748b;
      font-weight: 600;
    }

    .detail-meta-item .value {
      color: #0f172a;
      font-weight: 600;
      word-break: break-word;
    }

    .detail-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.55rem;
      padding-top: 0.35rem;
      border-top: 1px solid #e2e8f0;
    }

    .detail-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.36rem;
      border-radius: 10px;
      padding: 0.5rem 0.8rem;
      font-size: 0.86rem;
      font-weight: 700;
      text-decoration: none;
      border: 1px solid transparent;
    }

    .detail-btn.primary {
      background: #1d4ed8;
      color: #fff;
    }

    .detail-btn.primary:hover {
      color: #fff;
      background: #1e40af;
      text-decoration: none;
    }

    .detail-btn.outline {
      border-color: #cbd5e1;
      color: #334155;
      background: #fff;
    }

    .detail-btn.outline:hover {
      color: #0f172a;
      border-color: #94a3b8;
      text-decoration: none;
    }

    .related-box {
      background: transparent;
      border-top: 1px solid #dbe7f5;
      padding-top: 1.1rem;
    }

    .related-head {
      margin-bottom: 0.85rem;
    }

    .related-head h2 {
      margin: 0;
      font-size: 1.2rem;
      color: #0f172a;
      font-weight: 800;
    }

    .related-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 240px));
      gap: 0.9rem;
      justify-content: center;
    }

    .related-card {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 6px 16px rgba(15, 23, 42, 0.07);
      display: grid;
      grid-template-rows: auto 1fr;
    }

    .related-media {
      width: 100%;
      height: 118px;
      object-fit: contain;
      background: #f8fafc;
    }

    .related-fallback {
      width: 100%;
      height: 118px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(140deg, #dbeafe, #bfdbfe);
      color: #1d4ed8;
    }

    .related-body {
      padding: 0.75rem;
      display: grid;
      gap: 0.35rem;
    }

    .related-title {
      margin: 0;
      font-size: 0.95rem;
      font-weight: 700;
      color: #0f172a;
    }

    .related-meta {
      margin: 0;
      color: #64748b;
      font-size: 0.8rem;
    }

    @media (max-width: 991.98px) {
      .detail-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 575.98px) {
      .detail-shell {
        padding-left: 0.8rem;
        padding-right: 0.8rem;
      }

      .detail-photo,
      .detail-photo-fallback {
        height: 270px;
      }

      .detail-meta-item {
        grid-template-columns: 18px 110px 1fr;
      }
    }
  </style>
@endpush

@section('content')
  @php
    $isPicked = $item->pickup_status === 'sudah_diambil';
  @endphp

  <div class="detail-shell">
    <section class="detail-hero">
      <a href="{{ route('public.dashboard') }}" class="detail-back">
        <i class="fas fa-arrow-left"></i>Kembali ke Halaman Publik SIMARFA
      </a>
      <h1>Detail Barang Temuan</h1>
      <p>Informasi lengkap barang temuan untuk membantu proses verifikasi kepemilikan.</p>
    </section>

    <section class="detail-grid">
      <article class="detail-photo-wrap">
        @if ($item->photo_path)
          <img src="{{ route('public.dashboard.photo', $item->id) }}" alt="Foto {{ $item->name }}" class="detail-photo">
        @else
          <div class="detail-photo-fallback">
            <i class="fas fa-box-open"></i>
          </div>
        @endif
      </article>

      <article class="detail-info">
        <div class="detail-title-wrap">
          <h2 class="detail-title">{{ $item->name }}</h2>
          <span class="detail-badge {{ $isPicked ? 'picked' : 'pending' }}">{{ $isPicked ? 'Sudah Diambil' : 'Belum Diambil' }}</span>
        </div>

        <p class="detail-desc">{{ $item->description ?: 'Tidak ada deskripsi tambahan untuk barang ini.' }}</p>

        <div class="detail-meta">
          <div class="detail-meta-item">
            <i class="fas fa-tags"></i>
            <span class="label">Kategori</span>
            <span class="value">{{ $item->category?->name ?: 'Tanpa Kategori' }}</span>
          </div>
          <div class="detail-meta-item">
            <i class="fas fa-map-marker-alt"></i>
            <span class="label">Lokasi Ditemukan</span>
            <span class="value">{{ $item->found_location ?: '-' }}</span>
          </div>
          <div class="detail-meta-item">
            <i class="far fa-calendar-alt"></i>
            <span class="label">Tanggal Ditemukan</span>
            <span class="value">{{ $item->found_at ? \Carbon\Carbon::parse($item->found_at)->format('d M Y') : '-' }}</span>
          </div>
        </div>

        <div class="detail-actions">
          <a href="{{ route('public.dashboard') }}" class="detail-btn outline">Kembali ke Daftar</a>
        </div>
      </article>
    </section>

    @if ($relatedItems->isNotEmpty())
      <section class="related-box">
        <div class="related-head">
          <h2>Barang Terkait</h2>
        </div>

        <div class="related-grid">
          @foreach ($relatedItems as $related)
            <article class="related-card">
              @if ($related->photo_path)
                <img src="{{ route('public.dashboard.photo', $related->id) }}" alt="Foto {{ $related->name }}" class="related-media">
              @else
                <div class="related-fallback"><i class="fas fa-box-open"></i></div>
              @endif

              <div class="related-body">
                <h3 class="related-title">{{ $related->name }}</h3>
                <p class="related-meta">{{ $related->found_at ? \Carbon\Carbon::parse($related->found_at)->format('d M Y') : '-' }} � {{ $related->found_location ?: '-' }}</p>
                <a href="{{ route('public.dashboard.show', $related->id) }}" class="detail-btn outline" style="width: fit-content;">Lihat Detail</a>
              </div>
            </article>
          @endforeach
        </div>
      </section>
    @endif
  </div>
@endsection
