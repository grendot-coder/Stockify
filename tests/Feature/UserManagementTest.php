<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    protected $adminUser;

    protected $managerUser;

    protected $staffUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first()
            ?? User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->managerUser = User::where('role', 'manager')->first()
            ?? User::factory()->create(['role' => 'manager', 'is_active' => true]);

        $this->staffUser = User::where('role', 'staff')->first()
            ?? User::factory()->create(['role' => 'staff', 'is_active' => true]);
    }

    public function test_admin_can_view_users_list(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/users');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Akun Pengguna');
        $response->assertSee($this->adminUser->name);
    }

    public function test_non_admin_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->staffUser)->get('/users');
        $response->assertStatus(403);

        $responseManager = $this->actingAs($this->managerUser)->get('/users');
        $responseManager->assertStatus(403);
    }

    public function test_admin_can_create_user(): void
    {
        $uniqueEmail = 'staff-'.uniqid().'@stockify.id';

        $response = $this->actingAs($this->adminUser)->post('/users', [
            'name' => 'Staff Uji '.uniqid(),
            'email' => $uniqueEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'staff',
            'phone' => '081298765432',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'email' => $uniqueEmail,
            'role' => 'staff',
            'is_active' => 1,
        ]);
    }

    public function test_admin_can_update_user(): void
    {
        $user = User::factory()->create([
            'role' => 'staff',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->put("/users/{$user->id}", [
            'name' => 'Nama Baru Diperbarui',
            'email' => $user->email,
            'role' => 'manager',
            'phone' => '089988776655',
            'is_active' => '1',
        ]);

        $response->assertRedirect('/users');
        $user->refresh();
        $this->assertEquals('Nama Baru Diperbarui', $user->name);
        $this->assertEquals('manager', $user->role);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $response = $this->actingAs($this->adminUser)->delete("/users/{$this->adminUser->id}");
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->adminUser->id]);
    }
}
