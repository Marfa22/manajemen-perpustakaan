@extends('layouts.main')

@section('header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Dokumen</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ request()->getBaseUrl() }}/dashboard-admin">Beranda</a></li>
        <li class="breadcrumb-item active">Dokumen</li>
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

  <div class="row">
    <div class="col-12">
      <div class="table-shell">
        <div class="table-topbar">
          <div class="table-title">
            <h3>Data Dokumen</h3>
            <span class="table-subtitle">Kelola dokumen berdasarkan nama, kategori, tahun, deskripsi, dan file pendukung.</span>
          </div>
          <a href="{{ request()->getBaseUrl() }}/documents/create" class="btn btn-sm btn-primary">+ Tambah Dokumen</a>
        </div>

        <form class="table-filter" method="GET" action="{{ request()->getBaseUrl() }}/documents">
          <input
            type="text"
            name="q"
            value="{{ $q }}"
            class="form-control"
            style="max-width: 320px;"
            placeholder="Cari nama, kategori, tahun, deskripsi..."
          >
          <button class="btn btn-outline-primary" type="submit">Search</button>
          @if ($q !== '')
            <a href="{{ request()->getBaseUrl() }}/documents" class="btn btn-outline-secondary">Reset</a>
          @endif
        </form>

        <div class="table-responsive">
          <table class="table table-modern">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Dokumen</th>
                <th>Kategori</th>
                <th>Tahun</th>
                <th>Deskripsi</th>
                <th>File Pendukung</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($documents as $document)
                <tr>
                  <td>{{ ($documents->currentPage() - 1) * $documents->perPage() + $loop->iteration }}</td>
                  <td><div class="cell-primary">{{ $document->nama_dokumen }}</div></td>
                  <td>{{ $document->kategori }}</td>
                  <td>{{ $document->tahun }}</td>
                  <td>{{ \Illuminate\Support\Str::limit($document->deskripsi ?? '-', 80) }}</td>
                  <td>
                    @if ($document->file_path)
                      <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">
                        Lihat File
                      </a>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>
                    <div class="d-flex action-stack">
                      <a href="{{ request()->getBaseUrl() }}/documents/edit/{{ $document->id }}" class="btn btn-outline-primary">Ubah</a>
                      <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#modal-delete-{{ $document->id }}">
                        Hapus
                      </button>
                    </div>
                  </td>
                </tr>
                @include('pages.documents.delete-confirmation')
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted py-4">Data dokumen tidak ditemukan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="table-footer">
          {{ $documents->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </div>
@endsection
