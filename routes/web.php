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
        // Ejecutamos las migraciones forzadas (por estar en producción) y los seeders
        Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
        return '¡Éxito! Base de datos migrada y sembrada correctamente en TiDB.';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// -----------------------------------------------------------------------
// RUTAS PÚBLICAS Y DE AUTENTICACIÓN (No requieren inicio de sesión)
// -----------------------------------------------------------------------

// 1. Rutas de Google Socialite (El botón de Login)
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')
        ->stateless()
        ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
        ->user();

    // Crea el usuario o lo inicia si ya existe
    $user = User::updateOrCreate([
        'email' => $googleUser->email,
    ], [
        'name' => $googleUser->name,
        'google_id' => $googleUser->id,
        'password' => null, // Ya no usamos contraseña
    ]);

    Auth::login($user);
    
    // REDIRECCIÓN INTELIGENTE
    if ($user->email === 'jaziel@credian.mx' || $user->email === 'leonardo@credian.mx') { 
        return redirect()->route('loans.index'); // El admin va directo a las solicitudes
    }

    // Al entrar con éxito un usuario normal, lo mandamos directo al catálogo
    return redirect()->route('catalog.index');
});

// 2. Rutas generadas por Laravel Breeze (Login nativo, Registro, etc.)
require __DIR__.'/auth.php';


// -----------------------------------------------------------------------
// RUTAS PROTEGIDAS (Exigen que el usuario haya iniciado sesión)
// -----------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    
    // =======================================================
    // ZONA GENERAL (Todos los usuarios autenticados pueden entrar)
    // =======================================================
    
    // Catálogo y Carrito
    Route::get('/', [PublicCatalogController::class, 'index'])->name('catalog.index');
    Route::post('/lista/agregar/{resource}', [PublicCatalogController::class, 'addToList'])->name('catalog.add');
    Route::post('/lista/quitar/{id}', [PublicCatalogController::class, 'removeFromList'])->name('catalog.remove');
    Route::get('/solicitud', [PublicCatalogController::class, 'viewList'])->name('catalog.list');
    Route::post('/solicitud/enviar', [PublicCatalogController::class, 'submitRequest'])->name('catalog.submit');

    // Perfil de Usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ver solicitudes propias (Historial del usuario normal)
    Route::resource('solicitudes', LoanController::class)->names('loans')->only(['index', 'show'])->parameters(['solicitudes' => 'loan']);


    // =======================================================
    // ZONA VIP / ADMIN (Solo Jaziel y Leonardo)
    // =======================================================
    Route::middleware([function ($request, $next) {
        $admins = ['jaziel@credian.mx', 'leonardo@credian.mx'];
        
        // Si el correo NO está en la lista de VIPs, lo expulsamos al catálogo público
        if (!in_array(Auth::user()->email, $admins)) {
            return redirect()->route('catalog.index'); 
        }
        
        // Si es admin, lo dejamos pasar a las rutas de abajo
        return $next($request); 
    }])->group(function () {
        
        // CRUD DE INVENTARIO (Gestión de recursos)
        Route::resource('inventario', ResourceController::class)->names('inventory')->parameters([
            'inventario' => 'resource'
        ]);

        // ACCIONES DE PRÉSTAMOS ADMINISTRATIVAS (Aprobar, rechazar, devolver, eliminar)
        Route::post('solicitudes/{loan}/aprobar', [LoanController::class, 'approve'])->name('loans.approve');
        Route::post('solicitudes/{loan}/rechazar', [LoanController::class, 'reject'])->name('loans.reject');
        Route::post('solicitudes/{loan}/devolver', [LoanController::class, 'returnLoan'])->name('loans.return');
        Route::delete('/solicitudes/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy');
    });

});