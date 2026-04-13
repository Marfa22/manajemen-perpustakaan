@extends('layouts.main')

@section('header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Ubah Admin</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ request()->getBaseUrl() }}/dashboard-admin">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ request()->getBaseUrl() }}/admin/users">Manajemen Admin</a></li>
        <li class="breadcrumb-item active">Ubah</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  @if (session('error'))
    <script>
      Swal.fire({
        title: "Tidak Dapat Diproses",
        text: "{{ session('error') }}",
        icon: "error",
      });
    </script>
  @endif

  @if ($errors->any())
    <script>
      Swal.fire({
        title: "Terjadi Kesalahan",
        text: "@foreach ($errors->all() as $error) {{ $error }} @endforeach",
        icon: "error",
      });
    </script>
  @endif

  <div class="row">
    <div class="col">
      <form action="{{ request()->getBaseUrl() }}/admin/users/{{ $user->id }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card crud-form-card">
          <div class="card-header">
            <h3>Form Admin</h3>
            <p>Perbarui profil admin dan hak akses menunya.</p>
          </div>
          <div class="card-body">
            <div class="crud-form-grid">
              <div class="form-group">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
                @error('username')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="Opsional">
                @error('email')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="custom-select @error('role') is-invalid @enderror" required>
                  <option value="{{ $roleMenuAdmin }}" {{ old('role', $user->role) === $roleMenuAdmin ? 'selected' : '' }}>Admin Menu</option>
                  <option value="{{ $roleSuperAdmin }}" {{ old('role', $user->role) === $roleSuperAdmin ? 'selected' : '' }}>Super Admin</option>
                </select>
                @error('role')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak diubah">
                @error('password')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Kosongkan jika tidak diubah">
              </div>

              <div class="form-group full-width" id="permission-group">
                <label class="form-label d-block">Hak Akses Menu</label>
                @php
                  $selectedPermissions = old('permissions', $user->normalizedPermissions());
                @endphp
                <div class="d-flex flex-wrap" style="gap: 12px;">
                  @foreach ($accessLabels as $accessKey => $accessLabel)
                    <label class="mb-0" style="display: inline-flex; align-items: center; gap: 6px;">
                      <input
                        type="checkbox"
                        name="permissions[]"
                        value="{{ $accessKey }}"
                        {{ in_array($accessKey, $selectedPermissions, true) ? 'checked' : '' }}
                      >
                      <span>{{ $accessLabel }}</span>
                    </label>
                  @endforeach
                </div>
                @error('permissions')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
                @error('permissions.*')
                  <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="d-flex justify-content-end">
              <a href="{{ request()->getBaseUrl() }}/admin/users" class="btn btn-sm btn-outline-secondary mr-2">Batal</a>
              <button type="submit" class="btn btn-sm btn-primary">Simpan Perubahan</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var roleInput = document.getElementById('role');
      var permissionGroup = document.getElementById('permission-group');
      var superAdminRole = @json($roleSuperAdmin);

      function syncPermissionVisibility() {
        if (!roleInput || !permissionGroup) {
          return;
        }

        permissionGroup.style.display = roleInput.value === superAdminRole ? 'none' : 'block';
      }

      if (roleInput) {
        roleInput.addEventListener('change', syncPermissionVisibility);
      }

      syncPermissionVisibility();
    });
  </script>
@endpush
