@extends('layouts.public')

@push('styles')
  <style>
    .public-shell {
      display: grid;
      gap: 0.2rem;
      font-size: 1.03rem;
    }

    .lf-hero {
      border-radius: 0 0 24px 24px;
      padding: clamp(1.6rem, 3.2vw, 2.55rem) clamp(1rem, 2.2vw, 1.8rem);
      background:
        radial-gradient(145% 120% at 100% 100%, rgba(0, 166, 166, 0.24) 0%, rgba(0, 166, 166, 0) 62%),
        radial-gradient(120% 165% at 0% 0%, rgba(96, 165, 250, 0.24) 0%, rgba(96, 165, 250, 0) 56%),
        linear-gradient(120deg, #1e3a8a 0%, #0f6bff 52%, #00a6a6 100%);
      color: #fff;
      border: 1px solid rgba(147, 197, 253, 0.36);
      box-shadow: 0 14px 28px rgba(15, 23, 42, 0.24);
      position: relative;
      overflow: hidden;
      margin-bottom: 0.95rem;
    }

    .lf-hero > * {
      max-width: 1180px;
      margin-left: auto;
      margin-right: auto;
    }

    .lf-hero {
      text-align: center;
    }

    .lf-hero::before {
      content: "";
      position: absolute;
      left: -80px;
      bottom: -90px;
      width: 260px;
      height: 260px;
      border-radius: 999px;
      background: radial-gradient(circle at center, rgba(191, 219, 254, 0.34), rgba(191, 219, 254, 0));
      pointer-events: none;
    }

    .lf-hero::after {
      content: "";
      position: absolute;
      right: -58px;
      top: -58px;
      width: 200px;
      height: 200px;
      border-radius: 999px;
      background: radial-gradient(circle at center, rgba(255, 255, 255, 0.34), rgba(255, 255, 255, 0));
      pointer-events: none;
    }

    .lf-hero h1 {
      font-weight: 800;
      margin-bottom: 0.45rem;
      line-height: 1.15;
      font-size: clamp(1.5rem, 3vw, 2.2rem);
      color: #f8fbff;
      text-shadow: 0 2px 10px rgba(15, 23, 42, 0.2);
    }

    .lf-hero p {
      margin: 0 auto 0.55rem;
      max-width: 860px;
      font-size: 0.98rem;
      color: rgba(255, 255, 255, 0.92);
    }

    .lf-hero .hero-subtitle {
      max-width: 780px;
      line-height: 1.55;
      color: rgba(239, 248, 255, 0.96);
      background: rgba(15, 23, 42, 0.14);
      border: 1px solid rgba(191, 219, 254, 0.34);
      border-radius: 12px;
      padding: 0.55rem 0.82rem;
    }

    .section-head {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      gap: 12px;
      flex-wrap: wrap;
      margin-bottom: 1.25rem;
    }

    .section-head.centered {
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      gap: 0.78rem;
    }

    .section-head h2 {
      margin: 0;
      font-weight: 800;
      color: #0f172a;
      font-size: 1.46rem;
      letter-spacing: -0.02em;
      text-align: center;
    }

    .section-head p {
      margin: 0.18rem 0 0;
      color: #64748b;
      font-size: 0.98rem;
      text-align: center;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 0.95rem;
    }

    .stat-card {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 1rem;
      box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
      position: relative;
      overflow: hidden;
    }

    .stat-card::before {
      content: "";
      position: absolute;
      left: 0;
      top: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, #1d4ed8, #3b82f6);
    }

    .stat-card .stat-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 0.55rem;
      color: #fff;
      background: linear-gradient(135deg, #0f6bff, #2563eb);
    }

    .stat-card .stat-label {
      color: #64748b;
      font-size: 0.9rem;
      margin: 0;
    }

    .stat-card .stat-value {
      margin: 0.2rem 0 0;
      color: #0f172a;
      font-size: 1.8rem;
      line-height: 1;
      font-weight: 800;
      font-variant-numeric: tabular-nums;
    }

    .cards-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 240px));
      gap: 0.95rem;
      justify-content: center;
    }

    .latest-carousel-wrap {
      border: 1px solid #dbe4f3;
      border-radius: 16px;
      overflow: hidden;
      background: #0b1220;
      box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
    }

    .hero-inline-title {
      margin: 0.8rem 0 0.55rem;
      font-size: clamp(1.2rem, 2.2vw, 1.45rem);
      font-weight: 800;
      color: #f8fbff;
      letter-spacing: -0.015em;
    }

    .hero-carousel-wrap {
      margin: 0.5rem auto 0;
      max-width: 1160px;
      border-color: rgba(166, 191, 223, 0.5);
      box-shadow: 0 18px 36px rgba(15, 23, 42, 0.3);
    }

    .lf-hero > .hero-carousel-wrap {
      max-width: min(1160px, calc(100vw - 2rem));
    }

    .hero-empty-state {
      max-width: 760px;
      margin: 0.75rem auto 0;
      border: 1px dashed rgba(191, 219, 254, 0.55);
      border-radius: 14px;
      background: rgba(15, 23, 42, 0.22);
      padding: 1.1rem 1rem;
      color: rgba(234, 244, 255, 0.96);
      text-align: center;
    }

    .latest-carousel-wrap .carousel-item {
      position: relative;
      height: clamp(260px, 42vw, 430px);
    }

    .latest-slide-media {
      width: 100%;
      height: 100%;
      object-fit: contain;
      background: #f8fafc;
    }

    .latest-slide-fallback {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.2rem;
      color: #1d4ed8;
      background: linear-gradient(140deg, #dbeafe, #bfdbfe);
    }

    .hero-carousel-wrap .carousel-item {
      height: clamp(350px, 44vw, 580px);
    }

    .hero-carousel-wrap .latest-slide-media {
      object-fit: contain;
      object-position: center;
      background: #eef4ff;
      padding: 0.35rem;
    }

    .hero-carousel-wrap .latest-slide-fallback {
      font-size: 2.9rem;
    }

    .hero-carousel-wrap .latest-slide-body {
      padding: 1.15rem 1.25rem;
    }

    .latest-slide-body {
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      padding: 0.95rem 1rem;
      background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0.88) 68%);
      color: #fff;
    }

    .latest-slide-title {
      margin: 0;
      font-size: 1.08rem;
      font-weight: 700;
      line-height: 1.3;
      letter-spacing: -0.01em;
    }

    .latest-slide-desc {
      margin: 0.2rem 0 0;
      font-size: 0.86rem;
      color: rgba(255, 255, 255, 0.88);
      max-width: 840px;
    }

    .latest-slide-meta {
      margin-top: 0.45rem;
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      font-size: 0.8rem;
      color: rgba(255, 255, 255, 0.92);
    }

    .latest-slide-meta i {
      margin-right: 0.28rem;
      color: #93c5fd;
    }

    .latest-slide-tags {
      margin-top: 0.45rem;
      display: flex;
      gap: 0.45rem;
      flex-wrap: wrap;
    }

    .latest-pill {
      display: inline-flex;
      align-items: center;
      border-radius: 999px;
      padding: 0.2rem 0.58rem;
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.01em;
      background: rgba(191, 219, 254, 0.22);
      color: #dbeafe;
      border: 1px solid rgba(191, 219, 254, 0.35);
    }

    .latest-pill.status {
      background: rgba(16, 185, 129, 0.24);
      border-color: rgba(110, 231, 183, 0.4);
      color: #d1fae5;
    }

    .latest-pill.status.picked {
      background: rgba(148, 163, 184, 0.28);
      border-color: rgba(203, 213, 225, 0.45);
      color: #e2e8f0;
    }

    .latest-pill.link {
      text-decoration: none;
      background: rgba(37, 99, 235, 0.3);
      border-color: rgba(147, 197, 253, 0.45);
      color: #fff;
    }

    .latest-pill.link:hover {
      color: #fff;
      text-decoration: none;
      background: rgba(37, 99, 235, 0.44);
    }

    .latest-carousel-wrap .carousel-indicators {
      margin-bottom: 0.45rem;
    }

    .latest-carousel-wrap .carousel-indicators li {
      width: 8px;
      height: 8px;
      border-radius: 999px;
      border: 0;
      background: rgba(255, 255, 255, 0.45);
      margin-right: 4px;
      margin-left: 4px;
    }

    .latest-carousel-wrap .carousel-indicators .active {
      background: #fff;
      width: 24px;
    }

    .latest-carousel-wrap .carousel-control-prev,
    .latest-carousel-wrap .carousel-control-next {
      width: 8%;
    }

    .latest-carousel-wrap .carousel-control-prev-icon,
    .latest-carousel-wrap .carousel-control-next-icon {
      filter: drop-shadow(0 2px 6px rgba(15, 23, 42, 0.45));
    }

    .found-card {
      border: 1px solid #e4eaf3;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
      background: #fff;
      height: 100%;
      max-width: 240px;
      width: 100%;
      transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }

    .found-card:hover {
      transform: translateY(-3px);
      border-color: #cfe0ff;
      box-shadow: 0 10px 26px rgba(15, 23, 42, 0.1);
    }

    .found-media {
      width: 100%;
      height: 122px;
      object-fit: contain;
      object-position: center;
      background: #f8fafc;
    }

    .found-fallback {
      width: 100%;
      height: 122px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.35rem;
      color: #1d4ed8;
      background: linear-gradient(140deg, #dbeafe, #bfdbfe);
    }

    .found-card .card-body {
      padding: 0.85rem 0.85rem 0.9rem;
      display: grid;
      gap: 0.4rem;
    }

    .found-title {
      margin: 0;
      color: #0f172a;
      font-weight: 700;
      font-size: 1rem;
      line-height: 1.35;
    }

    .found-desc {
      margin: 0;
      color: #64748b;
      font-size: 0.9rem;
      line-height: 1.45;
      min-height: 2.5em;
    }

    .meta-line {
      font-size: 0.84rem;
      color: #475569;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.4rem;
    }

    .meta-line i {
      color: #2563eb;
      width: 14px;
      text-align: center;
    }

    .tag-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 0.55rem;
      flex-wrap: wrap;
      margin-top: 0.1rem;
    }

    .card-actions {
      margin-top: 0.35rem;
      display: flex;
      justify-content: flex-end;
    }

    .btn-detail-item {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 999px;
      padding: 0.28rem 0.62rem;
      font-size: 0.74rem;
      font-weight: 700;
      text-decoration: none;
      color: #1d4ed8;
      border: 1px solid #bfdbfe;
      background: #eff6ff;
      transition: 0.18s ease;
    }

    .btn-detail-item:hover {
      color: #fff;
      background: #1d4ed8;
      border-color: #1d4ed8;
      text-decoration: none;
    }

    .badge-soft-public {
      font-size: 0.74rem;
      border-radius: 999px;
      padding: 0.25rem 0.58rem;
      background: #eff6ff;
      color: #1d4ed8;
      font-weight: 700;
    }

    .badge-soft-public.status {
      background: #ecfdf3;
      color: #047857;
    }

    .badge-soft-public.status.pending {
      background: #ecfdf3;
      color: #047857;
    }

    .badge-soft-public.status.picked {
      background: #e2e8f0;
      color: #334155;
    }

    .public-section {
      background: transparent;
      border: 0;
      border-top: 1px solid #dbe7f5;
      border-radius: 0;
      padding: 1.85rem 0.95rem;
    }

    .public-section > * {
      max-width: 1180px;
      margin-left: auto;
      margin-right: auto;
    }

    .public-shell .public-section:first-of-type {
      border-top: 0;
      padding-top: 1.1rem;
    }

    .empty-state {
      border: 1px dashed #cbd5e1;
      border-radius: 14px;
      background: #f8fbff;
      padding: 1.5rem;
      text-align: center;
      color: #64748b;
    }

    .best-match-card {
      border: 1px solid #c7dcff;
      border-radius: 14px;
      background: linear-gradient(120deg, #eff6ff 0%, #f8fbff 100%);
      padding: 0.95rem 1rem;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.8rem;
      flex-wrap: wrap;
    }

    .best-match-card h3 {
      margin: 0;
      font-size: 0.95rem;
      font-weight: 800;
      color: #1d4ed8;
    }

    .best-match-card p {
      margin: 0.18rem 0 0;
      color: #334155;
      font-size: 0.88rem;
    }

    .footer-info {
      margin-top: 0.5rem;
      border-radius: 26px 26px 0 0;
      overflow: hidden;
      color: #e6f0ff;
      background:
        radial-gradient(145% 120% at 100% 100%, rgba(0, 166, 166, 0.24) 0%, rgba(0, 166, 166, 0) 62%),
        radial-gradient(120% 165% at 0% 0%, rgba(96, 165, 250, 0.24) 0%, rgba(96, 165, 250, 0) 56%),
        linear-gradient(120deg, #1e3a8a 0%, #0f6bff 52%, #00a6a6 100%);
      border: 1px solid rgba(147, 197, 253, 0.36);
      box-shadow: 0 16px 32px rgba(15, 23, 42, 0.28);
      position: relative;
    }

    .footer-info::before {
      content: "";
      position: absolute;
      inset: 0;
      background: repeating-linear-gradient(-35deg, rgba(255, 255, 255, 0.035) 0 1px, rgba(255, 255, 255, 0) 1px 26px);
      opacity: 0.18;
      pointer-events: none;
    }

    .footer-wrap {
      max-width: 1160px;
      margin: 0 auto;
      position: relative;
      z-index: 1;
    }

    .footer-body {
      padding: 1.25rem 1.4rem 0.95rem;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: 1.35fr 0.8fr 0.85fr 1fr;
      gap: 1rem;
    }

    .footer-panel {
      padding: 0.8rem 0.85rem;
      background: rgba(15, 23, 42, 0.14);
      border: 1px solid rgba(191, 219, 254, 0.3);
      border-radius: 14px;
    }

    .footer-brand {
      display: inline-flex;
      align-items: center;
      gap: 0.6rem;
      margin-bottom: 0.6rem;
    }

    .footer-brand-icon {
      width: 32px;
      height: 32px;
      border-radius: 10px;
      object-fit: contain;
      background: rgba(255, 255, 255, 0.92);
      padding: 0.2rem;
      border: 1px solid rgba(255, 255, 255, 0.48);
      image-rendering: -webkit-optimize-contrast;
      image-rendering: crisp-edges;
    }

    .footer-brand-text {
      font-size: 1.28rem;
      font-weight: 700;
      line-height: 1.1;
      color: #f8fbff;
    }

    .footer-title {
      margin: 0 0 0.62rem;
      font-weight: 700;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.07em;
      color: rgba(216, 230, 248, 0.98);
    }

    .footer-text {
      margin: 0;
      color: rgba(230, 239, 251, 0.96);
      font-size: 0.84rem;
      line-height: 1.5;
    }

    .footer-info a,
    .footer-info li {
      color: rgba(230, 239, 251, 0.96);
      font-size: 0.84rem;
      text-decoration: none;
    }

    .footer-info a:hover {
      color: #fff;
      text-decoration: none;
      opacity: 0.95;
    }

    .footer-list,
    .footer-contact-list,
    .footer-hours-list {
      list-style: none;
      margin: 0;
      padding: 0;
      display: grid;
      gap: 0.4rem;
    }

    .footer-contact-item {
      display: flex;
      gap: 0.5rem;
      align-items: flex-start;
    }

    .footer-contact-item i {
      margin-top: 0.18rem;
      width: 14px;
      color: rgba(223, 235, 250, 0.96);
    }

    .footer-hours-list li {
      display: flex;
      justify-content: space-between;
      gap: 0.6rem;
    }

    .footer-social-title {
      margin: 0.8rem 0 0.45rem;
      font-size: 0.8rem;
      font-weight: 700;
      color: rgba(221, 234, 251, 0.98);
      text-transform: uppercase;
      letter-spacing: 0.08em;
    }

    .social-list {
      display: flex;
      flex-wrap: wrap;
      gap: 0.4rem;
    }

    .social-link {
      width: 30px;
      height: 30px;
      border-radius: 8px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 1px solid rgba(191, 219, 254, 0.52);
      background: rgba(37, 99, 235, 0.2);
      color: #f0f6ff;
      transition: background 0.2s ease;
    }

    .social-link:hover {
      background: rgba(37, 99, 235, 0.34);
    }

    .footer-cta {
      margin-top: 0.75rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.35rem;
      border-radius: 999px;
      border: 0;
      background: linear-gradient(135deg, #0f6bff, #00a6a6);
      color: #f5f9ff;
      font-size: 0.82rem;
      font-weight: 700;
      padding: 0.45rem 0.92rem;
    }

    .footer-cta:hover {
      background: linear-gradient(135deg, #1f78ff, #14b8a6);
      color: #f5f9ff;
      text-decoration: none;
    }

    .footer-note {
      margin: 0.9rem 0 0;
      padding: 0.78rem 1.4rem 1.05rem;
      border-top: 1px solid rgba(191, 219, 254, 0.34);
      font-size: 0.76rem;
      color: rgba(220, 233, 249, 0.95);
      text-align: center;
    }

    .footer-note-link {
      color: #60a5fa !important;
      font-weight: 700;
      text-decoration: underline;
      text-decoration-color: rgba(147, 197, 253, 0.75);
      text-underline-offset: 2px;
    }

    .footer-note-link:hover {
      color: #93c5fd !important;
      text-decoration-color: #93c5fd;
    }

    .pagination-wrap .pagination {
      margin-bottom: 0;
      justify-content: center;
    }

    @media (max-width: 1199.98px) {
      .cards-grid {
        grid-template-columns: repeat(auto-fill, minmax(210px, 230px));
      }
    }

    @media (max-width: 991.98px) {
      .cards-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 220px));
      }

      .stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .footer-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .lf-hero > .hero-carousel-wrap {
        max-width: calc(100vw - 1.8rem);
      }

      .hero-carousel-wrap .carousel-item {
        height: clamp(320px, 52vw, 480px);
      }
    }

    @media (max-width: 575.98px) {
      .cards-grid,
      .stats-grid {
        grid-template-columns: 1fr;
      }

      .found-card {
        max-width: 100%;
      }

      .public-section {
        padding: 1.35rem 0.8rem;
      }

      .footer-grid {
        grid-template-columns: 1fr;
      }

      .footer-body,
      .footer-note {
        padding-left: 0.9rem;
        padding-right: 0.9rem;
      }

      .found-media,
      .found-fallback {
        height: 132px;
      }

      .latest-carousel-wrap .carousel-item {
        height: 280px;
      }

      .hero-carousel-wrap .carousel-item {
        height: 360px;
      }

      .lf-hero .hero-subtitle {
        font-size: 0.9rem;
        margin-bottom: 0.45rem;
      }

      .hero-inline-title {
        margin-top: 0.72rem;
        font-size: 1.02rem;
      }

      .latest-slide-title {
        font-size: 0.98rem;
      }

      .latest-slide-desc {
        font-size: 0.8rem;
      }
    }
  </style>
@endpush

@section('content')
  <div class="public-shell">
    <section id="home" class="lf-hero">
      <h1>Informasi Barang Temuan</h1>
      <p class="hero-subtitle">Cari barang Anda berdasarkan nama, kategori, atau lokasi ditemukan.</p>

      {{-- <p class="hero-inline-title">Barang Temuan Terbaru</p> --}}

      @if ($latestItems->isEmpty())
        <div class="hero-empty-state">Belum ada barang temuan terbaru yang bisa ditampilkan.</div>
      @else
        @php
          $showCarouselIndicators = $latestItems->count() > 1 && $latestItems->count() <= 12;
        @endphp
        <div id="latestFoundCarousel" class="carousel slide latest-carousel-wrap hero-carousel-wrap" data-ride="carousel" data-interval="3000" data-pause="hover">
          @if ($showCarouselIndicators)
            <ol class="carousel-indicators">
              @foreach ($latestItems as $item)
                <li data-target="#latestFoundCarousel" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
              @endforeach
            </ol>
          @endif

          <div class="carousel-inner">
            @foreach ($latestItems as $item)
              <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                @if ($item->photo_path)
                  <img
                    src="{{ asset('storage/' . $item->photo_path) }}"
                    alt="Foto {{ $item->name }}"
                    class="latest-slide-media"
                    loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                    fetchpriority="{{ $loop->first ? 'high' : 'auto' }}"
                    decoding="async"
                  >
                @else
                  <div class="latest-slide-fallback">
                    <i class="fas fa-box-open"></i>
                  </div>
                @endif

                <div class="latest-slide-body">
                  <h3 class="latest-slide-title">{{ $item->name }}</h3>
                  <p class="latest-slide-desc">{{ \Illuminate\Support\Str::limit($item->description ?: 'Tidak ada deskripsi tambahan.', 135) }}</p>

                  <div class="latest-slide-meta">
                    <span><i class="far fa-calendar-alt"></i>{{ $item->found_at ? \Carbon\Carbon::parse($item->found_at)->format('d M Y') : '-' }}</span>
                    <span><i class="fas fa-map-marker-alt"></i>{{ $item->found_location ?: '-' }}</span>
                  </div>

                  <div class="latest-slide-tags">
                    <span class="latest-pill">{{ $item->category?->name ?: 'Tanpa Kategori' }}</span>
                    <span class="latest-pill status pending">Belum Diambil</span>
                    <a href="{{ route('public.dashboard.show', $item->id) }}" class="latest-pill link">Lihat Detail</a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          @if ($latestItems->count() > 1)
            <a class="carousel-control-prev" href="#latestFoundCarousel" role="button" data-slide="prev" aria-label="Slide sebelumnya">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </a>
            <a class="carousel-control-next" href="#latestFoundCarousel" role="button" data-slide="next" aria-label="Slide berikutnya">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </a>
          @endif
        </div>
      @endif
    </section>

    <section class="public-section" id="daftar-temuan">
      <div class="section-head centered">
        <div>
          <h2>Daftar Barang Temuan</h2>
          <p>Data barang tersedia dari database dan diperbarui oleh admin.</p>
        </div>
      </div>

      @if ($q !== '' && $topSearchItem)
        <div class="best-match-card">
          <div>
            <h3>Paling Relevan untuk "{{ $q }}"</h3>
            <p>
              <strong>{{ $topSearchItem->name }}</strong>
              • {{ $topSearchItem->category?->name ?: 'Tanpa Kategori' }}
              • {{ $topSearchItem->found_location ?: '-' }}
            </p>
          </div>
          <a href="{{ route('public.dashboard.show', $topSearchItem->id) }}" class="btn-detail-item">Buka Barang Ini</a>
        </div>
      @endif

      @if ($foundItems->isEmpty())
        <div class="empty-state">Tidak ada hasil untuk kata kunci pencarian Anda.</div>
      @else
        <div class="cards-grid">
          @foreach ($foundItems as $item)
            <article class="card found-card">
              @if ($item->photo_path)
                <img src="{{ asset('storage/' . $item->photo_path) }}" alt="Foto {{ $item->name }}" class="found-media">
              @else
                <div class="found-fallback">
                  <i class="fas fa-box-open"></i>
                </div>
              @endif

              <div class="card-body">
                <h3 class="found-title">{{ $item->name }}</h3>
                <p class="found-desc">{{ \Illuminate\Support\Str::limit($item->description ?: 'Tidak ada deskripsi tambahan.', 95) }}</p>
                <p class="meta-line"><i class="far fa-calendar-alt"></i>{{ $item->found_at ? \Carbon\Carbon::parse($item->found_at)->format('d M Y') : '-' }}</p>
                <p class="meta-line"><i class="fas fa-map-marker-alt"></i>{{ $item->found_location ?: '-' }}</p>
                <div class="tag-row">
                  <span class="badge-soft-public">{{ $item->category?->name ?: 'Tanpa Kategori' }}</span>
                  <span class="badge-soft-public status pending">Belum Diambil</span>
                </div>
                <div class="card-actions">
                  <a href="{{ route('public.dashboard.show', $item->id) }}" class="btn-detail-item">Lihat Detail</a>
                </div>
              </div>
            </article>
          @endforeach
        </div>

        <div class="pagination-wrap mt-3">
          {{ $foundItems->links('pagination::bootstrap-4') }}
        </div>
      @endif
    </section>

    <section class="public-section" id="statistik">
      <div class="section-head centered">
        <div>
          <h2>Statistik Barang Temuan</h2>
          <p>Ringkasan dari data temuan pada sistem.</p>
        </div>
      </div>

      <div class="stats-grid">
        <article class="stat-card">
          <span class="stat-icon"><i class="fas fa-boxes"></i></span>
          <p class="stat-label">Total Barang Temuan</p>
          <p class="stat-value js-countup" data-target="{{ $totalFoundCount }}">0</p>
        </article>

        <article class="stat-card">
          <span class="stat-icon"><i class="fas fa-hourglass-half"></i></span>
          <p class="stat-label">Belum Diambil</p>
          <p class="stat-value js-countup" data-target="{{ $pendingFoundCount }}">0</p>
        </article>

        <article class="stat-card">
          <span class="stat-icon"><i class="fas fa-check-circle"></i></span>
          <p class="stat-label">Sudah Diambil</p>
          <p class="stat-value js-countup" data-target="{{ $pickedFoundCount }}">0</p>
        </article>
      </div>
    </section>

    <section id="kontak" class="footer-info">
      <div class="footer-wrap">
        <div class="footer-body">
          <div class="footer-grid">
            <div class="footer-panel">
              <div class="footer-brand">
                <img src="{{ asset('images/favicon-32x32.png') }}" alt="Logo SIMARFA Publik" class="footer-brand-icon">
                <span class="footer-brand-text">SIMARFA</span>
              </div>
              <p class="footer-text">Sistem informasi barang temuan perpustakaan kota untuk membantu mempercepat proses temuan kembali ke pemilik.</p>

              <p class="footer-social-title">Ikuti Kami</p>
              <div class="social-list">
                <a class="social-link" href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a class="social-link" href="https://www.instagram.com/puskotjogja/" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a class="social-link" href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
              </div>
            </div>

            <div class="footer-panel">
              <h3 class="footer-title">Navigasi</h3>
              <ul class="footer-list">
                <li><a href="{{ route('public.dashboard') }}#home">Home</a></li>
                <li><a href="{{ route('public.dashboard') }}#daftar-temuan">Daftar Temuan</a></li>
                <li><a href="{{ route('public.dashboard') }}#statistik">Statistik</a></li>
                <li><a href="{{ route('public.dashboard') }}#kontak">Kontak</a></li>
              </ul>
            </div>

            <div class="footer-panel">
              <h3 class="footer-title">Quick Link</h3>
              <ul class="footer-list">
                <li><a href="{{ route('public.dashboard') }}#daftar-temuan">Kategori</a></li>
                <li><a href="{{ route('public.dashboard') }}#statistik">Data</a></li>
                <li><a href="{{ route('login') }}">Admin</a></li>
                <li><a href="{{ route('public.dashboard') }}#kontak">Lokasi</a></li>
              </ul>
            </div>

            <div class="footer-panel">
              <h3 class="footer-title">Work Hours</h3>
              <ul class="footer-hours-list">
                <li><span>Senin - Kamis</span><span>07.30 - 20.00</span></li>
                <li><span>Jumat</span><span>09.00 - 17.00</span></li>
                <li><span>Sabtu - Minggu</span><span>08.30 - 12.00</span></li>
              </ul>
              <a href="https://maps.google.com/?q=Jl.+Suroto+No.+9+Kotabaru+Yogyakarta" target="_blank" rel="noopener noreferrer" class="footer-cta">
                <i class="fas fa-phone-alt"></i>
                Call Us
              </a>
            </div>
          </div>
        </div>
        <p class="footer-note">
          Copyright {{ date('Y') }}
          <a href="https://www.instagram.com/puskotjogja/" target="_blank" rel="noopener noreferrer" class="footer-note-link">Perpustakaan Kota Yogyakarta</a>.
          Semua hak dilindungi.
        </p>
      </div>
    </section>
  </div>
@endsection

@push('scripts')
  <script>
    (function () {
      const counters = document.querySelectorAll('.js-countup');
      if (!counters.length) return;

      const animate = (el) => {
        const target = Number(el.getAttribute('data-target') || 0);
        const duration = 1200;
        const start = performance.now();

        const tick = (now) => {
          const progress = Math.min((now - start) / duration, 1);
          const eased = 1 - Math.pow(1 - progress, 3);
          el.textContent = String(Math.round(target * eased));

          if (progress < 1) {
            requestAnimationFrame(tick);
          } else {
            el.textContent = String(target);
          }
        };

        requestAnimationFrame(tick);
      };

      const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          animate(entry.target);
          obs.unobserve(entry.target);
        });
      }, { threshold: 0.4 });

      counters.forEach((counter) => observer.observe(counter));
    })();

    (function () {
      const query = @json($q);
      if (!query) return;

      const targetSection = document.getElementById('daftar-temuan');
      if (!targetSection) return;

      // Keep the section visible below the fixed navbar.
      const offsetTop = 86;
      const targetY = targetSection.getBoundingClientRect().top + window.pageYOffset - offsetTop;

      window.requestAnimationFrame(function () {
        window.scrollTo({
          top: Math.max(0, targetY),
          behavior: 'smooth'
        });
      });

      if (window.history && typeof window.history.replaceState === 'function') {
        window.history.replaceState(null, '', window.location.pathname + window.location.search + '#daftar-temuan');
      }
    })();
  </script>
@endpush
