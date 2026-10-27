@php
    $navigationPhoto = $profile?->photo_path
        ? asset('storage/'.$profile->photo_path)
        : asset('resume/assets/img/profile.jpg');
    $dashboardUrl = route('dashboard');
@endphp

<aside class="modern-sidebar">
    <a class="sidebar-brand" href="{{ $dashboardUrl }}">
        <span class="brand-mark"><i class="fa-solid fa-layer-group" aria-hidden="true"></i></span>
        <span>CV<span class="brand-accent">Space</span></span>
    </a>

    <div class="sidebar-profile">
        <img src="{{ $navigationPhoto }}" alt="Foto {{ $profile?->name ?? 'profil' }}">
        <div>
            <strong>{{ $profile?->name ?? 'Profil CV' }}</strong>
            <span>{{ $profile?->professional_title ?? 'Curriculum Vitae' }}</span>
        </div>
    </div>

    <p class="sidebar-label">MENU UTAMA</p>
    <nav class="sidebar-links" aria-label="Navigasi CV">
        <a href="{{ $dashboardUrl }}#overview"><i class="fa-solid fa-house" aria-hidden="true"></i><span>Overview</span></a>
        <a href="{{ $dashboardUrl }}#experience"><i class="fa-solid fa-briefcase" aria-hidden="true"></i><span>Pengalaman</span></a>
        <a href="{{ $dashboardUrl }}#education"><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i><span>Pendidikan</span></a>
        <a href="{{ $dashboardUrl }}#skills"><i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i><span>Keahlian</span></a>
        <a href="{{ $dashboardUrl }}#interests"><i class="fa-solid fa-heart" aria-hidden="true"></i><span>Minat</span></a>
        <a href="{{ $dashboardUrl }}#awards"><i class="fa-solid fa-award" aria-hidden="true"></i><span>Pencapaian</span></a>
        <a href="{{ $dashboardUrl }}#manage"><i class="fa-solid fa-sliders" aria-hidden="true"></i><span>Kelola CV</span></a>
        <a href="{{ route('pelanggan.index') }}"><i class="fa-solid fa-users" aria-hidden="true"></i><span>Data Pelanggan</span></a>
    </nav>

    <div class="sidebar-bottom">
        <span class="sidebar-status-dot"></span>
        <span>Personal CV dashboard</span>
    </div>
</aside>
