<?php

namespace Tests\Feature;

use App\Models\Biodata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BiodataCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_lists_biodata_in_the_resume_template(): void
    {
        Biodata::query()->create([
            'name' => 'Andika',
            'nim' => '10210001',
            'email' => 'andika@example.com',
        ]);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Pengalaman')
            ->assertSee('Kelola CV')
            ->assertSee('Curriculum Vitae')
            ->assertSee('Andika')
            ->assertSee('10210001')
            ->assertSee('Lihat CV')
            ->assertSee(route('biodata.resume', Biodata::query()->first()));
    }

    public function test_homepage_displays_the_saved_cv_in_the_resume_template(): void
    {
        Biodata::query()->create([
            'name' => 'Andika',
            'nim' => '10210001',
            'email' => 'andika@example.com',
            'professional_title' => 'Mahasiswa Teknik Informatika',
            'experience' => 'Proyek aplikasi biodata',
            'education' => 'Teknik Informatika',
        ]);

        $this->get(route('resume'))
            ->assertOk()
            ->assertSee('Andika')
            ->assertSee('Mahasiswa Teknik Informatika')
            ->assertSee('Proyek aplikasi biodata')
            ->assertSee('Teknik Informatika')
            ->assertSee('Data Diri')
            ->assertSee('Kontak')
            ->assertSee('Dashboard')
            ->assertSee(route('dashboard'));
    }

    public function test_dashboard_view_cv_button_opens_the_selected_cv(): void
    {
        $selected = Biodata::query()->create([
            'name' => 'CV Terpilih',
            'nim' => '10210001',
            'email' => 'terpilih@example.com',
            'summary' => 'Profil yang dipilih untuk ditampilkan.',
        ]);
        Biodata::query()->create([
            'name' => 'CV Terbaru',
            'nim' => '10210002',
            'email' => 'terbaru@example.com',
        ]);

        $this->get(route('biodata.resume', $selected))
            ->assertOk()
            ->assertSee('CV Terpilih')
            ->assertSee('Profil yang dipilih untuk ditampilkan.')
            ->assertDontSee('CV Terbaru');
    }

    public function test_biodata_can_be_created_updated_and_deleted_with_a_profile_photo(): void
    {
        Storage::fake('public');

        $this->post(route('biodata.store'), [
            'name' => 'Andika',
            'nim' => '10210001',
            'email' => 'andika@example.com',
            'phone' => '08123456789',
            'address' => 'Bandung',
            'professional_title' => 'Mahasiswa Teknik Informatika',
            'summary' => 'Tertarik pada pengembangan aplikasi.',
            'experience' => "Pengurus organisasi kampus\nMembuat aplikasi inventaris",
            'education' => 'Teknik Informatika',
            'skills' => "Laravel\nPHP",
            'interests' => 'Pengembangan web',
            'awards' => 'Sertifikat Laravel',
            'photo' => UploadedFile::fake()->image('andika.jpg'),
        ])->assertRedirect(route('dashboard'));

        $biodata = Biodata::query()->firstOrFail();
        $originalPhoto = $biodata->photo_path;

        $this->assertNotNull($originalPhoto);
        Storage::disk('public')->assertExists($originalPhoto);
        $this->assertSame("Laravel\nPHP", $biodata->skills);
        $this->assertSame('Sertifikat Laravel', $biodata->awards);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Mahasiswa Teknik Informatika')
            ->assertSee('Pengurus organisasi kampus')
            ->assertSee('Teknik Informatika')
            ->assertSee('Laravel');

        $this->put(route('biodata.update', $biodata), [
            'name' => 'Andika Pratama',
            'nim' => '10210001',
            'email' => 'andika.pratama@example.com',
            'phone' => '08123456789',
            'address' => 'Jakarta',
            'professional_title' => 'Mahasiswa Informatika',
            'summary' => 'Suka membangun aplikasi web.',
            'experience' => 'Proyek aplikasi biodata',
            'education' => 'Teknik Informatika',
            'skills' => "PHP\nLaravel\nMySQL",
            'interests' => 'Teknologi web',
            'awards' => 'Sertifikat pengembangan web',
            'photo' => UploadedFile::fake()->image('andika-baru.jpg'),
        ])->assertRedirect(route('dashboard'));

        $biodata->refresh();
        $this->assertSame('Andika Pratama', $biodata->name);
        $this->assertSame('Proyek aplikasi biodata', $biodata->experience);
        $this->assertNotSame($originalPhoto, $biodata->photo_path);
        Storage::disk('public')->assertMissing($originalPhoto);
        Storage::disk('public')->assertExists($biodata->photo_path);

        $this->delete(route('biodata.destroy', $biodata))
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseMissing('Biodata_1021', ['id' => $biodata->id]);
        Storage::disk('public')->assertMissing($biodata->photo_path);
    }

    public function test_biodata_rejects_duplicate_nim_and_invalid_profile_photos(): void
    {
        Biodata::query()->create([
            'name' => 'Andika',
            'nim' => '10210001',
            'email' => 'andika@example.com',
        ]);

        $this->from(route('biodata.create'))
            ->post(route('biodata.store'), [
                'name' => 'Pengguna lain',
                'nim' => '10210001',
                'email' => 'lain@example.com',
                'photo' => UploadedFile::fake()->create('dokumen.pdf', 20, 'application/pdf'),
            ])
            ->assertRedirect(route('biodata.create'))
            ->assertSessionHasErrors(['nim', 'photo']);

        $this->assertDatabaseCount('Biodata_1021', 1);
    }
}
