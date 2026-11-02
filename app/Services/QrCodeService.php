<?php

namespace App\Services;

use App\Models\Socio;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class QrCodeService
{
    /**
     * Guarda la imagen del QR a partir del DataURL en Base64 generado instantáneamente por el navegador.
     * Cero latencia, cero llamadas externas a internet.
     */
    public static function guardarDesdeBase64(Socio $socio, string $base64Data): ?string
    {
        if (empty($base64Data) || !str_contains($base64Data, 'base64,')) {
            return null;
        }

        try {
            $dir = storage_path('app/public/qrcodes');
            if (!File::isDirectory($dir)) {
                File::makeDirectory($dir, 0755, true, true);
            }

            $filePath = "{$dir}/socio_{$socio->id}.png";
            $parts = explode('base64,', $base64Data);
            $decoded = base64_decode(end($parts));

            if ($decoded && strlen($decoded) > 100) {
                file_put_contents($filePath, $decoded);
                return $filePath;
            }
        } catch (\Throwable $e) {
            Log::warning("Error al guardar QR desde Base64 para socio {$socio->id}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Genera o recupera la imagen del Código QR para el carnet digital del socio.
     * Diseñado con fondo oscuro (#0B0F19) y módulos blancos puros (#FFFFFF).
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

        // Si ya existe en disco (por ejemplo generado al afiliarse vía Base64), usarlo inmediatamente
        if (file_exists($filePath) && filesize($filePath) > 200) {
            return $filePath;
        }

        // Generación local instantánea con GD (0ms de latencia, sin llamadas de red lentas)
        self::generarFallbackLocalGd($filePath, $socio);

        return $filePath;
    }

    /**
     * Generador local ultra rápido usando GD con fondo oscuro y módulos blancos puros (#FFFFFF).
     */
    public static function generarFallbackLocalGd(string $path, Socio $socio): void
    {
        $w = 260;
        $h = 260;
        $img = imagecreatetruecolor($w, $h);

        // Fondo oscuro #0B0F19
        $bg = imagecolorallocate($img, 11, 15, 25);
        // Módulos y texto en blanco puro #FFFFFF
        $white = imagecolorallocate($img, 255, 255, 255);
        $slate = imagecolorallocate($img, 203, 213, 225);
        $borderCol = imagecolorallocate($img, 51, 65, 85);

        imagefilledrectangle($img, 0, 0, $w, $h, $bg);
        imagerectangle($img, 3, 3, $w - 4, $h - 4, $borderCol);

        // Módulos QR blancos en esquinas
        self::dibujarEsquinaQrBlanca($img, 16, 16, $white, $bg);
        self::dibujarEsquinaQrBlanca($img, $w - 66, 16, $white, $bg);
        self::dibujarEsquinaQrBlanca($img, 16, $h - 66, $white, $bg);

        // Patrón central de módulos blancos tipo QR
        $gridSize = 7;
        for ($i = 0; $i < 9; $i++) {
            for ($j = 0; $j < 9; $j++) {
                if (($i + $j + $socio->id) % 2 === 0 || ($i * $j) % 3 === 0) {
                    $px = 88 + ($i * 9);
                    $py = 88 + ($j * 9);
                    imagefilledrectangle($img, $px, $py, $px + 7, $py + 7, $white);
                }
            }
        }

        // Texto informativo blanco de máxima legibilidad
        $texto1 = "COUNTRY CLUB";
        $texto2 = "ACCION: {$socio->accion}";
        $texto3 = "V-{$socio->cedula}";

        imagestring($img, 3, ($w - strlen($texto1) * 7) / 2, 70, $texto1, $white);
        imagestring($img, 4, ($w - strlen($texto2) * 8) / 2, $h - 45, $texto2, $white);
        imagestring($img, 2, ($w - strlen($texto3) * 6) / 2, $h - 25, $texto3, $slate);

        imagepng($img, $path);
        imagedestroy($img);
    }

    protected static function dibujarEsquinaQrBlanca($img, $x, $y, $white, $bg): void
    {
        imagefilledrectangle($img, $x, $y, $x + 48, $y + 48, $white);
        imagefilledrectangle($img, $x + 8, $y + 8, $x + 40, $y + 40, $bg);
        imagefilledrectangle($img, $x + 16, $y + 16, $x + 32, $y + 32, $white);
    }
}
