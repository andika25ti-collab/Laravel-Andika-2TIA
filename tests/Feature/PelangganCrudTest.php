<?php

namespace Tests\Feature;

use App\Models\Pelanggan;
use App\Models\User;
use Database\Seeders\CreatePelangganDummy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class PelangganCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_saved_pelanggan(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        Pelanggan::create($this->validPelanggan());

        $this->get(route('pelanggan.index'))
            ->assertOk()
            ->assertSee('Andika')
            ->assertSee('andika@example.com')
            ->assertSee('name="gender"', false)
            ->assertSee('name="search"', false)
            ->assertSee('id="sidebarMenu"', false)
            ->assertSee('nav-item active', false);
    }

    public function test_index_filters_by_gender_and_searches_first_name_last_name_and_email(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        Pelanggan::create($this->validPelanggan([
            'first_name' => 'Needle',
            'email' => 'first@example.com',
        ]));
        Pelanggan::create($this->validPelanggan([
            'first_name' => 'LastMatch',
            'last_name' => 'Needle',
            'gender' => 'Female',
            'email' => 'last@example.com',
        ]));
        Pelanggan::create($this->validPelanggan([
            'first_name' => 'EmailMatch',
            'gender' => 'Female',
            'email' => 'needle@example.com',
        ]));
        Pelanggan::create($this->validPelanggan([
            'first_name' => 'MaleMatch',
            'gender' => 'Male',
            'email' => 'male@example.com',
        ]));
        Pelanggan::create($this->validPelanggan([
            'first_name' => 'NoMatch',
            'gender' => 'Female',
            'email' => 'other@example.com',
        ]));

        $this->get(route('pelanggan.index', ['gender' => 'Female', 'search' => 'Needle']))
            ->assertOk()
            ->assertSee('Needle')
            ->assertSee('LastMatch')
            ->assertSee('EmailMatch')
            ->assertDontSee('MaleMatch')
            ->assertDontSee('NoMatch')
            ->assertSee('value="Needle"', false);
    }

    public function test_index_paginates_ten_records_and_preserves_filters_in_pagination_links(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        foreach (range(1, 11) as $index) {
            Pelanggan::create($this->validPelanggan([
                'first_name' => 'Needle '.$index,
                'email' => "needle{$index}@example.com",
                'gender' => 'Female',
            ]));
        }

        $response = $this->get(route('pelanggan.index', [
            'gender' => 'Female',
            'search' => 'Needle',
            'page' => 2,
        ]));

        $response->assertOk()
            ->assertViewHas('dataPelanggan', fn (LengthAwarePaginator $dataPelanggan): bool => $dataPelanggan->currentPage() === 2
                && $dataPelanggan->perPage() === 10
                && $dataPelanggan->total() === 11)
            ->assertSee('Needle 11')
            ->assertSee('>11</td>', false)
            ->assertSee('Navigasi halaman')
            ->assertSee('Menampilkan')
            ->assertSee('Sebelumnya')
            ->assertSee('Berikutnya')
            ->assertDontSee('Needle 1 Dika');

        $dataPelanggan = $response->viewData('dataPelanggan');
        $this->assertStringContainsString('gender=Female&search=Needle', $dataPelanggan->url(1));
    }

    public function test_index_rejects_an_unsupported_gender_filter(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $this->from(route('pelanggan.index', ['gender' => 'Unknown']))
            ->get(route('pelanggan.index', ['gender' => 'Unknown']))
            ->assertRedirect(route('pelanggan.index', ['gender' => 'Unknown']))
            ->assertSessionHasErrors('gender');
    }

    public function test_dummy_seeder_creates_one_hundred_pelanggan_records(): void
    {
        $this->seed(CreatePelangganDummy::class);

        $this->assertDatabaseCount('pelanggan', 100);
    }

    public function test_create_form_is_displayed(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $this->get(route('pelanggan.create'))
            ->assertOk()
            ->assertSee('id="sidebarMenu"', false)
            ->assertSee('nav-item active', false);
    }

    public function test_store_saves_valid_pelanggan(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $this->post(route('pelanggan.store'), $this->validPelanggan())
            ->assertRedirect(route('pelanggan.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pelanggan', [
            'first_name' => 'Andika',
            'email' => 'andika@example.com',
        ]);
    }

    public function test_store_rejects_invalid_pelanggan(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $this->from(route('pelanggan.create'))
            ->post(route('pelanggan.store'), [
                'first_name' => '',
                'last_name' => '',
                'birthday' => 'not-a-date',
                'gender' => 'Other',
                'email' => 'not-an-email',
                'phone' => 'not-a-number',
            ])
            ->assertRedirect(route('pelanggan.create'))
            ->assertSessionHasErrors([
                'first_name',
                'last_name',
                'birthday',
                'gender',
                'email',
                'phone',
            ]);

        $this->assertDatabaseCount('pelanggan', 0);
    }

    public function test_update_and_show_pelanggan(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $pelanggan = Pelanggan::create($this->validPelanggan());

        $this->get(route('pelanggan.show', $pelanggan))
            ->assertOk()
            ->assertSee('Andika')
            ->assertSee('id="sidebarMenu"', false)
            ->assertSee('nav-item active', false);
        $this->get(route('pelanggan.edit', $pelanggan))
            ->assertOk()
            ->assertSee('Andika')
            ->assertSee('id="sidebarMenu"', false)
            ->assertSee('nav-item active', false);

        $updated = $this->validPelanggan();
        $updated['first_name'] = 'Dika';

        $this->put(route('pelanggan.update', $pelanggan), $updated)
            ->assertRedirect(route('pelanggan.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pelanggan', [
            'pelanggan_id' => $pelanggan->pelanggan_id,
            'first_name' => 'Dika',
        ]);
    }

    public function test_update_allows_existing_email_but_rejects_another_records_email(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $pelanggan = Pelanggan::create($this->validPelanggan());
        $otherPelanggan = Pelanggan::create($this->validPelanggan([
            'first_name' => 'Budi',
            'email' => 'budi@example.com',
        ]));

        $this->put(route('pelanggan.update', $pelanggan), $this->validPelanggan())
            ->assertRedirect(route('pelanggan.index'));

        $this->from(route('pelanggan.edit', $otherPelanggan))
            ->put(route('pelanggan.update', $otherPelanggan), $this->validPelanggan([
                'email' => 'andika@example.com',
            ]))
            ->assertRedirect(route('pelanggan.edit', $otherPelanggan))
            ->assertSessionHasErrors('email');
    }

    public function test_destroy_deletes_pelanggan(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $pelanggan = Pelanggan::create($this->validPelanggan());

        $this->delete(route('pelanggan.destroy', $pelanggan))
            ->assertRedirect(route('pelanggan.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('pelanggan', [
            'pelanggan_id' => $pelanggan->pelanggan_id,
        ]);
    }

    private function validPelanggan(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Andika',
            'last_name' => 'Dika',
            'birthday' => '2004-01-15',
            'gender' => 'Male',
            'email' => 'andika@example.com',
            'phone' => '081234567890',
        ], $overrides);
    }
}
