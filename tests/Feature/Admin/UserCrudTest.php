<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_a_user(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $this->post(route('admin.users.store'), [
            'name' => 'Andika',
            'email' => 'andika@example.com',
            'password' => 'strong-password',
            'password_confirmation' => 'strong-password',
        ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success', 'User berhasil ditambahkan.');

        $user = User::query()->where('email', 'andika@example.com')->firstOrFail();
        $this->assertFalse($user->isAdmin());
        $this->assertTrue(Hash::check('strong-password', $user->password));
        $this->assertNotSame('strong-password', $user->password);

        $this->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Andika')
            ->assertSee('andika@example.com')
            ->assertSee('nav-item active', false);

        $this->put(route('admin.users.update', $user), [
            'name' => 'Andika Pratama',
            'email' => 'andika.pratama@example.com',
            'password' => '',
            'password_confirmation' => '',
        ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success', 'User berhasil diperbarui.');

        $user->refresh();
        $this->assertSame('Andika Pratama', $user->name);
        $this->assertSame('andika.pratama@example.com', $user->email);
        $this->assertTrue(Hash::check('strong-password', $user->password));

        $this->delete(route('admin.users.destroy', $user))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success', 'User berhasil dihapus.');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_can_grant_admin_access_to_a_new_user(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $this->post(route('admin.users.store'), [
            'name' => 'Admin Baru',
            'email' => 'admin.baru@example.com',
            'password' => 'strong-password',
            'password_confirmation' => 'strong-password',
            'is_admin' => '1',
        ])->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'admin.baru@example.com',
            'is_admin' => true,
        ]);
    }

    public function test_user_form_rejects_duplicate_email_and_short_password(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        User::factory()->create(['email' => 'existing@example.com']);

        $this->from(route('admin.users.create'))
            ->post(route('admin.users.store'), [
                'name' => 'User Baru',
                'email' => 'existing@example.com',
                'password' => 'short',
                'password_confirmation' => 'short',
            ])
            ->assertRedirect(route('admin.users.create'))
            ->assertSessionHasErrors(['email', 'password']);

        $this->assertDatabaseCount('users', 2);
    }

    public function test_admin_cannot_remove_own_access_or_delete_own_account(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $this->put(route('admin.users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'password' => '',
            'password_confirmation' => '',
        ])
            ->assertSessionHasErrors('is_admin');

        $this->delete(route('admin.users.destroy', $admin))
            ->assertSessionHasErrors('user');

        $this->assertModelExists($admin);
        $this->assertTrue($admin->fresh()->isAdmin());
    }
}
