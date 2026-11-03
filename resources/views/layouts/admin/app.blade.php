<!DOCTYPE html>
<html lang="id">
<head>
    {{-- start css --}}
    @include('layouts.admin.css')
    {{-- end css --}}
</head>
<body>
    {{-- start sidebar --}}
    @include('layouts.admin.sidebar')
    {{-- end sidebar --}}

    <main class="content">

        {{-- start header --}}
        @include('layouts.admin.header')
        {{-- end header --}}

        {{-- start main content --}}
        @yield('content')
        {{-- end main content --}}

        {{-- start footer --}}
        @include('layouts.admin.footer')
        {{-- end footer --}}

    </main>

    {{-- start js --}}
    @include('layouts.admin.js')
    {{-- end js --}}
</body>
</html>