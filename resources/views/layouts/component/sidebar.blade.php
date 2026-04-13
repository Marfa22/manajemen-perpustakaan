@php
    $user = auth()->user();
    $isSuperAdmin = $user?->isSuperAdmin() ?? false;
    $canDocuments = $user?->hasAccess(\App\Models\User::ACCESS_DOCUMENTS) ?? false;
    $canInventory = $user?->hasAccess(\App\Models\User::ACCESS_INVENTORY) ?? false;
    $canFoundItems = $user?->hasAccess(\App\Models\User::ACCESS_FOUND_ITEMS) ?? false;

    $dashboardMenu = (object) [
        "title" => "Dashboard Admin",
        "path" => "/dashboard-admin",
        "icon" => "fas fa-tachometer-alt",
    ];

    $inventoryMenus = [];

    if ($isSuperAdmin) {
        $inventoryMenus[] = (object) [
            "title" => "Kategori",
            "path" => "categories",
            "icon" => "fas fa-tags",
        ];
        $inventoryMenus[] = (object) [
            "title" => "Merek",
            "path" => "merek",
            "icon" => "fas fa-layer-group",
        ];
        $inventoryMenus[] = (object) [
            "title" => "Lokasi",
            "path" => "locations",
            "icon" => "fas fa-map-marker-alt",
        ];
    }

    if ($canDocuments) {
        $inventoryMenus[] = (object) [
            "title" => "Dokumen",
            "path" => "documents",
            "icon" => "fas fa-file-alt",
        ];
    }

    if ($canInventory) {
        $inventoryMenus[] = (object) [
            "title" => "Barang Kantor",
            "path" => "inventory",
            "icon" => "fas fa-boxes",
        ];
        $inventoryMenus[] = (object) [
            "title" => "Laporan Barang Kantor",
            "path" => "reports/inventory",
            "icon" => "fas fa-file-excel",
        ];
    }

    $foundItemMenus = [];
    if ($canFoundItems) {
        $foundItemMenus[] = (object) [
            "title" => "Barang Temuan",
            "path" => "products",
            "icon" => "fas fa-box-open",
        ];
    }

    $superAdminMenus = [];
    if ($isSuperAdmin) {
        $superAdminMenus[] = (object) [
            "title" => "Manajemen Admin",
            "path" => "admin/users",
            "icon" => "fas fa-user-shield",
        ];
    }
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="#" class="brand-link">
    <img src="{{ asset('images/favicon-32x32.png') }}" alt="Logo SIMARFA" class="brand-image elevation-1 sidebar-brand-logo">
    <span class="brand-text font-weight-light">SIMARFA</span>
  </a>

  <div class="sidebar d-flex flex-column h-100">
    <nav class="mt-3 flex-grow-1 sidebar-nav-scroll">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @php
            $dashboardPath = $dashboardMenu->path[0] !== '/' ? '/' . $dashboardMenu->path : $dashboardMenu->path;
            $dashboardActive = $dashboardPath === '/'
                ? request()->path() === '/'
                : request()->is(ltrim($dashboardPath, '/') . '*');
        @endphp
        <li class="nav-item">
          <a href="{{ request()->getBaseUrl() . $dashboardPath }}" class="nav-link {{ $dashboardActive ? 'active' : '' }}">
            <i class="nav-icon {{ $dashboardMenu->icon }}"></i>
            <p>{{ $dashboardMenu->title }}</p>
          </a>
        </li>

        @if (!empty($inventoryMenus))
          <li class="nav-header">Inventaris</li>
          @foreach ($inventoryMenus as $menu)
            @php
                $menuPath = $menu->path[0] !== '/' ? '/' . $menu->path : $menu->path;
                $isActive = $menuPath === '/'
                    ? request()->path() === '/'
                    : request()->is(ltrim($menuPath, '/') . '*');
            @endphp
            <li class="nav-item">
              <a href="{{ request()->getBaseUrl() . $menuPath }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                <i class="nav-icon {{ $menu->icon }}"></i>
                <p>{{ $menu->title }}</p>
              </a>
            </li>
          @endforeach
        @endif

        @if (!empty($foundItemMenus))
          <li class="nav-header">Barang Temuan</li>
          @foreach ($foundItemMenus as $menu)
            @php
                $menuPath = $menu->path[0] !== '/' ? '/' . $menu->path : $menu->path;
                $isActive = $menuPath === '/'
                    ? request()->path() === '/'
                    : request()->is(ltrim($menuPath, '/') . '*');
            @endphp
            <li class="nav-item">
              <a href="{{ request()->getBaseUrl() . $menuPath }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                <i class="nav-icon {{ $menu->icon }}"></i>
                <p>{{ $menu->title }}</p>
              </a>
            </li>
          @endforeach
        @endif

        @if (!empty($superAdminMenus))
          <li class="nav-header">Super Admin</li>
          @foreach ($superAdminMenus as $menu)
            @php
                $menuPath = $menu->path[0] !== '/' ? '/' . $menu->path : $menu->path;
                $isActive = $menuPath === '/'
                    ? request()->path() === '/'
                    : request()->is(ltrim($menuPath, '/') . '*');
            @endphp
            <li class="nav-item">
              <a href="{{ request()->getBaseUrl() . $menuPath }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                <i class="nav-icon {{ $menu->icon }}"></i>
                <p>{{ $menu->title }}</p>
              </a>
            </li>
          @endforeach
        @endif

        @if (empty($inventoryMenus) && empty($foundItemMenus) && empty($superAdminMenus))
          <li class="nav-item">
            <span class="nav-link text-muted" style="opacity: 0.8;">
              <i class="nav-icon fas fa-info-circle"></i>
              <p>Akses menu belum diatur</p>
            </span>
          </li>
        @endif
      </ul>
    </nav>

    @auth
      <div class="sidebar-logout-wrap">
        <ul class="nav nav-pills nav-sidebar flex-column">
          <li class="nav-item sidebar-logout">
            <form action="{{ request()->getBaseUrl() }}/logout" method="POST">
              @csrf
              <button type="submit" class="sidebar-logout-btn nav-link w-100 border-0 text-left d-flex align-items-center">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p class="logout-text mb-0">Logout</p>
              </button>
            </form>
          </li>
        </ul>
      </div>
    @endauth
  </div>
</aside>
