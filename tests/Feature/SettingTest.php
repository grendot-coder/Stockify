<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Tests\TestCase;

class SettingTest extends TestCase
{
    protected $adminUser;

    protected $staffUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first()
            ?? User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->staffUser = User::where('role', 'staff')->first()
            ?? User::factory()->create(['role' => 'staff', 'is_active' => true]);
    }

    public function test_admin_can_access_settings_page(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/settings');
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Umum Aplikasi');
    }

    public function test_staff_cannot_access_settings_page(): void
    {
        $response = $this->actingAs($this->staffUser)->get('/settings');
        $response->assertStatus(403);
    }

    public function test_admin_can_update_settings(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/settings', [
            'app_name' => 'Stockify',
            'app_tagline' => 'Next-Gen Supply Chain',
            'warehouse_address' => 'Jl. Logistik Industri No. 99, Surabaya',
            'warehouse_phone' => '0812-9999-8888',
            'warehouse_email' => 'contact@warehousepro.id',
            'default_min_stock' => 10,
        ]);

        $response->assertRedirect('/settings');
        $response->assertSessionHas('success');

        $this->assertEquals('Stockify', Setting::get('app_name'));
        $this->assertEquals('10', Setting::get('default_min_stock'));
    }
}
