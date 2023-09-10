<?php

use Illuminate\Support\Facades\Route;
use OpenTelemetry\API\Trace\TracerInterface;
use OpenTelemetry\SDK\Common\Configuration\Configuration;
use OpenTelemetry\SDK\Common\Configuration\Variables;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    /** @var TracerInterface $tracer */
    $tracer = \OpenTelemetry\API\Globals::tracerProvider()->getTracer('laravel');
    $span = $tracer->spanBuilder('home')->startSpan();
    $scope = $span->activate();
    \App\Models\User::factory()->create();
    $span->end();
    $scope->detach();
    return view('welcome');
});

