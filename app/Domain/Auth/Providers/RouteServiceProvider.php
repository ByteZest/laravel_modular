<?php

namespace App\Domain\Auth\Providers;

use App\Domain\Auth\Http\Controllers\AuthenticationController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as RouteServiceProviderLaravel;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends RouteServiceProviderLaravel
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot(): void
    {
    }

    public function map()
    {
        $this->group([
            'prefix' => 'auth',
            'as' => 'auth.',
        ], function () {
            $this->mapWebRoutes();
        });
    }

    private function mapWebRoutes()
    {
        Route::middleware('web')
            ->group(function () {
                $this->post('login', [AuthenticationController::class, 'login'])
                    ->middleware('guest')
                    ->name('login');
                $this->post('register', [AuthenticationController::class, 'register'])
                    ->middleware('guest')
                    ->name('register');
                $this->post('logout', [AuthenticationController::class, 'logout'])
                    ->middleware('auth:sanctum');
                $this->get('me', [AuthenticationController::class, 'me'])
                    ->middleware('auth:sanctum');
            });
    }
}
