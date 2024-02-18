<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Junges\Kafka\Config\Config;
use Junges\Kafka\Message\Message;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::any('/readiness', \App\Http\Controllers\ReadnessController::class);
Route::any(
    'kafka',
    function () {
        \Junges\Kafka\Facades\Kafka::publishOn('pnquxavk-default')
            ->withSasl(
                new \Junges\Kafka\Config\Sasl(
                    username: config('kafka.sasl.username'),
                    password: config('kafka.sasl.password'),
                    mechanisms: 'SCRAM-SHA-512',
                    securityProtocol: Config::SASL_SSL,
                )
            )
            ->withBodyKey('key', 'value')
            ->withMessage((new Message())->withBody([
                'teste' => ['ok' => 'ok3'],
            ]))
            ->send();
        return 'ok';
    }
);
Route::any('kafka2', function() {
    app(\App\Kafka\Producer::class)->send('teste', 'pnquxavk-default');
});
Route::any('info', fn () => phpinfo());
