<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $isEditing ? 'Edit' : 'Tambah' }} CV | CV Space</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('resume/assets/img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets-admin/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=DM+Sans:400,500,600,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('resume/css/crud.css') }}">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body>
    @php($profile = $biodata)
    @include('admin.partials.resume-nav')

    <main class="modern-main modern-form-main">
        <div class="modern-form-heading">
            <a class="back-link" href="{{ route('dashboard') }}#manage">
                <i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Kembali ke dashboard
            </a>
            <p class="eyebrow mt-4 mb-2">EDITOR CURRICULUM VITAE</p>
            <h1>{{ $isEditing ? 'Edit CV' : 'Buat CV' }}</h1>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger modern-alert" role="alert">
                <strong>Periksa kembali data yang dimasukkan.</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="content-card modern-form-card biodata-form" method="POST"
            action="{{ $isEditing ? route('biodata.update', $biodata) : route('biodata.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if ($isEditing)
                @method('PUT')
            @endif

            <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label" for="name">Nama lengkap <span class="text-danger">*</span></label>
                            <input class="form-control" id="name" name="name" type="text"
                                value="{{ old('name', $biodata->name) }}" maxlength="255" required autofocus>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="nim">NIM <span class="text-danger">*</span></label>
                            <input class="form-control" id="nim" name="nim" type="text"
                                value="{{ old('nim', $biodata->nim) }}" maxlength="30" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                            <input class="form-control" id="email" name="email" type="email"
                                value="{{ old('email', $biodata->email) }}" maxlength="255" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="phone">Nomor HP</label>
                            <input class="form-control" id="phone" name="phone" type="tel"
                                value="{{ old('phone', $biodata->phone) }}" maxlength="30">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="address">Alamat</label>
                            <textarea class="form-control" id="address" name="address" rows="3" maxlength="2000">{{ old('address', $biodata->address) }}</textarea>
                        </div>
                        <div class="col-12">
                            <hr class="my-2">
                            <h2 class="h3 mb-1">Isi Curriculum Vitae</h2>
                            <p class="text-muted mb-0">Isi bagian yang relevan dengan dirimu. Bagian kosong tidak akan menampilkan data contoh.</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="professional_title">Profesi atau gelar singkat</label>
                            <input class="form-control" id="professional_title" name="professional_title" type="text"
                                value="{{ old('professional_title', $biodata->professional_title) }}"
                                placeholder="Contoh: Mahasiswa Teknik Informatika" maxlength="255">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="summary">Tentang saya / ringkasan profil</label>
                            <textarea class="form-control" id="summary" name="summary" rows="4" maxlength="5000"
                                placeholder="Ceritakan profil, tujuan, dan hal yang ingin ditonjolkan.">{{ old('summary', $biodata->summary) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="experience">Pengalaman</label>
                            <textarea class="form-control" id="experience" name="experience" rows="5" maxlength="12000"
                                placeholder="Tulis satu pengalaman per baris, misalnya pengalaman organisasi, proyek, magang, atau pekerjaan.">{{ old('experience', $biodata->experience) }}</textarea>
                            <div class="form-text">Jika belum ada pengalaman kerja, isi proyek atau organisasi yang pernah diikuti.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="education">Pendidikan</label>
                            <textarea class="form-control" id="education" name="education" rows="4" maxlength="12000"
                                placeholder="Tulis satu riwayat pendidikan per baris, dari yang terbaru.">{{ old('education', $biodata->education) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="skills">Keahlian</label>
                            <textarea class="form-control" id="skills" name="skills" rows="4" maxlength="5000"
                                placeholder="Tulis satu keahlian per baris.">{{ old('skills', $biodata->skills) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="interests">Minat</label>
                            <textarea class="form-control" id="interests" name="interests" rows="4" maxlength="5000"
                                placeholder="Ceritakan minat atau bidang yang ingin dipelajari.">{{ old('interests', $biodata->interests) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="awards">Pencapaian dan sertifikat</label>
                            <textarea class="form-control" id="awards" name="awards" rows="4" maxlength="5000"
                                placeholder="Tulis satu pencapaian atau sertifikat per baris.">{{ old('awards', $biodata->awards) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="photo">Foto profil</label>
                            <input class="form-control" id="photo" name="photo" type="file" accept="image/*">
                            <div class="form-text">JPG, PNG, atau format gambar lain. Maksimal 2 MB.</div>
                            @if ($isEditing && $biodata->photo_path)
                                <div class="current-photo mt-3">
                                    <img class="biodata-avatar biodata-avatar-large"
                                        src="{{ asset('storage/'.$biodata->photo_path) }}" alt="Foto profil saat ini">
                                    <label class="form-check-label ms-2" for="remove_photo">
                                        <input class="form-check-input me-1" id="remove_photo" name="remove_photo"
                                            type="checkbox" value="1">
                                        Hapus foto saat ini
                                    </label>
                                </div>
                            @endif
                        </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-4">
                <button class="btn btn-primary btn-lg modern-button" type="submit">
                    {{ $isEditing ? 'Simpan perubahan' : 'Simpan CV' }}
                </button>
                <a class="btn btn-outline-secondary btn-lg" href="{{ route('dashboard') }}">Batal</a>
            </div>
        </form>
    </main>

    <script src="{{ asset('assets-admin/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
