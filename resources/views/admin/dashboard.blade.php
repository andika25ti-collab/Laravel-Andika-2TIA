<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $cv?->name ?? 'Curriculum Vitae' }} | CV Space</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('resume/assets/img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,600,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('resume/css/crud.css') }}">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body>
    @php
        $profile = $cv;
        $experienceItems = $cv?->experience ? array_filter(preg_split('/\r\n|\r|\n/', trim($cv->experience))) : [];
        $educationItems = $cv?->education ? array_filter(preg_split('/\r\n|\r|\n/', trim($cv->education))) : [];
        $skillItems = $cv?->skills ? array_filter(preg_split('/\r\n|\r|\n/', trim($cv->skills))) : [];
        $awardItems = $cv?->awards ? array_filter(preg_split('/\r\n|\r|\n/', trim($cv->awards))) : [];
        $profilePhoto = $cv?->photo_path
            ? asset('storage/'.$cv->photo_path)
            : asset('resume/assets/img/profile.jpg');
    @endphp

    @include('admin.partials.resume-nav')

    <main class="modern-main">
        <header class="dashboard-header" id="overview">
            <div>
                <span class="eyebrow">PERSONAL WORKSPACE</span>
                <h1>Halo, {{ $cv?->name ?? 'teman' }} <span aria-hidden="true">👋</span></h1>
                <p>Ringkasan perjalanan, pengalaman, dan keahlianmu dalam satu tempat.</p>
            </div>
            <div class="header-actions">
                <span class="today-label"><i class="fa-regular fa-calendar" aria-hidden="true"></i> Curriculum Vitae</span>
                @if ($cv)
                    <a class="btn btn-primary modern-button" href="{{ route('resume') }}">
                        <i class="fa-solid fa-eye me-2" aria-hidden="true"></i>Lihat CV
                    </a>
                    <a class="header-avatar" href="{{ route('biodata.edit', $cv) }}" aria-label="Edit profil CV">
                        <img src="{{ $profilePhoto }}" alt="Foto {{ $cv->name }}">
                    </a>
                @endif
            </div>
        </header>

        @if (session('success'))
            <div class="alert alert-success modern-alert" role="status">{{ session('success') }}</div>
        @endif

        <section class="summary-grid" aria-label="Ringkasan CV">
            <article class="summary-card">
                <span class="summary-icon icon-blue"><i class="fa-solid fa-briefcase" aria-hidden="true"></i></span>
                <span class="summary-label">Pengalaman</span>
                <strong>{{ count($experienceItems) }}</strong>
                <span class="summary-caption">pengalaman / proyek</span>
            </article>
            <article class="summary-card">
                <span class="summary-icon icon-orange"><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i></span>
                <span class="summary-label">Pendidikan</span>
                <strong>{{ count($educationItems) }}</strong>
                <span class="summary-caption">riwayat pendidikan</span>
            </article>
            <article class="summary-card">
                <span class="summary-icon icon-teal"><i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i></span>
                <span class="summary-label">Keahlian</span>
                <strong>{{ count($skillItems) }}</strong>
                <span class="summary-caption">keahlian dicatat</span>
            </article>
            <article class="summary-card">
                <span class="summary-icon icon-green"><i class="fa-solid fa-award" aria-hidden="true"></i></span>
                <span class="summary-label">Pencapaian</span>
                <strong>{{ count($awardItems) }}</strong>
                <span class="summary-caption">pencapaian / sertifikat</span>
            </article>
        </section>

        <section class="workspace-grid">
            <div class="workspace-column">
                <article class="content-card profile-card">
                    <div class="card-heading">
                        <div>
                            <span class="eyebrow">PROFIL SAYA</span>
                            <h2>Ringkasan Profil</h2>
                        </div>
                        @if ($cv)
                            <a class="icon-action" href="{{ route('biodata.edit', $cv) }}" aria-label="Edit CV">
                                <i class="fa-solid fa-pen" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>
                    @if ($cv)
                        <div class="profile-hero">
                            <img class="profile-photo" src="{{ $profilePhoto }}" alt="Foto {{ $cv->name }}">
                            <div>
                                <h3>{{ $cv->name }}</h3>
                                <p>{{ $cv->professional_title ?: 'Curriculum Vitae' }}</p>
                                <span class="profile-badge"><i class="fa-solid fa-id-card me-1" aria-hidden="true"></i> NIM {{ $cv->nim }}</span>
                            </div>
                        </div>
                        <p class="profile-summary-text">{{ $cv->summary ?: 'Tambahkan ringkasan tentang dirimu melalui tombol edit profil.' }}</p>
                        <div class="contact-list">
                            @if ($cv->email)
                                <a href="mailto:{{ $cv->email }}"><i class="fa-regular fa-envelope" aria-hidden="true"></i>{{ $cv->email }}</a>
                            @endif
                            @if ($cv->phone)
                                <a href="tel:{{ $cv->phone }}"><i class="fa-solid fa-phone" aria-hidden="true"></i>{{ $cv->phone }}</a>
                            @endif
                            @if ($cv->address)
                                <span><i class="fa-solid fa-location-dot" aria-hidden="true"></i>{{ $cv->address }}</span>
                            @endif
                        </div>
                    @else
                        <div class="empty-card-state">
                            <span class="empty-card-icon"><i class="fa-solid fa-user-plus" aria-hidden="true"></i></span>
                            <h3>Mulai bangun CV-mu</h3>
                            <p>Lengkapi profil, pengalaman, pendidikan, dan keahlian untuk mengisi dashboard ini.</p>
                            <a class="btn btn-primary modern-button" href="{{ route('biodata.create') }}">Buat CV sekarang</a>
                        </div>
                    @endif
                </article>

                <article class="content-card" id="experience">
                    <div class="card-heading">
                        <div>
                            <span class="eyebrow">PERJALANAN</span>
                            <h2>Pengalaman</h2>
                        </div>
                        <span class="card-count">{{ count($experienceItems) }}</span>
                    </div>
                    @forelse ($experienceItems as $item)
                        <div class="timeline-item">
                            <span class="timeline-dot dot-blue"></span>
                            <p>{{ $item }}</p>
                        </div>
                    @empty
                        <p class="muted-copy">Pengalaman, proyek, atau kegiatan organisasi akan tampil di sini setelah ditambahkan.</p>
                    @endforelse
                </article>

                <article class="content-card" id="education">
                    <div class="card-heading">
                        <div>
                            <span class="eyebrow">LATAR BELAKANG</span>
                            <h2>Pendidikan</h2>
                        </div>
                        <span class="card-count">{{ count($educationItems) }}</span>
                    </div>
                    @forelse ($educationItems as $item)
                        <div class="timeline-item">
                            <span class="timeline-dot dot-orange"></span>
                            <p>{{ $item }}</p>
                        </div>
                    @empty
                        <p class="muted-copy">Riwayat pendidikan akan tampil di sini setelah ditambahkan.</p>
                    @endforelse
                </article>
            </div>

            <div class="workspace-column">
                <article class="content-card" id="skills">
                    <div class="card-heading">
                        <div>
                            <span class="eyebrow">YANG SAYA KUASAI</span>
                            <h2>Keahlian</h2>
                        </div>
                        <i class="fa-solid fa-wand-magic-sparkles heading-icon" aria-hidden="true"></i>
                    </div>
                    @forelse ($skillItems as $skill)
                        <span class="skill-chip">{{ $skill }}</span>
                    @empty
                        <p class="muted-copy">Keahlian dan tools yang dikuasai akan tampil di sini setelah ditambahkan.</p>
                    @endforelse
                </article>

                <article class="content-card" id="interests">
                    <div class="card-heading">
                        <div>
                            <span class="eyebrow">DI LUAR KEGIATAN</span>
                            <h2>Minat</h2>
                        </div>
                        <i class="fa-solid fa-heart heading-icon icon-heart" aria-hidden="true"></i>
                    </div>
                    @if ($cv?->interests)
                        <p class="content-copy">{{ $cv->interests }}</p>
                    @else
                        <p class="muted-copy">Minat dan bidang yang ingin dipelajari akan tampil di sini setelah ditambahkan.</p>
                    @endif
                </article>

                <article class="content-card" id="awards">
                    <div class="card-heading">
                        <div>
                            <span class="eyebrow">PENCAPAIAN</span>
                            <h2>Sertifikat &amp; Penghargaan</h2>
                        </div>
                        <i class="fa-solid fa-award heading-icon icon-award" aria-hidden="true"></i>
                    </div>
                    @forelse ($awardItems as $award)
                        <div class="achievement-item">
                            <span class="achievement-icon"><i class="fa-solid fa-check" aria-hidden="true"></i></span>
                            <p>{{ $award }}</p>
                        </div>
                    @empty
                        <p class="muted-copy">Sertifikat dan pencapaian akan tampil di sini setelah ditambahkan.</p>
                    @endforelse
                </article>
            </div>
        </section>

        <section class="content-card manage-card" id="manage">
            <div class="card-heading">
                <div>
                    <span class="eyebrow">PENGELOLAAN DATA</span>
                    <h2>Kelola CV</h2>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @if ($cv)
                        <a class="btn btn-outline-primary" href="{{ route('resume') }}">
                            <i class="fa-solid fa-eye me-2" aria-hidden="true"></i>Lihat CV
                        </a>
                    @endif
                    <a class="btn btn-primary modern-button" href="{{ route('biodata.create') }}">
                        <i class="fa-solid fa-plus me-2" aria-hidden="true"></i>Tambah CV
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table modern-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Profil</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Email</th>
                            <th scope="col">Nomor HP</th>
                            <th scope="col" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($biodataEntries as $entry)
                            <tr>
                                <td>
                                    <div class="table-profile">
                                        <img src="{{ $entry->photo_path ? asset('storage/'.$entry->photo_path) : asset('resume/assets/img/profile.jpg') }}" alt="Foto {{ $entry->name }}">
                                        <div><strong>{{ $entry->name }}</strong><span>{{ $entry->professional_title ?: 'Curriculum Vitae' }}</span></div>
                                    </div>
                                </td>
                                <td>{{ $entry->nim }}</td>
                                <td>{{ $entry->email }}</td>
                                <td>{{ $entry->phone ?: '—' }}</td>
                                <td class="text-end text-nowrap">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('biodata.resume', $entry) }}">Lihat CV</a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('biodata.edit', $entry) }}">Edit</a>
                                    <form class="d-inline" method="POST" action="{{ route('biodata.destroy', $entry) }}" onsubmit="return confirm('Yakin menghapus CV ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-center text-muted">Belum ada data CV.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $biodataEntries->links('pagination::bootstrap-5') }}</div>
        </section>

        <footer class="modern-footer">CV Space <span>·</span> Personal CV workspace</footer>
    </main>

    <script src="{{ asset('assets-admin/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
