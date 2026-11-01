<?php

namespace App\Services;

use App\Models\Socio;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class QrCodeService
{
    /**
     * Genera o recupera la imagen del Código QR para el carnet digital del socio.
     * Diseñado especialmente con tema oscuro (fondo oscuro #0B0F19 y módulos dorados #FBBF24)
     * para armonizar perfectamente con la identidad visual del Country Club.
     *
     * @param Socio $socio
     * @return string Ruta absoluta local al archivo PNG del QR
     */
    public static function generarQrSocio(Socio $socio): string
    {
        $dir = storage_path('app/public/qrcodes');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true, true);
        }

        $filePath = "{$dir}/socio_{$socio->id}.png";

        // Contenido del QR: datos oficiales para el escáner de Garita
        $qrPayload = json_encode([
            'tipo' => 'SOCIO_COUNTRY_CLUB',
            'id' => $socio->id,
            'cedula' => $socio->cedula,
            'accion' => $socio->accion,
            'nombre' => $socio->nombre_completo,
            'estado' => $socio->estado,
        ], JSON_UNESCAPED_UNICODE);

        // Intentar generar con API de alta fidelidad en tema oscuro
        try {
            $encodedData = urlencode($qrPayload);
            // Color de módulos: FBBF24 (Dorado Club), Fondo: 0B0F19 (Negro azulado del club)
            $url = "https://api.qrserver.com/v1/create-qr-code/?size=260x260&color=FBBF24&bgcolor=0B0F19&margin=8&data={$encodedData}";

            $ctx = stream_context_create([
                'http' => [
                    'timeout' => 4,
                    'user_agent' => 'CountryClubApp/1.0',
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ]);

            $imgContent = @file_get_contents($url, false, $ctx);

            if ($imgContent && strlen($imgContent) > 200) {
                file_put_contents($filePath, $imgContent);
                return $filePath;
            }
        } catch (\Throwable $e) {
            Log::info("No se pudo obtener QR remoto, utilizando generador local GD: " . $e->getMessage());
        }

        // Si ya existía un archivo previo, usarlo
        if (file_exists($filePath) && filesize($filePath) > 200) {
            return $filePath;
        }

        // Fallback local con GD: genera una imagen oscura estilizada con el código del socio
        self::generarFallbackLocalGd($filePath, $socio);

        return $filePath;
    }

    /**
     * Generador local usando GD en caso de estar sin conexión a Internet.
     */
    protected static function generarFallbackLocalGd(string $path, Socio $socio): void
    {
        $w = 260;
        $h = 260;
        $img = imagecreatetruecolor($w, $h);

        // Fondo oscuro #0B0F19
        $bg = imagecolorallocate($img, 11, 15, 25);
        $gold = imagecolorallocate($img, 251, 191, 36);
        $slate = imagecolorallocate($img, 148, 163, 184);

        imagefilledrectangle($img, 0, 0, $w, $h, $bg);

        // Borde dorado
        imagerectangle($img, 4, 4, $w - 5, $h - 5, $gold);

        // Simulador de patrones QR en esquinas
        self::dibujarEsquinaQr($img, 15, 15, $gold, $bg);
        self::dibujarEsquinaQr($img, $w - 65, 15, $gold, $bg);
        self::dibujarEsquinaQr($img, 15, $h - 65, $gold, $bg);

        // Texto central identificador
        $texto1 = "COUNTRY CLUB";
        $texto2 = "ACCION: {$socio->accion}";
        $texto3 = "V-{$socio->cedula}";

        imagestring($img, 3, ($w - strlen($texto1) * 7) / 2, 85, $texto1, $gold);
        imagestring($img, 4, ($w - strlen($texto2) * 8) / 2, 115, $texto2, $gold);
        imagestring($img, 3, ($w - strlen($texto3) * 7) / 2, 145, $texto3, $slate);
        imagestring($img, 2, ($w - 18 * 6) / 2, 180, "CARNET DE GARITA", $gold);

        imagepng($img, $path);
        imagedestroy($img);
    }

    protected static function dibujarEsquinaQr($img, $x, $y, $color, $bg): void
    {
        imagefilledrectangle($img, $x, $y, $x + 50, $y + 50, $color);
        imagefilledrectangle($img, $x + 8, $y + 8, $x + 42, $y + 42, $bg);
        imagefilledrectangle($img, $x + 16, $y + 16, $x + 34, $y + 34, $color);
    }
}
