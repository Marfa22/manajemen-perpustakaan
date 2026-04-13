<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>

    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ request()->getBaseUrl() }}/dashboard-admin" class="nav-link font-weight-bold">SIMARFA</a>
    </li>

    {{-- <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">Contact</a>
    </li> --}}
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">

    @auth
      @php
        $canSearch = auth()->user()->hasAccess(\App\Models\User::ACCESS_INVENTORY)
          || auth()->user()->hasAccess(\App\Models\User::ACCESS_FOUND_ITEMS);
      @endphp

      @if ($canSearch)
        <li class="nav-item admin-nav-search-item">
          <form action="{{ url('/admin/search#hasil-pencarian') }}" method="GET" class="admin-nav-search" role="search">
            <div class="input-group">
              <input
                type="text"
                name="q"
                value="{{ request()->query('q', '') }}"
                class="form-control"
                placeholder="Cari barang temuan & inventaris..."
                aria-label="Cari barang temuan dan inventaris"
              >
              <div class="input-group-append">
                <button class="btn" type="submit">
                  <i class="fas fa-search"></i><span>Cari</span>
                </button>
              </div>
            </div>
          </form>
        </li>
      @endif

      <li class="nav-item">
        <span class="nav-link">
          <i class="fas fa-user-circle mr-1"></i> {{ auth()->user()->name }}
        </span>
      </li>
    @else
      <li class="nav-item">
        <a href="{{ request()->getBaseUrl() }}/login" class="nav-link">
          <i class="fas fa-lock mr-1"></i> Login
        </a>
      </li>
    @endauth

  </ul>

</nav>
