<aside class="cv-sidebar">
    <div class="cv-sidebar-inner">
        <div class="cv-portrait-wrap">
            <img class="cv-portrait"
                src="{{ $cv?->photo_path ? asset('storage/'.$cv->photo_path) : asset('resume/assets/img/profile.jpg') }}"
                alt="Foto {{ $cv?->name ?? 'profil CV' }}">
        </div>

        <section class="cv-sidebar-section">
            <h2 class="cv-side-heading"><span>Data Diri</span></h2>
            @if ($cv)
                <dl class="cv-personal-details">
                    <div>
                        <dt>Profesi</dt>
                        <dd>{{ $cv->professional_title ?: 'Mahasiswa' }}</dd>
                    </div>
                    <div>
                        <dt>NIM</dt>
                        <dd>{{ $cv->nim }}</dd>
                    </div>
                    @if ($cv->address)
                        <div>
                            <dt>Alamat</dt>
                            <dd>{{ $cv->address }}</dd>
                        </div>
                    @endif
                </dl>
            @else
                <p class="cv-sidebar-empty">Tambahkan profil CV untuk menampilkan data diri.</p>
            @endif
        </section>

        <section class="cv-sidebar-section">
            <h2 class="cv-side-heading"><span>Kontak</span></h2>
            <ul class="cv-contact-list">
                @if ($cv?->phone)
                    <li>
                        <i class="fa-solid fa-phone" aria-hidden="true"></i>
                        <a href="tel:{{ $cv->phone }}">{{ $cv->phone }}</a>
                    </li>
                @endif
                @if ($cv?->email)
                    <li>
                        <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                        <a href="mailto:{{ $cv->email }}">{{ $cv->email }}</a>
                    </li>
                @endif
                @if ($cv?->address)
                    <li>
                        <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                        <span>{{ $cv->address }}</span>
                    </li>
                @endif
                @if (! $cv?->phone && ! $cv?->email && ! $cv?->address)
                    <li><span>Kontak belum ditambahkan.</span></li>
                @endif
            </ul>
        </section>

        <section class="cv-sidebar-section cv-sidebar-skills">
            <h2 class="cv-side-heading"><span>Keahlian</span></h2>
            @if ($cv?->skills)
                <ul class="cv-side-skill-list">
                    @foreach (array_filter(preg_split('/\r\n|\r|\n/', trim($cv->skills))) as $skill)
                        <li>{{ $skill }}</li>
                    @endforeach
                </ul>
            @else
                <p class="cv-sidebar-empty">Keahlian belum ditambahkan.</p>
            @endif
        </section>

        <div class="cv-sidebar-actions">
            @if ($cv)
                <a class="cv-edit-link" href="{{ route('biodata.edit', $cv) }}">
                    <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> Edit CV
                </a>
            @else
                <a class="cv-edit-link" href="{{ route('biodata.create') }}">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Buat CV
                </a>
            @endif
            <a class="cv-dashboard-link" href="{{ route('dashboard') }}">
                <i class="fa-solid fa-table-columns" aria-hidden="true"></i> Dashboard
            </a>
        </div>
    </div>
</aside>
