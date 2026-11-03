<nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark ps-0 pe-2 pb-0">
    <div class="container-fluid px-0">
        <h1 class="h5 mb-0">@yield('title', 'Administrasi')</h1>
        <div class="d-flex align-items-center gap-3">
            <span class="text-gray-700">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">Keluar</button>
            </form>
        </div>
    </div>
</nav>