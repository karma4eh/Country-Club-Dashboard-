<?php

namespace App\Services;

use App\Models\TasaDolar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BcvScraperService
{
    /**
     * Obtiene la tasa del BCV más reciente.
     */
    public function obtenerTasa(bool $forzarActualizacion = false): float
    {
        $ultima = TasaDolar::orderBy('fecha_actualizacion', 'desc')->first();

        // Si ya existe una tasa en BD y no se solicita forzar actualización, devolverla de inmediato (0ms de latencia)
        if ($ultima && !$forzarActualizacion) {
            return (float) $ultima->tasa;
        }

        // Si se fuerza explícitamente o no existe ninguna en BD, consultar fuentes externas
        return $this->sincronizarTasaEnVivo($ultima ? (float) $ultima->tasa : 889.2553);
    }

    /**
     * Sincroniza la tasa consultando primero el portal del BCV y con fallback a DolarApi.
     */
    public function sincronizarTasaEnVivo(float $fallback = 889.2553): float
    {
        // 1. Intentar scraping directo del portal del BCV
        $tasaBcv = $this->scrapingDirectoBcv();
        if ($tasaBcv !== null && $tasaBcv > 0) {
            $this->guardarTasa($tasaBcv);
            return $tasaBcv;
        }

        // 2. Fallback: API pública de tasas oficiales de Venezuela (DolarApi)
        $tasaApi = $this->consultarDolarApi();
        if ($tasaApi !== null && $tasaApi > 0) {
            $this->guardarTasa($tasaApi);
            return $tasaApi;
        }

        return $fallback;
    }

    /**
     * Extrae la tasa del dólar directamente del HTML oficial de bcv.org.ve
     */
    protected function scrapingDirectoBcv(): ?float
    {
        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://www.bcv.org.ve/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 7,
                CURLOPT_CONNECTTIMEOUT => 4,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
            ]);

            $html = curl_exec($ch);
            $error = curl_errno($ch);
            curl_close($ch);

            if ($html === false || $error) {
                return null;
            }

            // Buscar el contenedor específico: id="dolar" y su etiqueta <strong>
            if (preg_match('/id=["\']dolar["\'][\s\S]*?<strong[^>]*>([\d,\.]+)<\/strong>/i', $html, $matches)) {
                $valor = trim($matches[1]);
                $valor = str_replace('.', '', $valor); // Eliminar posibles separadores de miles
                $valor = str_replace(',', '.', $valor); // Coma a punto decimal
                $tasa = (float) $valor;

                if ($tasa > 10) {
                    return $tasa;
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Error en scraping directo del BCV: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Consulta la API REST oficial de DolarApi (fuente oficial BCV)
     */
    protected function consultarDolarApi(): ?float
    {
        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://ve.dolarapi.com/v1/dolares/oficial',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 6,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (CountryClubDashboard/2.0)',
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            if ($response) {
                $data = json_decode($response, true);
                if (isset($data['promedio']) && is_numeric($data['promedio'])) {
                    return (float) $data['promedio'];
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Error en consulta a DolarApi: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Almacena o actualiza la tasa en la base de datos
     */
    protected function guardarTasa(float $tasa): void
    {
        try {
            TasaDolar::create([
                'tasa' => $tasa,
                'fecha_actualizacion' => Carbon::now(),
                'scraped_today' => true,
            ]);
        } catch (\Throwable $e) {
            Log::error("Error al guardar la tasa BCV: " . $e->getMessage());
        }
    }
}
