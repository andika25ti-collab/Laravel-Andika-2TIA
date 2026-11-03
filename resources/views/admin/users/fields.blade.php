@if (session('success'))
    <div class="alert alert-success" role="status">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <p class="mb-1">Periksa kembali data yang dimasukkan:</p>
        <ul class="mb-0">
            @foreach ($errors->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" id="name" name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $user->name) }}" autocomplete="name" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $user->email) }}" autocomplete="email" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="password" class="form-label">Password {{ $editing ? '(kosongkan jika tidak diubah)' : '' }}</label>
        <input type="password" id="password" name="password"
            class="form-control @error('password') is-invalid @enderror"
            autocomplete="new-password"
            {{ $editing ? '' : 'required' }}>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation"
            class="form-control" autocomplete="new-password" {{ $editing ? '' : 'required' }}>
    </div>
    <div class="col-12 mb-3">
        <div class="form-check">
            <input type="checkbox" id="is_admin" name="is_admin" value="1"
                class="form-check-input @error('is_admin') is-invalid @enderror"
                @checked(old('is_admin', $user->is_admin))>
            <label for="is_admin" class="form-check-label">Berikan akses admin</label>
            @error('is_admin')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
