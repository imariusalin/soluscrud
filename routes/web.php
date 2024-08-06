<?php

use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/clients', [ClientsController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientsController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientsController::class, 'store'])->name('clients.store');
    Route::get('/clients/{id}', [ClientsController::class, 'show'])->name('clients.show');
    Route::get('/clients/{id}/edit', [ClientsController::class, 'edit'])->name('clients.edit');
    Route::post('/clients/{id}/update', [ClientsController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{id}', [ClientsController::class, 'destroy'])->name('clients.destroy');

    Route::get('/servers', [ServersController::class, 'index'])->name('servers.index');
    Route::get('/servers/create', [ServersController::class, 'create'])->name('servers.create');
    Route::post('/servers', [ServersController::class, 'store'])->name('servers.store');
    Route::delete('/servers/{id}', [ServersController::class, 'destroy'])->name('servers.destroy');

});

require __DIR__.'/auth.php';
