<?php

namespace Tests\Feature;

use App\Models\RackLocation;
use App\Models\User;
use Tests\TestCase;

class RackLocationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        RackLocation::whereIn('name', ['Rack-99', 'Rack-88'])->delete();
    }

    protected function tearDown(): void
    {
        RackLocation::whereIn('name', ['Rack-99', 'Rack-88'])->delete();
        parent::tearDown();
    }

    protected function getAdmin()
    {
        return User::where('role', 'admin')->first() ?: User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    public function test_admin_can_access_locations_index_and_view_matrix(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->get('/locations');

        $response->assertStatus(200);
        $response->assertSee('Lokasi Rak Gudang');
    }

    public function test_admin_can_create_new_rack(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->post('/locations', [
            'name' => 'Rack-99',
            'capacity' => 120,
            'zone' => 'Zona Testing',
            'description' => 'Rak khusus pengujian',
        ]);

        $response->assertRedirect('/locations');
        $this->assertDatabaseHas('rack_locations', [
            'name' => 'Rack-99',
            'capacity' => 120,
        ]);
    }

    public function test_name_is_auto_formatted_to_rack_format(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->post('/locations', [
            'name' => '88', // Input angka langsung
            'capacity' => 100,
        ]);

        $response->assertRedirect('/locations');
        $this->assertDatabaseHas('rack_locations', [
            'name' => 'Rack-88',
        ]);
    }
}
