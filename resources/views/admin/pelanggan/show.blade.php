<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pelanggan</title>
    <link type="text/css" href="{{ asset('assets-admin/css/volt.css') }}" rel="stylesheet">
</head>

<body>
    <main class="content">
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h4 mb-0">Detail Pelanggan</h1>
                <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>

            <div class="card border-0 shadow">
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Nama</dt>
                        <dd class="col-sm-9">{{ $pelanggan->first_name }} {{ $pelanggan->last_name }}</dd>
                        <dt class="col-sm-3">Tanggal Lahir</dt>
                        <dd class="col-sm-9">{{ $pelanggan->birthday }}</dd>
                        <dt class="col-sm-3">Gender</dt>
                        <dd class="col-sm-9">{{ $pelanggan->gender }}</dd>
                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9">{{ $pelanggan->email }}</dd>
                        <dt class="col-sm-3">Telepon</dt>
                        <dd class="col-sm-9">{{ $pelanggan->phone }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
