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
        $response->assertSee('Oficial Responsable');

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
        $postResponse = $this->postJson('/visitas', [
            'socios_id' => $socio->id,
            'invitados' => 2,
            'observaciones' => 'Vehículo Toyota Fortuner',
        ]);

        $postResponse->assertStatus(200);
        $postResponse->assertJsonFragment([
            'success' => true,
            'vigilante' => $vigilante->nombre_completo,
        ]);

        $visita = Visita::where('socios_id', $socio->id)->latest('id')->first();
        $this->assertNotNull($visita);
        $this->assertEquals($vigilante->id, $visita->usuarios_id);
        $this->assertNull($visita->fecha_salida);

        // 4. Marcar salida de la visita
        $salidaResponse = $this->postJson("/visitas/{$visita->id}/salida");
        $salidaResponse->assertStatus(200);
        $salidaResponse->assertJsonFragment([
            'success' => true,
            'vigilante' => $vigilante->nombre_completo,
        ]);

        $visita->refresh();
        $this->assertNotNull($visita->fecha_salida);

        // 5. Asentar novedad en el libro digital de guardia
        $novedadResponse = $this->post('/visitas/novedades', [
            'tipo' => 'Ronda Perimetral',
            'titulo' => 'Ronda sector tenis y piscinas',
            'descripcion' => 'Recorrido completado sin novedades ni accesos no autorizados.',
        ]);

        $novedadResponse->assertRedirect();
        $this->assertDatabaseHas('novedades_garita', [
            'usuarios_id' => $vigilante->id,
            'tipo' => 'Ronda Perimetral',
            'titulo' => 'Ronda sector tenis y piscinas',
        ]);
    }

    public function test_duplicate_submission_protection_prevents_multiple_records()
    {
        $admin = User::where('rol', 'admin')->first() ?? User::first();
        $this->actingAs($admin);

        $payload = [
            'tipo' => 'Ronda Perimetral',
            'titulo' => 'Ronda rápida de prueba anti-duplicados ' . uniqid(),
            'descripcion' => 'Verificando que dos clics consecutivos no generen dos registros.',
        ];

        // Primer envío
        $res1 = $this->postJson('/visitas/novedades', $payload);
        $res1->assertStatus(200);

        // Segundo envío idéntico inmediato (simulando doble clic)
        $res2 = $this->postJson('/visitas/novedades', $payload);
        $res2->assertStatus(200);
        $res2->assertJsonFragment([
            'success' => true,
        ]);

        // Debe existir exactamente 1 registro en la base de datos, NO dos
        $count = \App\Models\NovedadGarita::where('titulo', $payload['titulo'])->count();
        $this->assertEquals(1, $count);
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
        $user = User::where('rol', 'admin')->first() ?? User::first();
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

    public function test_vigilante_menu_and_restricted_access_isolation()
    {
        $vigilante = User::where('rol', 'vigilante')->first();
        $this->actingAs($vigilante);

        // 1. Acceso a Garita permitido y con menú de seguridad
        $response = $this->get('/visitas');
        $response->assertStatus(200);
        $response->assertSee('Puesto de Vigilancia');
        $response->assertSee('Seguridad & Garita', false);
        $response->assertSee('Control de Acceso / Garita');
        $response->assertSee('Aforo en Tiempo Real');
        $response->assertSee('Libro de Novedades');
        $response->assertSee('Directorio & Carnets', false);

        // 2. NO debe ver módulos administrativos ni financieros en su menú
        $response->assertDontSee('Caja & Cobranzas', false);
        $response->assertDontSee('Caja y Cobro de Cuota');
        $response->assertDontSee('Historial de Cobros');
        $response->assertDontSee('Nueva Afiliación');
        $response->assertDontSee('Bitácora de Auditoría');

        // 3. Intento de acceso a Dashboard redirige a Visitas
        $dashResponse = $this->get('/dashboard');
        $dashResponse->assertRedirect(route('visitas.index'));

        // 4. Intento de acceso a Caja/Cobros redirige a Visitas
        $pagosResponse = $this->get('/pagos');
        $pagosResponse->assertRedirect(route('visitas.index'));

        $pagosCreateResponse = $this->get('/pagos/create');
        $pagosCreateResponse->assertRedirect(route('visitas.index'));

        // 5. Intento de acceso a Nueva Afiliación redirige a Visitas
        $sociosCreateResponse = $this->get('/socios/create');
        $sociosCreateResponse->assertRedirect(route('visitas.index'));

        // 6. Intento de acceso a Auditoría redirige a Visitas
        $movResponse = $this->get('/movimientos');
        $movResponse->assertRedirect(route('visitas.index'));
    }

    public function test_vigilante_novedad_creates_notification_for_admin_and_secretaria()
    {
        $vigilante = User::where('rol', 'vigilante')->first();
        $admin = User::where('rol', 'admin')->first();
        $secretaria = User::where('rol', 'secretaria')->first();

        $this->actingAs($vigilante);

        // 1. Vigilante registra cambio de guardia
        $titulo = 'Relevo de puesto Garita Norte ' . uniqid();
        $res = $this->postJson('/visitas/novedades', [
            'tipo' => 'Cambio de Guardia',
            'titulo' => $titulo,
            'descripcion' => 'Entrega de radio transmisores y llaves de acceso.',
        ]);
        $res->assertStatus(200);

        // 2. Comprobar que tanto Admin como Secretaria recibieron la notificación
        $notifAdmin = \App\Models\Notificacion::where('usuarios_id', $admin->id)
            ->where('titulo', 'like', "%{$titulo}%")
            ->first();
        $this->assertNotNull($notifAdmin);
        $this->assertEquals('blue', $notifAdmin->color);
        $this->assertFalse($notifAdmin->leida);

        $notifSecretaria = \App\Models\Notificacion::where('usuarios_id', $secretaria->id)
            ->where('titulo', 'like', "%{$titulo}%")
            ->first();
        $this->assertNotNull($notifSecretaria);

        // 3. Admin consulta sus notificaciones vía API
        $this->actingAs($admin);
        $apiRes = $this->getJson('/api/notificaciones');
        $apiRes->assertStatus(200);
        $apiRes->assertJsonFragment([
            'id' => $notifAdmin->id,
            'leida' => false,
        ]);

        // 4. Admin marca la notificación como leída
        $leerRes = $this->postJson("/api/notificaciones/{$notifAdmin->id}/leer");
        $leerRes->assertStatus(200);
        $notifAdmin->refresh();
        $this->assertTrue($notifAdmin->leida);

        // 5. Admin marca todas las notificaciones como leídas
        $todasRes = $this->postJson('/api/notificaciones/marcar-todas');
        $todasRes->assertStatus(200);
        $todasRes->assertJson(['no_leidas_count' => 0]);
    }

    public function test_socio_cannot_enter_twice_without_checkout()
    {
        $vigilante = User::where('rol', 'vigilante')->first() ?? User::first();
        $this->actingAs($vigilante);

        // Crear socio exclusivo para esta prueba
        $cedula = '88' . rand(100000, 999999);
        $socio = Socio::create([
            'nombre' => 'Roberto',
            'apellido' => 'Pérez',
            'cedula' => $cedula,
            'accion' => 'ACC-' . rand(1000, 9999),
            'correo' => "roberto{$cedula}@countryclub.com",
            'estado' => 'activo',
            'saldo' => 0,
        ]);

        // 1. Primer ingreso: Autorizado
        $res1 = $this->postJson('/visitas', [
            'socios_id' => $socio->id,
            'invitados' => 1,
            'observaciones' => 'Primer ingreso del día',
        ]);
        $res1->assertStatus(200);
        $res1->assertJsonFragment(['success' => true]);

        // 2. Consulta en garita: Debe marcar que YA ESTÁ DENTRO DEL CLUB
        $buscarRes = $this->getJson('/api/visitas/buscar-socio?query=' . $socio->cedula);
        $buscarRes->assertStatus(200);
        $buscarRes->assertJsonFragment([
            'esta_en_club' => true,
        ]);
        $visitaActivaId = $buscarRes->json('visita_activa_id');
        $this->assertNotNull($visitaActivaId);

        // 3. Intento de SEGUNDO ingreso sin marcar salida: DEBE SER RECHAZADO (422)
        $res2 = $this->postJson('/visitas', [
            'socios_id' => $socio->id,
            'invitados' => 0,
            'observaciones' => 'Intento de doble ingreso no permitido',
        ]);
        $res2->assertStatus(422);
        $res2->assertJsonFragment([
            'success' => false,
            'ya_en_club' => true,
        ]);

        // Verificar que en base de datos NO se duplicó el ingreso activo
        $visitasActivasCount = Visita::where('socios_id', $socio->id)
            ->whereDate('fecha_entrada', \Carbon\Carbon::today())
            ->whereNull('fecha_salida')
            ->count();
        $this->assertEquals(1, $visitasActivasCount);

        // 4. Marcar salida del socio
        $salidaRes = $this->postJson("/visitas/{$visitaActivaId}/salida");
        $salidaRes->assertStatus(200);

        // 5. Verificar que ya no está en el club
        $buscarRes2 = $this->getJson('/api/visitas/buscar-socio?query=' . $socio->cedula);
        $buscarRes2->assertStatus(200);
        $buscarRes2->assertJsonFragment([
            'esta_en_club' => false,
        ]);

        // 6. Ahora que ya marcó salida, un nuevo ingreso SÍ está permitido
        $res3 = $this->postJson('/visitas', [
            'socios_id' => $socio->id,
            'invitados' => 0,
            'observaciones' => 'Reingreso en la tarde luego de haber salido',
        ]);
        $res3->assertStatus(200);
        $res3->assertJsonFragment(['success' => true]);
    }
}
