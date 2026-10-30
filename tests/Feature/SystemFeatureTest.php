<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Socio;
use App\Models\Pago;
use App\Models\Visita;
use App\Models\TasaDolar;
use App\Mail\SocioBienvenidaMail;
use App\Services\BcvScraperService;
use Illuminate\Support\Facades\Mail;

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

    public function test_new_socio_registration_sends_welcome_mail_with_action_number()
    {
        Mail::fake();

        $user = User::first();
        $this->actingAs($user);

        $cedula = '99' . rand(100000, 999999);
        $accion = 'ACC-' . rand(500, 999);
        $correo = "socio.test{$cedula}@countryclub.com";

        $response = $this->post('/socios', [
            'nombre' => 'Alejandro',
            'apellido' => 'Guzmán',
            'cedula' => $cedula,
            'accion' => $accion,
            'correo' => $correo,
            'numero' => '04141234567',
            'direccion' => 'Urb. Valle Arriba',
            'estado' => 'activo',
        ]);

        $socio = Socio::where('cedula', $cedula)->first();
        $this->assertNotNull($socio);
        $this->assertEquals($accion, $socio->accion);

        $response->assertRedirect('/socios/' . $socio->id);

        Mail::assertSent(SocioBienvenidaMail::class, function ($mail) use ($socio, $accion, $correo) {
            return $mail->hasTo($correo) &&
                   $mail->socio->accion === $accion;
        });
    }

    public function test_visitas_module_and_garita_check_in()
    {
        $vigilante = User::where('rol', 'vigilante')->first() ?? User::first();
        $this->actingAs($vigilante);

        // 1. Acceso al módulo de garita
        $response = $this->get('/visitas');
        $response->assertStatus(200);
        $response->assertSee('Garita');

        // 2. Búsqueda por cédula / acción o QR
        $socio = Socio::first();
        $searchResponse = $this->get('/api/visitas/buscar-socio?query=' . $socio->cedula);
        $searchResponse->assertStatus(200);
        $searchResponse->assertJsonFragment([
            'encontrado' => true,
            'cedula' => $socio->cedula,
            'accion' => $socio->accion,
        ]);

        // 3. Registrar visita en garita
        $postResponse = $this->post('/visitas', [
            'socios_id' => $socio->id,
            'invitados' => 2,
            'observaciones' => 'Vehículo Toyota Fortuner',
        ]);

        $postResponse->assertRedirect('/visitas');
        $this->assertDatabaseHas('visitas', [
            'socios_id' => $socio->id,
            'invitados' => 2,
            'observaciones' => 'Vehículo Toyota Fortuner',
        ]);
    }

    public function test_socio_monthly_visits_control_navigation()
    {
        $user = User::first();
        $this->actingAs($user);

        $socio = Socio::first();

        // Control mensual con mes y año específicos
        $response = $this->get("/socios/{$socio->id}?year=2026&month=10");
        $response->assertStatus(200);
        $response->assertSee('Control Mensual de Visitas');
        $response->assertSee('Carnet Digital Oficial');
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

        // Visitas Index
        $response = $this->get('/visitas');
        $response->assertStatus(200);

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
