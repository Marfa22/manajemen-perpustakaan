<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIMARFA | Sistem Informasi Manajemen Arsip dan Rekap Fungsi Administrasi</title>

  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap">
  <link rel="stylesheet" href="{{ asset('templates//plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('templates//dist/css/adminlte.min.css') }}">
  <style>
    :root {
      --brand: #0f6bff;
      --brand-dark: #1d4ed8;
      --ink: #0f172a;
      --muted: #64748b;
    }

    body {
      font-family: "Plus Jakarta Sans", sans-serif;
      font-size: 17px;
      line-height: 1.6;
      min-height: 100vh;
      background: radial-gradient(circle at top right, #dbeafe 0%, #eff6ff 36%, #f8fafc 100%);
      color: var(--ink);
    }

    .public-nav {
      border-bottom: 1px solid #e2e8f0;
      background: rgba(255, 255, 255, 0.9) !important;
      backdrop-filter: blur(8px);
      position: sticky;
      top: 0;
      z-index: 1030;
      transition: transform 0.28s ease, box-shadow 0.28s ease;
      will-change: transform;
    }

    .public-nav.is-hidden {
      transform: translateY(-100%);
    }

    .public-nav .navbar-brand {
      font-weight: 800;
      font-size: 1.08rem;
      color: var(--ink);
      display: inline-flex;
      align-items: center;
      gap: 0.56rem;
    }

    .public-nav .navbar-brand .brand-mascot {
      width: 32px;
      height: 32px;
      object-fit: contain;
      flex-shrink: 0;
      image-rendering: -webkit-optimize-contrast;
      image-rendering: crisp-edges;
    }

    .public-nav .nav-link {
      color: #334155;
      font-weight: 600;
      font-size: 0.95rem;
      border-radius: 9px;
      padding: 0.45rem 0.66rem;
    }

    .public-nav .navbar-collapse {
      align-items: center;
      gap: 0.25rem;
    }

    .public-nav-search {
      flex: 1 1 340px;
      max-width: 360px;
      margin: 0 0.7rem;
    }

    .public-nav-search .input-group {
      border: 1px solid #dbe5f5;
      border-radius: 10px;
      overflow: hidden;
      background: #f8fbff;
    }

    .public-nav-search .form-control {
      border: 0;
      box-shadow: none;
      font-size: 0.88rem;
      height: 38px;
      padding-left: 0.72rem;
      background: transparent;
      color: #1e3a8a;
    }

    .public-nav-search .form-control::placeholder {
      color: #94a3b8;
    }

    .public-nav-search .btn {
      border: 0;
      height: 38px;
      border-radius: 0;
      background: linear-gradient(135deg, #8cb6f5, #6699e5);
      color: #fff;
      font-size: 0.86rem;
      font-weight: 700;
      padding: 0 0.85rem;
      display: inline-flex;
      align-items: center;
      gap: 0.32rem;
    }

    .public-nav-search .btn:hover {
      background: linear-gradient(135deg, #9bc2fb, #75a7ed);
      color: #fff;
    }

    .public-nav .nav-link:hover {
      color: var(--brand-dark);
      background: #eaf1ff;
    }

    .btn-public {
      background: var(--brand-dark);
      border: 0;
      color: #fff !important;
      font-weight: 700;
      font-size: 0.92rem;
      border-radius: 9px;
      padding: 0.52rem 0.95rem !important;
      line-height: 1.2;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.38rem;
      min-width: 132px;
      text-align: center;
      transition: background-color 0.2s ease;
    }

    .btn-public:hover {
      background: #1e40af;
    }

    .btn-public:active {
      background: #1d3a8a;
    }

    .content-wrapper {
      background: transparent;
      min-height: calc(100vh - 68px);
    }

    .content {
      padding-left: 0 !important;
      padding-right: 0 !important;
    }

    .public-container {
      width: 100%;
      max-width: 100%;
      padding-left: 0 !important;
      padding-right: 0 !important;
    }

    @media (max-width: 767.98px) {
      .public-nav-search {
        width: 100%;
        max-width: none;
        margin: 0.45rem 0 0.6rem;
      }
    }
  </style>
  @stack('styles')
</head>
<body class="layout-top-nav">
  <div class="wrapper">
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white public-nav">
      <div class="container-fluid px-3 px-md-4">
        <a href="{{ route('public.dashboard') }}" class="navbar-brand">
          <img src="{{ asset('images/favicon-32x32.png') }}" alt="Logo SIMARFA Publik" class="brand-mascot">
          <span>SIMARFA</span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#publicNavbar" aria-controls="publicNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="publicNavbar">
          @if (request()->is('/') || request()->is('dash-public'))
            <form action="{{ route('public.dashboard') }}" method="GET" class="public-nav-search" role="search">
              <div class="input-group">
                <input
                  type="text"
                  name="q"
                  value="{{ request()->query('q', '') }}"
                  class="form-control"
                  placeholder="Cari barang temuan..."
                  aria-label="Cari barang temuan"
                >
                <div class="input-group-append">
                  <button type="submit" class="btn">
                    <i class="fas fa-search"></i><span>Cari</span>
                  </button>
                </div>
              </div>
            </form>
          @endif

          <ul class="navbar-nav ml-auto align-items-md-center">
            <li class="nav-item"><a href="{{ route('public.dashboard') }}#home" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="{{ route('public.dashboard') }}#daftar-temuan" class="nav-link">Daftar Temuan</a></li>
            <li class="nav-item"><a href="{{ route('public.dashboard') }}#statistik" class="nav-link">Statistik</a></li>
            <li class="nav-item"><a href="{{ route('public.dashboard') }}#kontak" class="nav-link">Lokasi</a></li>
            <li class="nav-item ml-md-2 mt-2 mt-md-0">
              <a href="{{ route('login') }}" class="nav-link btn-public text-center">
                <i class="fas fa-lock"></i>Login Admin
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="content-wrapper">
      <section class="content pt-0 pb-0">
        <div class="container-fluid public-container">
          @yield('content')
        </div>
      </section>
    </div>
  </div>

  <script src="{{ asset('templates//plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('templates//plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('templates//dist/js/adminlte.min.js') }}"></script>
  <script>
    (() => {
      const nav = document.querySelector('.public-nav');
      if (!nav) {
        return;
      }

      let lastScrollY = window.scrollY;
      let ticking = false;
      const revealAt = 12;
      const hideAfter = 10;
      const delta = 6;

      const updateNavState = () => {
        const currentScrollY = window.scrollY;
        const distance = currentScrollY - lastScrollY;
        const navbarExpanded = document.querySelector('#publicNavbar.show');

        if (currentScrollY <= revealAt || navbarExpanded) {
          nav.classList.remove('is-hidden');
        } else if (Math.abs(distance) >= delta) {
          if (distance > 0 && currentScrollY > hideAfter) {
            nav.classList.add('is-hidden');
          } else if (distance < 0) {
            nav.classList.remove('is-hidden');
          }
        }

        lastScrollY = currentScrollY;
        ticking = false;
      };

      window.addEventListener('scroll', () => {
        if (ticking) {
          return;
        }

        ticking = true;
        window.requestAnimationFrame(updateNavState);
      }, { passive: true });
    })();
  </script>
  @stack('scripts')
</body>
</html>
