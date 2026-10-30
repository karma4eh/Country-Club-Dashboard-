<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Socio;
use App\Models\Pago;
use App\Models\Visita;
use App\Models\TasaDolar;
use App\Models\Movimiento;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Usuarios
        $admin = User::firstOrCreate(
            ['email' => 'admin@countryclub.com'],
            [
                'nombre_completo' => 'Administrador del Club',
                'password' => Hash::make('password'),
                'rol' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'secretaria@countryclub.com'],
            [
                'nombre_completo' => 'Secretaría General',
                'password' => Hash::make('password'),
                'rol' => 'secretaria',
            ]
        );

        User::firstOrCreate(
            ['email' => 'vigilancia@countryclub.com'],
            [
                'nombre_completo' => 'Oficial José Gregorio Colmenares',
                'password' => Hash::make('password'),
                'rol' => 'vigilante',
            ]
        );

        // 2. Socios
        $carlos = Socio::firstOrCreate(
            ['cedula' => '12345678'],
            [
                'nombre' => 'Carlos',
                'apellido' => 'Mendoza',
                'direccion' => 'Av. Principal Las Mercedes, Quinta Los Robles',
                'numero' => '0414-1234567',
                'correo' => 'carlos.mendoza@email.com',
                'accion' => 'ACC-001',
                'estado' => 'activo',
                'saldo' => 0.00,
                'ultimo_mes_pagado' => Carbon::now(),
                'fecha_nacimiento' => '1985-05-15',
                'socio_desde' => '2015-01-10',
            ]
        );

        $maria = Socio::firstOrCreate(
            ['cedula' => '18765432'],
            [
                'nombre' => 'María',
                'apellido' => 'Fernández',
                'direccion' => 'Calle Los Pinos, Res. El Bosque, Apto 4B',
                'numero' => '0424-9876543',
                'correo' => 'maria.fernandez@email.com',
                'accion' => 'ACC-002',
                'estado' => 'activo',
                'saldo' => -30.00,
                'ultimo_mes_pagado' => Carbon::now()->subMonths(3),
                'fecha_nacimiento' => '1990-09-20',
                'socio_desde' => '2018-06-01',
            ]
        );

        $roberto = Socio::firstOrCreate(
            ['cedula' => '14523987'],
            [
                'nombre' => 'Roberto',
                'apellido' => 'Castillo',
                'direccion' => 'Urb. Alto Prado, Calle 2, Casa #15',
                'numero' => '0412-5554321',
                'correo' => 'roberto.castillo@email.com',
                'accion' => 'ACC-003',
                'estado' => 'inactivo',
                'saldo' => -120.00,
                'ultimo_mes_pagado' => Carbon::now()->subMonths(12),
                'fecha_nacimiento' => '1978-11-03',
                'socio_desde' => '2010-03-15',
            ]
        );

        // 3. Tasa Dólar
        TasaDolar::create([
            'tasa' => 889.2553,
            'fecha_actualizacion' => Carbon::now(),
            'scraped_today' => true,
        ]);

        // 4. Pago inicial
        Pago::create([
            'socios_id' => $carlos->id,
            'descripcion' => 'Cuota Mensual Mantenimiento',
            'monto' => 10.00,
            'fecha_pago' => Carbon::now(),
        ]);

        // 5. Visitas de prueba
        Visita::create(['socios_id' => $carlos->id, 'fecha_entrada' => Carbon::now()]);
        Visita::create(['socios_id' => $carlos->id, 'fecha_entrada' => Carbon::now()->subDays(2)]);
        Visita::create(['socios_id' => $carlos->id, 'fecha_entrada' => Carbon::now()->subDays(5)]);

        // 6. Movimientos
        Movimiento::create([
            'usuarios_id' => $admin->id,
            'tipo' => 'Instalación Inicial del Sistema',
            'fecha' => Carbon::now(),
        ]);
    }
}
