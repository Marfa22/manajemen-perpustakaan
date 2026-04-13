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
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset ('templates//plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset ('templates//dist/css/adminlte.min.css') }}">
  @stack('styles')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    :root {
      --bg-app: #eef3f9;
      --bg-panel: #ffffff;
      --ink: #0f172a;
      --muted: #64748b;
      --brand: #0f6bff;
      --brand-dark: #0b56cc;
      --brand-soft: #dbeafe;
      --line: #e2e8f0;
      --brand-2: #00a6a6;
      --sidebar-bg: #1f2937;
      --sidebar-bg-2: #111827;
    }

    body {
      font-family: "Plus Jakarta Sans", sans-serif;
      background: radial-gradient(circle at top right, #dbeafe 0%, #eef3f9 40%);
      color: var(--ink);
      line-height: 1.45;
    }

    .main-header.navbar {
      border-bottom: 1px solid var(--line);
      background: rgba(255, 255, 255, 0.88) !important;
      backdrop-filter: blur(8px);
      padding: 0.5rem 0.85rem;
    }

    .main-header .navbar-nav {
      align-items: center;
    }

    .main-header .navbar-nav > .nav-item {
      margin-right: 0.22rem;
    }

    .main-header .navbar-nav > .nav-item:last-child {
      margin-right: 0;
    }

    .main-header .nav-link {
      color: #334155 !important;
      font-weight: 600;
      padding: 0.5rem 0.72rem;
      border-radius: 9px;
    }

    .main-header .nav-link:hover {
      color: var(--brand) !important;
    }

    .main-header .admin-nav-search-item {
      display: flex;
      align-items: center;
      margin-right: 0.6rem !important;
    }

    .main-header .admin-nav-search {
      width: min(360px, 42vw);
      margin: 0;
    }

    .main-header .admin-nav-search .input-group {
      border: 1px solid #dbe5f5;
      border-radius: 10px;
      overflow: hidden;
      background: #f8fbff;
      flex-wrap: nowrap;
    }

    .main-header .admin-nav-search .form-control {
      border: 0;
      box-shadow: none;
      font-size: 0.88rem;
      height: 38px;
      padding-left: 0.72rem;
      background: transparent;
      color: #1e3a8a;
      min-width: 0;
    }

    .main-header .admin-nav-search .form-control::placeholder {
      color: #94a3b8;
    }

    .main-header .admin-nav-search .btn {
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
      box-shadow: none;
    }

    .main-header .admin-nav-search .btn:hover {
      background: linear-gradient(135deg, #9bc2fb, #75a7ed);
      color: #fff;
    }

    .content-wrapper {
      background: transparent;
    }

    .content-header {
      padding: 0.85rem 0 0.4rem;
    }

    .content-header h1 {
      font-size: 1.52rem;
      font-weight: 800;
      margin-bottom: 0;
      color: #0f172a;
    }

    .content {
      padding-top: 0.5rem;
      padding-bottom: 0.85rem;
    }

    .breadcrumb {
      background: transparent;
      margin-bottom: 0;
      padding: 0;
    }

    .breadcrumb-item a {
      color: #2563eb;
      font-weight: 600;
    }

    .breadcrumb-item.active {
      color: #64748b;
    }

    .btn {
      border-radius: 10px;
      font-weight: 700;
      letter-spacing: 0.01em;
      box-shadow: none;
      border-width: 1px;
      transition: all .18s ease;
    }

    .btn:focus,
    .btn.focus {
      box-shadow: 0 0 0 .2rem rgba(15, 107, 255, .16) !important;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--brand), #3b82f6);
      border-color: transparent;
      box-shadow: 0 8px 18px rgba(15, 107, 255, .26);
    }

    .btn-primary:hover,
    .btn-primary:active {
      background: linear-gradient(135deg, var(--brand-dark), #2563eb);
      border-color: transparent;
      color: #fff;
    }

    .btn-outline-primary {
      color: var(--brand);
      border-color: #93c5fd;
      background: #fff;
    }

    .btn-outline-primary:hover,
    .btn-outline-primary:active {
      color: #fff;
      border-color: var(--brand);
      background: var(--brand);
    }

    .btn-outline-secondary {
      border-color: #cbd5e1;
      color: #475569;
      background: #fff;
    }

    .btn-outline-secondary:hover {
      border-color: #94a3b8;
      color: #1e293b;
      background: #f8fafc;
    }

    .form-control,
    .custom-select {
      border: 1px solid #dbe4f0;
      border-radius: 10px;
      color: #0f172a;
      min-height: 38px;
      font-size: 0.9rem;
    }

    .form-control::placeholder {
      color: #94a3b8;
    }

    .form-control:focus,
    .custom-select:focus {
      border-color: #86b7fe;
      box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .12);
    }

    .card-header {
      border-bottom: 1px solid #eaf0f7;
      background: linear-gradient(180deg, #fbfdff, #f4f8ff);
    }

    .card-header > h3 {
      font-size: 1rem;
      font-weight: 800;
      color: #0f172a;
      margin-bottom: 0;
    }

    .card-body {
      color: #1e293b;
    }

    .table {
      color: #1e293b;
    }

    .table td,
    .table th {
      padding: 0.62rem 0.68rem;
    }

    .main-sidebar {
      background: linear-gradient(180deg, var(--sidebar-bg), var(--sidebar-bg-2)) !important;
      border-right: 1px solid rgba(255, 255, 255, 0.08);
    }

    .main-sidebar .sidebar {
      height: calc(100vh - 57px);
      display: flex;
      flex-direction: column;
      padding-bottom: 0 !important;
      overflow: hidden;
      position: relative;
    }

    .sidebar-nav-scroll {
      overflow-y: auto;
      overflow-x: hidden;
      min-height: 0;
      padding-bottom: 128px;
    }

    .brand-link {
      border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
      margin-top: 8px;
      padding-top: 0.9rem !important;
      padding-bottom: 0.9rem !important;
    }

    .brand-link .sidebar-brand-logo {
      width: 30px;
      height: 30px;
      border-radius: 6px;
      object-fit: contain;
      opacity: 0.95;
    }

    .brand-link .brand-text {
      color: #e2e8f0 !important;
      font-weight: 700 !important;
      letter-spacing: 0.01em;
    }

    .nav-sidebar .nav-link {
      border-radius: 10px;
      margin-bottom: 14px;
      padding-top: 0.78rem;
      padding-bottom: 0.78rem;
      transition: 0.22s ease;
      position: relative;
      overflow: hidden;
    }

    .nav-sidebar .nav-link:hover {
      background: rgba(255, 255, 255, 0.06);
    }

    .nav-sidebar .nav-link.active {
      background: linear-gradient(135deg, var(--brand), #3b82f6) !important;
      box-shadow: 0 8px 20px rgba(15, 107, 255, 0.35);
    }

    .nav-sidebar .nav-link.active::before {
      content: "";
      position: absolute;
      left: 0;
      top: 8px;
      bottom: 8px;
      width: 3px;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.9);
    }

    .nav-sidebar .nav-header {
      font-size: 0.72rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      opacity: 0.7;
      margin-top: 0.9rem;
      margin-bottom: 0.9rem;
    }

    .sidebar-logout-wrap {
      position: absolute;
      left: 10px;
      right: 10px;
      bottom: 12px;
      background: transparent;
      padding-top: 14px;
      border-top: 1px solid rgba(255, 255, 255, 0.14);
    }

    .sidebar-logout {
      border-top: 0;
      padding-top: 0;
      margin-top: 0;
    }

    .sidebar-logout .sidebar-logout-btn {
      background: transparent !important;
      color: rgba(255, 255, 255, 0.72) !important;
      box-shadow: none !important;
      border-radius: 8px;
      padding: 0.58rem 0.7rem;
      font: inherit;
      width: 100%;
      border: 0;
      text-align: left;
      cursor: pointer;
      display: flex;
      align-items: center;
      transition: background-color .18s ease, color .18s ease;
    }

    .sidebar-logout .sidebar-logout-btn:hover {
      color: #fff !important;
      background: rgba(255, 255, 255, 0.06) !important;
      transform: none;
    }

    .sidebar-logout .nav-icon {
      color: inherit !important;
      font-size: 0.92rem;
      margin-right: 0.55rem !important;
    }

    body.sidebar-mini.sidebar-collapse .sidebar-logout .logout-text {
      display: none;
    }

    body.sidebar-mini.sidebar-collapse .sidebar-logout .sidebar-logout-btn {
      justify-content: center;
      padding-left: .35rem;
      padding-right: .35rem;
    }

    body.sidebar-mini.sidebar-collapse .sidebar-logout .nav-icon {
      margin-right: 0 !important;
    }

    body.sidebar-mini.sidebar-collapse .sidebar-logout-wrap {
      left: 8px;
      right: 8px;
      bottom: 10px;
      padding-top: 12px;
    }

    body.sidebar-mini.sidebar-collapse .sidebar-nav-scroll {
      padding-bottom: 104px;
    }

    .card {
      border: 0;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .main-footer {
      background: transparent;
      border-top: 0;
      color: var(--muted);
      font-size: 0.92rem;
    }

    .dash-card {
      position: relative;
      border-radius: 18px;
      padding: 20px;
      color: #fff;
      overflow: hidden;
      min-height: 170px;
      box-shadow: 0 14px 28px rgba(2, 6, 23, 0.2);
    }

    .dash-card h3 {
      font-size: 2.1rem;
      font-weight: 800;
      margin-bottom: 8px;
    }

    .dash-card p {
      margin-bottom: 0;
      font-size: 1rem;
      opacity: 0.95;
    }

    .dash-card .dash-icon {
      position: absolute;
      right: 18px;
      top: 18px;
      font-size: 2.2rem;
      opacity: 0.35;
    }

    .dash-card .dash-link {
      position: absolute;
      left: 20px;
      bottom: 16px;
      color: #fff;
      font-weight: 600;
    }

    .dash-blue {
      background: linear-gradient(140deg, #0f6bff 0%, #06b6d4 100%);
    }

    .dash-emerald {
      background: linear-gradient(140deg, #059669 0%, #10b981 100%);
    }

    .dash-amber {
      background: linear-gradient(140deg, #d97706 0%, #f59e0b 100%);
    }

    .dash-slate {
      background: linear-gradient(140deg, #475569 0%, #64748b 100%);
    }

    .dash-gold {
      background: linear-gradient(140deg, #ca8a04 0%, #facc15 100%);
    }

    .table-shell {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
      overflow: hidden;
    }

    .table-shell .table-topbar {
      padding: 14px 18px;
      border-bottom: 1px solid #eef2f7;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
      background: #f8fbff;
    }

    .table-topbar .table-title {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .table-topbar .table-title h3 {
      font-size: 1rem;
      font-weight: 800;
      margin: 0;
      color: #0f172a;
    }

    .table-topbar .table-title .table-subtitle {
      font-size: 0.78rem;
      color: #64748b;
    }

    .table-shell .table-filter {
      padding: 12px 18px;
      border-bottom: 1px solid #eef2f7;
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }

    .table-shell .table-filter .form-control {
      border-radius: 10px;
      border: 1px solid #dbe4f0;
      font-size: 0.9rem;
      height: 36px;
    }

    .table-shell .table-filter .btn {
      border-radius: 10px;
      height: 36px;
      font-size: 0.82rem;
      font-weight: 600;
      padding: 0 14px;
    }

    .table-shell .table-responsive {
      padding: 4px 12px 0;
    }

    .table-modern {
      margin-bottom: 0;
    }

    .table-modern thead th {
      border-top: 0;
      border-bottom: 1px solid #e5ebf3;
      font-size: 0.76rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: #718096;
      font-weight: 700;
      white-space: nowrap;
      background: #f8fbff;
      position: sticky;
      top: 0;
      z-index: 1;
    }

    .table-modern tbody td {
      border-top: 1px solid #f1f5f9;
      font-size: 0.9rem;
      vertical-align: middle;
    }

    .table-modern tbody tr:hover {
      background: #f8fbff;
    }

    .table-modern .cell-primary {
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 2px;
    }

    .table-modern .cell-secondary {
      font-size: 0.82rem;
      color: #64748b;
      margin: 0;
    }

    .badge-soft {
      display: inline-flex;
      align-items: center;
      border-radius: 999px;
      padding: 0.25rem 0.6rem;
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.02em;
      background: #eff6ff;
      color: #1d4ed8;
    }

    .badge-soft.success {
      background: #ecfdf3;
      color: #047857;
    }

    .badge-soft.warning {
      background: #fff7ed;
      color: #c2410c;
    }

    .badge-soft.danger {
      background: #fef2f2;
      color: #b91c1c;
    }

    .status-pill {
      display: inline-flex;
      align-items: center;
      border-radius: 999px;
      padding: 0.24rem 0.62rem;
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.02em;
      white-space: nowrap;
    }

    .status-pill.pending {
      background: #e5e7eb;
      color: #374151;
    }

    .status-pill.picked {
      background: #fef08a;
      color: #854d0e;
    }

    .item-photo-thumb {
      width: 54px;
      height: 54px;
      object-fit: cover;
      border-radius: 10px;
      border: 1px solid #e2e8f0;
      background: #f8fafc;
      display: block;
    }

    .item-photo-preview {
      width: 130px;
      height: 130px;
      object-fit: cover;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      background: #f8fafc;
      display: block;
    }

    .action-stack .btn {
      border-radius: 8px;
      font-size: 0.75rem;
      padding: 0.24rem 0.5rem;
      font-weight: 600;
      line-height: 1.2;
    }

    .action-stack {
      gap: 8px;
      flex-wrap: wrap;
    }

    .table-footer {
      padding: 10px 18px;
      border-top: 1px solid #eef2f7;
      background: #f8fbff;
    }

    .pagination {
      gap: 4px;
    }

    .pagination .page-item .page-link {
      border-radius: 8px;
      border-color: #dbe4f0;
      color: #334155;
      font-weight: 600;
      min-width: 34px;
      text-align: center;
    }

    .pagination .page-item.active .page-link {
      background: linear-gradient(135deg, var(--brand), #3b82f6);
      border-color: transparent;
      color: #fff;
      box-shadow: 0 8px 14px rgba(15, 107, 255, .25);
    }

    .pagination .page-item.disabled .page-link {
      color: #94a3b8;
      background: #f8fafc;
      border-color: #e2e8f0;
    }

    .crud-form-card {
      border-radius: 18px;
      overflow: hidden;
    }

    .crud-form-card .card-header {
      background: linear-gradient(135deg, #f8fbff, #eef5ff);
      border-bottom: 1px solid #e6edf8;
      padding: 14px 18px;
    }

    .crud-form-card .card-header h3 {
      margin: 0;
      font-size: 1rem;
      font-weight: 800;
      color: #0f172a;
    }

    .crud-form-card .card-header p {
      margin: 4px 0 0;
      font-size: 0.8rem;
      color: #64748b;
    }

    .crud-form-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 14px 16px;
    }

    .crud-form-grid .full-width {
      grid-column: 1 / -1;
    }

    .crud-form-grid .form-group {
      margin-bottom: 0;
    }

    .crud-form-card .form-label {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      font-weight: 700;
      color: #64748b;
      margin-bottom: 0.45rem;
    }

    .crud-form-card .form-control,
    .crud-form-card .custom-select,
    .crud-form-card .select2-selection {
      border-radius: 12px !important;
      border: 1px solid #dbe4f0 !important;
      min-height: 40px;
      font-size: 0.9rem;
    }

    .crud-form-card textarea.form-control {
      min-height: 120px;
      resize: vertical;
    }

    .crud-form-card .form-control:focus,
    .crud-form-card .custom-select:focus {
      border-color: #86b7fe !important;
      box-shadow: 0 0 0 .2rem rgba(13,110,253,.12);
    }

    .crud-form-card .card-footer {
      background: #f8fbff;
      border-top: 1px solid #e6edf8;
      padding: 12px 18px;
    }

    @media (max-width: 991.98px) {
      .crud-form-grid {
        grid-template-columns: 1fr;
      }
    }

    .select2-container--bootstrap4 .select2-selection {
      border-radius: 12px !important;
      border: 1px solid #dbe4f0 !important;
      min-height: 40px;
    }

    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
      line-height: 38px;
      padding-left: 12px;
      color: #0f172a;
    }

    .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
      top: 4px;
      right: 6px;
    }

    .select2-container--bootstrap4.select2-container--focus .select2-selection {
      border-color: #86b7fe !important;
      box-shadow: 0 0 0 .2rem rgba(13,110,253,.15);
    }

    .select2-container--bootstrap4 .select2-dropdown {
      border: 1px solid #dbe4f0;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 14px 26px rgba(15, 23, 42, 0.12);
    }

    .select2-container--bootstrap4 .select2-search--dropdown .select2-search__field {
      border: 1px solid #dbe4f0;
      border-radius: 8px;
      height: 34px;
      padding: 0 10px;
    }

    .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] {
      background: linear-gradient(135deg, #0f6bff, #3b82f6);
    }

    .delete-modal .modal-dialog {
      max-width: 430px;
    }

    .delete-modal .modern-delete-modal {
      background: #ffffff;
      border: 0;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 24px 40px rgba(15, 23, 42, 0.2);
    }

    .delete-modal .delete-modal-form {
      margin: 0;
      width: 100%;
    }

    .delete-modal .modal-header {
      border-bottom: 0;
      padding: 16px 16px 0;
      align-items: flex-start;
    }

    .delete-modal .modal-body {
      padding: 10px 16px 8px;
    }

    .delete-modal .modal-footer {
      border-top: 0;
      padding: 0 16px 16px;
      gap: 8px;
      justify-content: flex-end;
    }

    .delete-modal-icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #fef2f2;
      color: #dc2626;
      font-size: 1.05rem;
    }

    .delete-modal-close {
      border: 0;
      background: transparent;
      color: #94a3b8;
      margin-left: auto;
      font-size: 1.35rem;
      line-height: 1;
      cursor: pointer;
      padding: 2px 4px;
    }

    .delete-modal-close:hover {
      color: #334155;
    }

    .delete-modal-title {
      margin: 0 0 6px;
      font-weight: 800;
      font-size: 1rem;
      color: #0f172a;
    }

    .delete-modal-text {
      margin: 0;
      color: #64748b;
      font-size: 0.9rem;
      line-height: 1.5;
    }

    .delete-modal .btn {
      border-radius: 10px;
      font-size: 0.84rem;
      font-weight: 700;
      min-width: 100px;
      padding: 0.46rem 0.8rem;
    }

    .delete-modal .btn-cancel {
      background: #f1f5f9;
      color: #334155;
      border: 1px solid #e2e8f0;
    }

    .delete-modal .btn-cancel:hover {
      background: #e2e8f0;
      color: #0f172a;
    }

    .delete-modal .btn-confirm {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: #fff;
      border: 0;
      box-shadow: 0 10px 20px rgba(220, 38, 38, 0.28);
    }

    .delete-modal .btn-confirm:hover {
      color: #fff;
      filter: brightness(0.95);
    }

    @media (max-width: 991.98px) {
      .main-header .admin-nav-search {
        width: 290px;
      }
    }

    @media (max-width: 767.98px) {
      .main-header.navbar {
        padding: 0.4rem 0.58rem;
      }

      .main-header .admin-nav-search-item {
        margin-right: 0.25rem;
      }

      .main-header .admin-nav-search {
        width: 190px;
      }

      .main-header .admin-nav-search .btn {
        padding: 0 0.68rem;
      }

      .main-header .admin-nav-search .btn span {
        display: none;
      }

      .content-header {
        padding-top: 0.62rem;
      }

      .content-header h1 {
        font-size: 1.28rem;
      }

      .table-shell .table-topbar,
      .table-shell .table-filter,
      .table-footer {
        padding-left: 12px;
        padding-right: 12px;
      }

      .table-shell .table-responsive {
        padding: 4px 8px 0;
      }
    }

    @media (max-width: 575.98px) {
      .main-header .admin-nav-search {
        width: 152px;
      }

      .main-header .admin-nav-search .form-control {
        font-size: 0.82rem;
      }
    }

  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  @include('layouts.component.navbar')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('layouts.component.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        @yield('header')
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @if (session('error') || session('error-unauthorized'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') ?? session('error-unauthorized') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif

        @yield('content')
      </div>
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
      <div>
        <strong>Copyright &copy; {{ date('Y') }} <a href="https://www.instagram.com/puskotjogja/">Perpustakaan Kota Yogyakarta</a>.</strong> All rights reserved.
      </div>
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset ('templates//plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset ('templates//plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset ('templates//dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="(../dist/js/demo.js')"></script>
@stack('scripts')
</body>
</html>
