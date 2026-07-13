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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

Route::get('/test-correo', function () {
    try {
        // 1. Recolectar la configuración que Laravel realmente está leyendo en Vercel
        $config = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
            // No imprimimos la contraseña por seguridad, solo vemos si existe
            'tiene_password' => config('mail.mailers.smtp.password') ? 'Sí' : 'No', 
            'encryption' => config('mail.mailers.smtp.encryption'),
            'from' => config('mail.from.address'),
        ];

        // 2. Intentar enviar un correo de prueba puro
        Mail::raw('Si puedes leer esto, tu configuración de Vercel y Google funciona perfectamente.', function ($message) {
            // Se enviará al mismo correo remitente para probar
            $message->to(config('mail.from.address')) 
                    ->subject('Prueba de Diagnóstico Credian');
        });

        // 3. Si llega aquí, fue un éxito
        return response()->json([
            'status' => '¡Éxito! El correo salió de los servidores de Vercel.',
            'configuracion_actual' => $config
        ]);

    } catch (\Exception $e) {
        // 4. Si falla, atrapamos el error exacto y lo mostramos en pantalla
        return response()->json([
            'status' => 'Error al intentar enviar el correo',
            'error_mensaje' => $e->getMessage(),
            'configuracion_actual' => $config ?? 'No se pudo leer la configuración'
        ], 500);
    }
});

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