@extends('layouts.main')

@section('header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Manajemen Admin</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ request()->getBaseUrl() }}/dashboard-admin">Beranda</a></li>
        <li class="breadcrumb-item active">Manajemen Admin</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  @if (session('success'))
    <script>
      Swal.fire({
        title: "Berhasil",
        text: "{{ session('success') }}",
        icon: "success",
      });
    </script>
  @endif

  @if (session('error'))
    <script>
      Swal.fire({
        title: "Tidak Dapat Diproses",
        text: "{{ session('error') }}",
        icon: "error",
      });
    </script>
  @endif

  <div class="row">
    <div class="col-12">
      <div class="table-shell">
        <div class="table-topbar">
          <div class="table-title">
            <h3>Data Admin</h3>
            <span class="table-subtitle">Kelola akun admin dan hak akses menu.</span>
          </div>
          <a href="{{ request()->getBaseUrl() }}/admin/users/create" class="btn btn-sm btn-primary">+ Tambah Admin</a>
        </div>

        <form class="table-filter" method="GET" action="{{ request()->getBaseUrl() }}/admin/users">
          <input
            type="text"
            name="q"
            value="{{ $q }}"
            class="form-control"
            style="max-width: 320px;"
            placeholder="Cari nama, username, atau email..."
          >
          <button class="btn btn-outline-primary" type="submit">Search</button>
          @if ($q !== '')
            <a href="{{ request()->getBaseUrl() }}/admin/users" class="btn btn-outline-secondary">Reset</a>
          @endif
        </form>

        <div class="table-responsive">
          <table class="table table-modern">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Hak Akses</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($users as $adminUser)
                @php
                  $userPermissions = $adminUser->normalizedPermissions();
                @endphp
                <tr>
                  <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                  <td>
                    <div class="cell-primary">{{ $adminUser->name }}</div>
                  </td>
                  <td>{{ $adminUser->username }}</td>
                  <td>{{ $adminUser->email ?? '-' }}</td>
                  <td>
                    @if ($adminUser->isSuperAdmin())
                      <span class="badge-soft success">Super Admin</span>
                    @else
                      <span class="badge-soft warning">Admin Menu</span>
                    @endif
                  </td>
                  <td>
                    @if ($adminUser->isSuperAdmin())
                      <span class="badge-soft success">Semua Menu</span>
                    @elseif (empty($userPermissions))
                      <span class="text-muted">Belum diatur</span>
                    @else
                      <div class="d-flex action-stack">
                        @foreach ($userPermissions as $permissionKey)
                          <span class="badge-soft">{{ $accessLabels[$permissionKey] ?? $permissionKey }}</span>
                        @endforeach
                      </div>
                    @endif
                  </td>
                  <td>
                    @if ($adminUser->isSuperAdmin())
                      <span class="text-muted">Permanen</span>
                    @else
                      <div class="d-flex action-stack">
                        <a href="{{ request()->getBaseUrl() }}/admin/users/{{ $adminUser->id }}/edit" class="btn btn-outline-primary">Ubah</a>
                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#modal-delete-user-{{ $adminUser->id }}">
                          Hapus
                        </button>
                      </div>
                    @endif
                  </td>
                </tr>

                @if (! $adminUser->isSuperAdmin())
                  <div class="modal fade delete-modal" id="modal-delete-user-{{ $adminUser->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content modern-delete-modal">
                        <form action="{{ request()->getBaseUrl() }}/admin/users/{{ $adminUser->id }}" method="POST" class="delete-modal-form">
                          @csrf
                          @method('DELETE')
                          <div class="modal-header">
                            <span class="delete-modal-icon">
                              <i class="fas fa-trash-alt"></i>
                            </span>
                            <button type="button" class="delete-modal-close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <h5 class="delete-modal-title">Hapus admin?</h5>
                            <p class="delete-modal-text">
                              Akun <strong>{{ $adminUser->name }}</strong> akan dihapus dari sistem.
                            </p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-cancel" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-confirm">
                              <i class="fas fa-trash-alt mr-1"></i> Hapus
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @endif
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted py-4">Data admin tidak ditemukan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="table-footer">
          {{ $users->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </div>
@endsection
