<?php

use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonicaController;
use App\Http\Controllers\OfficeLifeController;
use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'year' => Carbon::now()->year,
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // officelife
    Route::get('/officelife', [OfficeLifeController::class, 'index'])->name('officelife.index');
    Route::post('/officelife/{plan}/price', [OfficeLifeController::class, 'price'])->name('officelife.price');

    // monica
    Route::get('/monica', [MonicaController::class, 'index'])->name('monica.index');
    Route::delete('', [DashboardController::class, 'destroy'])->name('dashboard.destroy');

    Route::middleware(['administration'])->prefix('administration')->group(function () {
        Route::get('', [AdministrationController::class, 'index'])->name('administration.index');
        Route::get('{user}', [AdministrationController::class, 'show'])->name('administration.user.show');
    });
});
