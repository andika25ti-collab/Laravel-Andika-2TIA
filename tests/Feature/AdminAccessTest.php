<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\CreateFirstUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login_for_protected_pages(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));

        $this->get(route('admin.users.index'))
            ->assertRedirect(route('login'));
    }

    public function test_first_user_seeder_creates_an_admin_account(): void
    {
        $this->seed(CreateFirstUser::class);

        $this->assertDatabaseHas('users', [
            'email' => 'gatot@pcr.ac.id',
            'is_admin' => true,
        ]);
    }

    public function test_non_admin_cannot_access_admin_pages(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_non_admin_cannot_sign_in(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password',
            'is_admin' => false,
        ]);

        $this->post(route('login.store'), [
            'email' => 'user@example.com',
            'password' => 'password',
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_can_sign_in_and_sign_out(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
            'is_admin' => true,
        ]);

        $this->get(route('login'))->assertOk();

        $this->post(route('login.store'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($admin);

        $this->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('correct-password'),
            'is_admin' => true,
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => 'admin@example.com',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
