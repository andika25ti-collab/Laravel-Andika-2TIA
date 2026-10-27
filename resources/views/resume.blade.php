<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $cv?->name ?? 'Curriculum Vitae' }} | Curriculum Vitae</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('resume/assets/img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,600,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('resume/css/crud.css') }}">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="cv-showcase-page">
    @php
        $experienceItems = $cv?->experience ? array_filter(preg_split('/\r\n|\r|\n/', trim($cv->experience))) : [];
        $educationItems = $cv?->education ? array_filter(preg_split('/\r\n|\r|\n/', trim($cv->education))) : [];
        $skillItems = $cv?->skills ? array_filter(preg_split('/\r\n|\r|\n/', trim($cv->skills))) : [];
        $awardItems = $cv?->awards ? array_filter(preg_split('/\r\n|\r|\n/', trim($cv->awards))) : [];
    @endphp

    @include('partials.resume-public-nav')

    <main class="cv-showcase-main">
        <header class="cv-showcase-header" id="about">
            @if ($cv)
                <h1>{{ $cv->name }}</h1>
                <p class="cv-professional-title">{{ $cv->professional_title ?: 'Curriculum Vitae' }}</p>
            @else
                <h1>Curriculum Vitae</h1>
                <p class="cv-professional-title">Profil profesional</p>
            @endif
            <div class="cv-header-actions">
                @if (session('success'))
                    <span class="cv-save-message" role="status">{{ session('success') }}</span>
                @endif
                @if ($cv)
                    <a class="cv-header-edit" href="{{ route('biodata.edit', $cv) }}">
                        <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> Edit CV
                    </a>
                @else
                    <a class="cv-header-edit" href="{{ route('biodata.create') }}">
                        <i class="fa-solid fa-plus" aria-hidden="true"></i> Buat CV
                    </a>
                @endif
            </div>
        </header>

        <section class="cv-main-section" id="summary">
            <h2 class="cv-section-heading">Tentang Saya</h2>
            <div class="cv-section-content">
                <p class="cv-paragraph">{{ $cv?->summary ?: 'Ringkasan profil belum ditambahkan.' }}</p>
            </div>
        </section>

        <section class="cv-main-section" id="education">
            <h2 class="cv-section-heading">Pendidikan</h2>
            <div class="cv-section-content">
                @forelse ($educationItems as $item)
                    <div class="cv-detail-row">
                        <i class="fa-solid fa-graduation-cap cv-inline-icon" aria-hidden="true"></i>
                        <p>{{ $item }}</p>
                    </div>
                @empty
                    <p class="cv-empty-copy">Riwayat pendidikan belum ditambahkan.</p>
                @endforelse
            </div>
        </section>

        <section class="cv-main-section" id="experience">
            <h2 class="cv-section-heading">Pengalaman</h2>
            <div class="cv-section-content">
                @forelse ($experienceItems as $item)
                    <div class="cv-detail-row">
                        <i class="fa-solid fa-briefcase cv-inline-icon" aria-hidden="true"></i>
                        <p>{{ $item }}</p>
                    </div>
                @empty
                    <p class="cv-empty-copy">Pengalaman kerja, proyek, atau organisasi belum ditambahkan.</p>
                @endforelse
            </div>
        </section>

        <section class="cv-main-section" id="skills">
            <h2 class="cv-section-heading">Kemampuan</h2>
            <div class="cv-section-content">
                @forelse ($skillItems as $skill)
                    <div class="cv-detail-row cv-skill-row">
                        <i class="fa-solid fa-check cv-inline-icon" aria-hidden="true"></i>
                        <p>{{ $skill }}</p>
                    </div>
                @empty
                    <p class="cv-empty-copy">Keahlian belum ditambahkan.</p>
                @endforelse
            </div>
        </section>

        @if ($cv?->interests || $awardItems)
            <section class="cv-main-section cv-additional-section" id="additional">
                <h2 class="cv-section-heading">Informasi Tambahan</h2>
                <div class="cv-section-content">
                    @if ($cv?->interests)
                        <h3 class="cv-subsection-heading">Minat</h3>
                        <p class="cv-paragraph">{{ $cv->interests }}</p>
                    @endif
                    @if ($awardItems)
                        <h3 class="cv-subsection-heading">Pencapaian &amp; Sertifikat</h3>
                        @foreach ($awardItems as $award)
                            <div class="cv-detail-row">
                                <i class="fa-solid fa-award cv-inline-icon" aria-hidden="true"></i>
                                <p>{{ $award }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>
        @endif

        <footer class="cv-page-footer">
            <span>Curriculum Vitae</span>
            <a href="{{ route('dashboard') }}">Kelola data CV</a>
        </footer>
    </main>
</body>
</html>
