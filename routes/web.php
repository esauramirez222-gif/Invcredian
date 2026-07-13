<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\PublicCatalogController;
use App\Http\Controllers\LoanController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

// -----------------------------------------------------------------------
// RUTA DE INSTALACIÓN (Base de Datos)
// -----------------------------------------------------------------------
Route::get('/instalar-bd', function () {
    try {
        Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
        return '¡Éxito! Base de datos migrada y sembrada correctamente en TiDB.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// -----------------------------------------------------------------------
// RUTAS PÚBLICAS Y DE AUTENTICACIÓN
// -----------------------------------------------------------------------
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')
        ->stateless()
        ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
        ->user();

    $user = User::updateOrCreate([
        'email' => $googleUser->email,
    ], [
        'name' => $googleUser->name,
        'google_id' => $googleUser->id,
        'password' => null, 
    ]);

    Auth::login($user);
    
    // REDIRECCIÓN INTELIGENTE
    if ($user->email === 'jaziel@credian.mx' || $user->email === 'leonardo@credian.mx') { 
        return redirect()->route('loans.index'); 
    }

    return redirect()->route('catalog.index');
});

require __DIR__.'/auth.php';

// -----------------------------------------------------------------------
// RUTAS PROTEGIDAS 
// -----------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    
    // =======================================================
    // ZONA GENERAL (Todos los usuarios autenticados)
    // =======================================================
    Route::get('/', [PublicCatalogController::class, 'index'])->name('catalog.index');
    Route::post('/lista/agregar/{resource}', [PublicCatalogController::class, 'addToList'])->name('catalog.add');
    Route::post('/lista/quitar/{id}', [PublicCatalogController::class, 'removeFromList'])->name('catalog.remove');
    Route::get('/solicitud', [PublicCatalogController::class, 'viewList'])->name('catalog.list');
    Route::post('/solicitud/enviar', [PublicCatalogController::class, 'submitRequest'])->name('catalog.submit');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('solicitudes', LoanController::class)->names('loans')->only(['index', 'show'])->parameters(['solicitudes' => 'loan']);

    // =======================================================
    // ZONA VIP (Exige el gafete 'admin' que creamos en el AppServiceProvider)
    // =======================================================
    Route::middleware('can:admin')->group(function () {
        
        Route::resource('inventario', ResourceController::class)->names('inventory')->parameters([
            'inventario' => 'resource'
        ]);

        Route::post('solicitudes/{loan}/aprobar', [LoanController::class, 'approve'])->name('loans.approve');
        Route::post('solicitudes/{loan}/rechazar', [LoanController::class, 'reject'])->name('loans.reject');
        Route::post('solicitudes/{loan}/devolver', [LoanController::class, 'returnLoan'])->name('loans.return');
        Route::delete('/solicitudes/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy');
    });

});