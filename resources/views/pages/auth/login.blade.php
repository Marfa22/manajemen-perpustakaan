<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIMARFA | Login</title>
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&display=swap">
  <link rel="stylesheet" href="{{ asset('templates/plugins/fontawesome-free/css/all.min.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    :root {
      --bg-app: #eef3f9;
      --bg-panel: #ffffff;
      --ink: #0f172a;
      --muted: #64748b;
      --line: #e2e8f0;
      --brand: #0f6bff;
      --brand-dark: #0b56cc;
      --brand-soft: #dbeafe;
      --brand-2: #00a6a6;
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: "Plus Jakarta Sans", sans-serif;
      color: var(--ink);
      background: radial-gradient(circle at top right, #dbeafe 0%, #f8fafc 45%, #eef2ff 100%);
      display: grid;
      place-items: center;
      padding: 20px;
      position: relative;
    }

    body::before,
    body::after {
      content: "";
      position: fixed;
      pointer-events: none;
      z-index: 0;
      background: rgba(226, 236, 243, 0.22);
      border-radius: 999px;
      backdrop-filter: blur(2px);
    }

    body::before {
      width: 180px;
      height: 74px;
      left: -22px;
      bottom: -18px;
    }

    body::after {
      width: 102px;
      height: 102px;
      right: -18px;
      bottom: 44px;
    }

    .login-wrap {
      width: min(920px, 100%);
      display: grid;
      gap: 14px;
      position: relative;
      z-index: 1;
    }

    .institution-badge {
      justify-self: center;
      color: #1e3a8a;
      font-size: 0.85rem;
      font-weight: 700;
      letter-spacing: 0.01em;
      text-align: center;
      text-shadow: 0 1px 0 rgba(255, 255, 255, 0.4);
    }

    .login-shell {
      width: 100%;
      background: rgba(255, 255, 255, 0.92);
      border: 1px solid rgba(226, 232, 240, 0.88);
      border-radius: 16px;
      box-shadow: 0 22px 48px rgba(15, 107, 255, 0.18);
      overflow: hidden;
      display: grid;
      grid-template-columns: 1.08fr 1fr;
    }

    .login-side {
      padding: 34px 30px;
      background: linear-gradient(145deg, var(--brand) 0%, var(--brand-dark) 56%, var(--brand-2) 100%);
      color: #eff5ff;
      position: relative;
      overflow: hidden;
    }

    .login-side::after {
      content: "";
      position: absolute;
      width: 220px;
      height: 220px;
      border-radius: 999px;
      right: -70px;
      bottom: -72px;
      background: rgba(225, 236, 255, 0.2);
    }

    .brand-row {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 24px;
      position: relative;
      z-index: 1;
    }

    .brand-badge {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.18);
      border: 1px solid rgba(255, 255, 255, 0.26);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .brand-badge img {
      width: 27px;
      height: 27px;
      object-fit: contain;
      display: block;
    }

    .brand-title {
      font-size: 1.08rem;
      font-weight: 800;
      margin: 0;
      color: #f8fbff;
    }

    .brand-sub {
      margin: 2px 0 0;
      font-size: 0.83rem;
      color: rgba(235, 243, 255, 0.92);
      line-height: 1.45;
      max-width: 280px;
    }

    .side-copy {
      position: relative;
      z-index: 1;
      margin-top: 18px;
    }

    .side-copy h2 {
      margin: 0 0 9px;
      font-size: 1.62rem;
      line-height: 1.25;
      font-weight: 800;
      color: #f8fbff;
    }

    .side-copy p {
      margin: 0;
      color: rgba(231, 241, 255, 0.95);
      font-size: 0.93rem;
      line-height: 1.55;
    }

    .login-main {
      padding: 34px 28px 28px;
      background: transparent;
    }

    .login-main h3 {
      margin: 0;
      font-size: 1.2rem;
      font-weight: 800;
      text-align: center;
    }

    .login-main .muted {
      color: var(--muted);
      margin: 6px 0 20px;
      font-size: 0.84rem;
      text-align: center;
    }

    .field {
      margin-bottom: 14px;
    }

    .field label {
      display: block;
      margin-bottom: 7px;
      font-size: 0.84rem;
      font-weight: 700;
      color: #334155;
    }

    .input-wrap {
      position: relative;
    }

    .input-wrap i {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 0.9rem;
    }

    .field input {
      width: 100%;
      height: 44px;
      border-radius: 10px;
      border: 1px solid #dbe3ef;
      padding: 0 12px 0 38px;
      font-size: 0.88rem;
      transition: 0.2s ease;
      outline: none;
      background: #fbfcff;
    }

    #password {
      padding-right: 42px;
    }

    .password-toggle {
      position: absolute;
      right: 6px;
      top: 50%;
      transform: translateY(-50%);
      width: 30px;
      height: 30px;
      border: 0;
      border-radius: 8px;
      background: transparent;
      color: #94a3b8;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: color .2s ease, background-color .2s ease;
    }

    .password-toggle:hover,
    .password-toggle:focus-visible {
      color: #334155;
      background: #f1f5f9;
      outline: none;
    }

    .field input:focus {
      border-color: #93c5fd;
      box-shadow: 0 0 0 4px rgba(15, 107, 255, 0.14);
    }

    .btn-login {
      width: 100%;
      border: 0;
      height: 44px;
      border-radius: 10px;
      color: #fff;
      font-size: 0.88rem;
      font-weight: 700;
      background: linear-gradient(135deg, var(--brand), var(--brand-2));
      cursor: pointer;
      transition: transform .2s ease, filter .2s ease, box-shadow .2s ease;
    }

    .btn-login:hover,
    .btn-login:focus-visible {
      filter: brightness(1.05);
      transform: translateY(-1px);
      box-shadow: 0 10px 20px rgba(15, 107, 255, 0.3);
      outline: none;
    }

    .btn-login:active {
      transform: translateY(0);
      box-shadow: 0 6px 14px rgba(15, 107, 255, 0.24);
    }

    .alert-custom {
      border-radius: 10px;
      border: 1px solid #fecaca;
      background: #fef2f2;
      color: #b91c1c;
      font-size: 0.86rem;
      padding: 10px 12px;
      margin-bottom: 14px;
    }

    .bottom-link {
      margin-top: 14px;
      text-align: center;
      font-size: 0.86rem;
      color: #64748b;
    }

    .bottom-link a {
      color: var(--brand);
      text-decoration: none;
      font-weight: 700;
      display: inline-block;
      position: relative;
      padding: 2px 8px;
      border-radius: 999px;
      transition: color .2s ease, background-color .2s ease, transform .2s ease;
    }

    .bottom-link a::after {
      content: "";
      position: absolute;
      left: 8px;
      right: 8px;
      bottom: 1px;
      height: 2px;
      border-radius: 999px;
      background: currentColor;
      transform: scaleX(0);
      transform-origin: left;
      transition: transform .22s ease;
    }

    .bottom-link a:hover,
    .bottom-link a:focus-visible {
      color: var(--brand-dark);
      background: #eff6ff;
      transform: translateY(-1px);
      outline: none;
    }

    .bottom-link a:hover::after,
    .bottom-link a:focus-visible::after {
      transform: scaleX(1);
    }

    @media (max-width: 860px) {
      .login-wrap {
        width: min(430px, 100%);
      }

      .login-shell {
        grid-template-columns: 1fr;
      }

      .login-side {
        display: none;
      }
    }

    @media (max-width: 575px) {
      .institution-badge {
        font-size: 0.78rem;
      }

      .login-main {
        padding: 28px 20px 24px;
      }
    }
  </style>
</head>
<body>
  <div class="login-wrap">
    <div class="institution-badge">
      Perpustakaan Kota Yogyakarta
    </div>

    <div class="login-shell">
      <section class="login-side">
        <div class="brand-row">
          <div class="brand-badge">
            <img src="{{ asset('images/favicon-32x32.png') }}" alt="Logo SIMARFA">
          </div>
          <div>
            <h1 class="brand-title">SIMARFA</h1>
            <p class="brand-sub">Sistem Informasi Manajemen Arsip dan Rekap Fungsi Administrasi</p>
          </div>
        </div>
        <div class="side-copy">
          <h2>Masuk sebagai Admin</h2>
          <p>Kelola kategori, barang kantor, dan barang temuan dalam satu panel yang terintegrasi.</p>
        </div>
      </section>

      <section class="login-main">
        <h3>Login Admin</h3>
        <p class="muted">Gunakan akun admin yang sudah terdaftar.</p>

        @if (session('error-unauthorized'))
          <div class="alert-custom">{{ session('error-unauthorized') }}</div>
          <script>
            Swal.fire({
              title: "Akses Ditolak",
              text: "{{ session('error-unauthorized') }}",
              icon: "error",
              scrollbarPadding: false,
              heightAuto: false
            });
          </script>
        @endif

        @if ($errors->any())
          <div class="alert-custom">
            {{ $errors->first() }}
          </div>
        @endif

        <form action="{{ route('login.perform') }}" method="POST">
          @csrf
          <div class="field">
            <label for="username">Username</label>
            <div class="input-wrap">
              <i class="fas fa-user"></i>
              <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username" required>
            </div>
          </div>

          <div class="field">
            <label for="password">Password</label>
            <div class="input-wrap">
              <i class="fas fa-lock"></i>
              <input type="password" id="password" name="password" placeholder="Masukkan password" required>
              <button type="button" class="password-toggle" id="togglePassword" aria-label="Lihat password" aria-controls="password" aria-pressed="false">
                <i class="far fa-eye"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="btn-login">Masuk ke Dashboard</button>
        </form>

        <div class="bottom-link">
          Kembali ke halaman publik? <a href="{{ route('public.dashboard') }}">Lihat Halaman Publik SIMARFA</a>
        </div>
      </section>
    </div>
  </div>

  <script>
    (function () {
      const passwordInput = document.getElementById('password');
      const toggleBtn = document.getElementById('togglePassword');

      if (!passwordInput || !toggleBtn) {
        return;
      }

      const icon = toggleBtn.querySelector('i');

      toggleBtn.addEventListener('click', function () {
        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';

        toggleBtn.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
        toggleBtn.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Lihat password');

        if (icon) {
          icon.classList.toggle('fa-eye', !isHidden);
          icon.classList.toggle('fa-eye-slash', isHidden);
        }
      });
    })();
  </script>
</body>
</html>
