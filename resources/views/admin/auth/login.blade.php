<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin | Laravel Andika</title>
    <link rel="stylesheet" href="{{ asset('assets-admin/css/volt.css') }}">
</head>
<body>
    <main class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card border-0 shadow col-12 col-md-8 col-lg-5">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 mb-2">Login Admin</h1>
                <p class="text-gray-600 mb-4">Masuk untuk mengelola dashboard.</p>

                <form action="{{ route('login.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            autocomplete="username" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            autocomplete="current-password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-check mb-4">
                        <input type="checkbox" id="remember" name="remember" value="1" class="form-check-input">
                        <label for="remember" class="form-check-label">Ingat saya</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Masuk</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
