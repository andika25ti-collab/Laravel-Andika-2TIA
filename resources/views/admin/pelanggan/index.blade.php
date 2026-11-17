@extends('layouts.admin.app')

@section('title', 'Data Pelanggan')

@section('content')
    <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h4 mb-1">Data Pelanggan</h1>
                    <p class="mb-0">Daftar pelanggan yang tersimpan.</p>
                </div>
                <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">Tambah Pelanggan</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif

            <div class="card border-0 shadow">
                <div class="card-body">
                    <form method="GET" action="{{ route('pelanggan.index') }}" class="row g-2 align-items-center mb-3">
                        <div class="col-md-3">
                            <label for="gender" class="visually-hidden">Filter gender</label>
                            <select name="gender" id="gender" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua gender</option>
                                <option value="Male" @selected(request('gender') === 'Male')>Male</option>
                                <option value="Female" @selected(request('gender') === 'Female')>Female</option>
                                <option value="Other" @selected(request('gender') === 'Other')>Other</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="search" class="visually-hidden">Cari pelanggan</label>
                            <div class="input-group">
                                <input type="search" name="search" id="search" class="form-control"
                                    value="{{ request('search') }}" placeholder="Cari nama atau email">
                                <button type="submit" class="btn btn-outline-secondary">Cari</button>
                                @if (request()->filled('search'))
                                    <a href="{{ request()->fullUrlWithQuery(['search' => null, 'page' => null]) }}"
                                        class="btn btn-outline-secondary">Hapus pencarian</a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Tanggal Lahir</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Telepon</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataPelanggan as $item)
                                    <tr>
                                        <td>{{ ($dataPelanggan->currentPage() - 1) * $dataPelanggan->perPage() + $loop->iteration }}</td>
                                        <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                                        <td>{{ $item->birthday ?? '-' }}</td>
                                        <td>{{ $item->gender ?? '-' }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->phone ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('pelanggan.show', $item) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                            <a href="{{ route('pelanggan.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                            <form action="{{ route('pelanggan.destroy', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data pelanggan ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data pelanggan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $dataPelanggan->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
    </div>
@endsection
