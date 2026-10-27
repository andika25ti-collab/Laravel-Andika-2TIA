<?php

namespace Tests\Feature;

use App\Models\Pelanggan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelangganCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_saved_pelanggan(): void
    {
        Pelanggan::create($this->validPelanggan());

        $this->get(route('pelanggan.index'))
            ->assertOk()
            ->assertSee('Andika')
            ->assertSee('andika@example.com');
    }

    public function test_create_form_is_displayed(): void
    {
        $this->get(route('pelanggan.create'))->assertOk();
    }

    public function test_store_saves_valid_pelanggan(): void
    {
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
        $pelanggan = Pelanggan::create($this->validPelanggan());

        $this->get(route('pelanggan.show', $pelanggan))->assertOk()->assertSee('Andika');
        $this->get(route('pelanggan.edit', $pelanggan))->assertOk()->assertSee('Andika');

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
