<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Socio;
use App\Models\Pago;
use App\Models\TasaDolar;
use App\Services\BcvScraperService;

class SystemFeatureTest extends TestCase
{
    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_login_page_renders_with_real_bcv_rate()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('COUNTRY CLUB');
        $response->assertSee('Tasa BCV Oficial');
    }

    public function test_user_can_login_with_demo_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'admin@countryclub.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_bcv_service_returns_updated_rate()
    {
        $service = app(BcvScraperService::class);
        $tasa = $service->obtenerTasa();
        $this->assertGreaterThan(100.0, $tasa);
    }

    public function test_authenticated_user_can_access_all_modules()
    {
        $user = User::first();
        $this->actingAs($user);

        // Dashboard
        $response = $this->get('/dashboard');
        $response->assertStatus(200);

        // Socios Index
        $response = $this->get('/socios');
        $response->assertStatus(200);

        // Socios Create
        $response = $this->get('/socios/create');
        $response->assertStatus(200);

        // Socios Show
        $socio = Socio::first();
        if ($socio) {
            $response = $this->get('/socios/' . $socio->id);
            $response->assertStatus(200);
        }

        // Pagos Index
        $response = $this->get('/pagos');
        $response->assertStatus(200);

        // Pagos Create
        $response = $this->get('/pagos/create');
        $response->assertStatus(200);

        // Movimientos
        $response = $this->get('/movimientos');
        $response->assertStatus(200);
    }
}
