<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', App\Http\Controllers\WelcomeController::class)->name('home');

Route::get('/nosotros', function () {
    $totalTrabajadores = \App\Models\User::where('tipo', 'trabajador')->where('estado', true)->count();
    $totalEmpresas     = \App\Models\Empresa::where('estado', 'activo')->count();
    $totalOfertas      = \App\Models\Oferta::where('estado', 'Activa')->count();

    return view('nosotros', compact('totalTrabajadores', 'totalEmpresas', 'totalOfertas'));
})->name('nosotros');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Ruta ofertas
Route::get('/ofertas', [\App\Http\Controllers\OfertaPublicaController::class, 'index'])->name('ofertas.index');
Route::get('/ofertas/{oferta}', [\App\Http\Controllers\OfertaPublicaController::class, 'show'])->name('ofertas.show');


// Trabajador
Route::middleware(['auth', 'es.trabajador'])->prefix('trabajador')->name('trabajador.')->group(function () {
    Route::get('/dashboard', fn() => view('trabajador.dashboard'))->name('dashboard');
    Route::get('/perfil', [\App\Http\Controllers\Trabajador\PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [\App\Http\Controllers\Trabajador\PerfilController::class, 'update'])->name('perfil.update');
    Route::get('/postulaciones', [\App\Http\Controllers\Trabajador\PostulacionController::class, 'index'])->name('postulaciones.index');
    //ofertas 
    Route::get('/ofertas/{oferta}/postular', [\App\Http\Controllers\Trabajador\PostulacionController::class, 'crear'])->name('postulaciones.crear');
    Route::post('/ofertas/{oferta}/postular', [\App\Http\Controllers\Trabajador\PostulacionController::class, 'store'])->name('postulaciones.store');
    // Notificaciones
    Route::get('/notificaciones', [\App\Http\Controllers\Trabajador\NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::post('/notificaciones/{id}/leer', [\App\Http\Controllers\Trabajador\NotificacionController::class, 'marcarLeida'])->name('notificaciones.leer');
    Route::post('/notificaciones/leer-todas', [\App\Http\Controllers\Trabajador\NotificacionController::class, 'marcarTodasLeidas'])->name('notificaciones.leerTodas');

});

//Localidades
Route::get('/api/localidades/{provincia}', function (\App\Models\Provincia $provincia) {
    return $provincia->localidades()->orderBy('nombre')->get(['id', 'nombre']);
});

// Empresa
Route::middleware(['auth', 'es.empresa'])->prefix('empresa')->name('empresa.')->group(function () {
    Route::get('/dashboard', fn() => view('empresa.dashboard'))->name('dashboard');
    Route::resource('ofertas', \App\Http\Controllers\Empresa\OfertaController::class);
    Route::get('/postulaciones', [\App\Http\Controllers\Empresa\PostulacionController::class, 'index'])->name('postulaciones.index');
    Route::patch('/postulaciones/{postulacion}/estado', [\App\Http\Controllers\Empresa\PostulacionController::class, 'cambiarEstado'])->name('postulaciones.estado');
   
    Route::get('/perfil', [\App\Http\Controllers\Empresa\PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [\App\Http\Controllers\Empresa\PerfilController::class, 'update'])->name('perfil.update');
});

// Admin
Route::middleware(['auth', 'es.admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');

    // Usuarios
    Route::get('/usuarios', [\App\Http\Controllers\Admin\UsuarioController::class, 'index'])->name('usuarios.index');
    Route::patch('/usuarios/{user}/estado', [\App\Http\Controllers\Admin\UsuarioController::class, 'cambiarEstado'])->name('usuarios.estado');

    // Empresas
    Route::get('/empresas', [\App\Http\Controllers\Admin\EmpresaController::class, 'index'])->name('empresas.index');
    Route::patch('/empresas/{empresa}/estado', [\App\Http\Controllers\Admin\EmpresaController::class, 'cambiarEstado'])->name('empresas.estado');

    // Ofertas
    Route::get('/ofertas', [\App\Http\Controllers\Admin\OfertaAdminController::class, 'index'])->name('ofertas.index');
    Route::patch('/ofertas/{oferta}/estado', [\App\Http\Controllers\Admin\OfertaAdminController::class, 'cambiarEstado'])->name('ofertas.estado');
});


require __DIR__.'/auth.php';
