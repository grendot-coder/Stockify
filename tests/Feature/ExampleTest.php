<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test halaman landing page dapat dimuat dengan baik.
     */
    public function test_landing_page_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Stockify');
    }

    /**
     * Test halaman login dapat dimuat dengan baik.
     */
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Stockify');
    }

    /**
     * Test user terautentikasi dapat mengakses dashboard.
     */
    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Selamat Datang');
    }

    /**
     * Test user dapat logout dengan aman.
     */
    public function test_user_can_logout(): void
    {
        $user = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}

