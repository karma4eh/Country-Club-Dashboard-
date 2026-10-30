<?php

use Illuminate\Support\Facades\Schedule;
use App\Services\BcvScraperService;

// Actualizar la tasa del BCV automáticamente de lunes a viernes a las 5:05 PM
Schedule::call(function (BcvScraperService $bcv) {
    $bcv->forzarScraping();
})->weekdays()->at('17:05')->name('actualizar-tasa-bcv');
