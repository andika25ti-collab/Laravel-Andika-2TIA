<footer class="bg-white rounded shadow p-4 mb-4 mt-4">
    <div class="row">
        <div class="col-12 col-md-6 mb-2 mb-md-0">
            <p class="mb-0 text-center text-lg-start">&copy; {{ date('Y') }} Laravel Andika &middot; 2 TI A</p>
        </div>
        <div class="col-12 col-md-6">
            <ul class="list-inline list-group-flush list-group-borderless text-center text-md-end mb-0">
                <li class="list-inline-item px-0 px-sm-2"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="list-inline-item px-0 px-sm-2"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
            </ul>
        </div>
    </div>
</footer>