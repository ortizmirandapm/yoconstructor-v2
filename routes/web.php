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
    Route::get('/perfil', [\App\Http\Controllers\Trabajador\PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [\App\Http\Controllers\Trabajador\PerfilController::class, 'update'])->name('perfil.update');
    Route::get('/postulaciones', [\App\Http\Controllers\Trabajador\PostulacionController::class, 'index'])->name('postulaciones.index');
    Route::post('/postulaciones/{postulacion}/cancelar', [\App\Http\Controllers\Trabajador\PostulacionController::class, 'cancelar'])->name('postulaciones.cancelar');
    //ofertas 
    Route::get('/ofertas/{oferta}/postular', [\App\Http\Controllers\Trabajador\PostulacionController::class, 'crear'])->name('postulaciones.crear');
    Route::post('/ofertas/{oferta}/postular', [\App\Http\Controllers\Trabajador\PostulacionController::class, 'store'])->name('postulaciones.store');
    // Notificaciones
    Route::get('/notificaciones', [\App\Http\Controllers\Trabajador\NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::post('/notificaciones/{id}/leer', [\App\Http\Controllers\Trabajador\NotificacionController::class, 'marcarLeida'])->name('notificaciones.leer');
    Route::post('/notificaciones/leer-todas', [\App\Http\Controllers\Trabajador\NotificacionController::class, 'marcarTodasLeidas'])->name('notificaciones.leerTodas');
    // Configuracion
    Route::get('/configuracion', [\App\Http\Controllers\Trabajador\ConfiguracionController::class, 'edit'])->name('configuracion.edit');
    Route::post('/configuracion/visibilidad', [\App\Http\Controllers\Trabajador\ConfiguracionController::class, 'toggleVisibilidad'])->name('configuracion.visibilidad');
    Route::post('/configuracion/password', [\App\Http\Controllers\Trabajador\ConfiguracionController::class, 'cambiarPassword'])->name('configuracion.password');
    Route::post('/configuracion/eliminar', [\App\Http\Controllers\Trabajador\ConfiguracionController::class, 'eliminarCuenta'])->name('configuracion.eliminar');

});

//Localidades
Route::get('/api/localidades/{provincia}', function (\App\Models\Provincia $provincia) {
    return $provincia->localidades()->orderBy('nombre')->get(['id', 'nombre']);
});

// Empresa
Route::middleware(['auth', 'es.empresa'])->prefix('empresa')->name('empresa.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Empresa\EmpresaDashboardController::class, 'index'])->name('dashboard');
    Route::resource('ofertas', \App\Http\Controllers\Empresa\OfertaController::class)->except('show');
    Route::patch('/ofertas/{oferta}/toggle', [\App\Http\Controllers\Empresa\OfertaController::class, 'toggleEstado'])->name('ofertas.toggle');
    Route::get('/borradores', [\App\Http\Controllers\Empresa\OfertaController::class, 'borradores'])->name('borradores.index');
    Route::patch('/borradores/{oferta}/restaurar', [\App\Http\Controllers\Empresa\OfertaController::class, 'restaurar'])->name('borradores.restaurar');
    Route::delete('/borradores/{oferta}', [\App\Http\Controllers\Empresa\OfertaController::class, 'eliminarDefinitiva'])->name('borradores.destroy');
    Route::get('/postulaciones', [\App\Http\Controllers\Empresa\PostulacionController::class, 'index'])->name('postulaciones.index');
    Route::patch('/postulaciones/{postulacion}/estado', [\App\Http\Controllers\Empresa\PostulacionController::class, 'cambiarEstado'])->name('postulaciones.estado');
    Route::post('/postulaciones/{postulacion}/notas', [\App\Http\Controllers\Empresa\PostulacionController::class, 'guardarNotas'])->name('postulaciones.notas');
    Route::get('/postulaciones/{trabajador}/perfil', [\App\Http\Controllers\Empresa\PostulacionController::class, 'perfil'])->name('postulaciones.perfil');

    Route::get('/perfil', [\App\Http\Controllers\Empresa\PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [\App\Http\Controllers\Empresa\PerfilController::class, 'update'])->name('perfil.update');

    Route::get('/configuracion', [\App\Http\Controllers\Empresa\ConfiguracionController::class, 'edit'])->name('configuracion.edit');
    Route::post('/configuracion/password', [\App\Http\Controllers\Empresa\ConfiguracionController::class, 'cambiarPassword'])->name('configuracion.password');
    Route::post('/configuracion/privacidad', [\App\Http\Controllers\Empresa\ConfiguracionController::class, 'togglePrivacidad'])->name('configuracion.privacidad');
    Route::post('/configuracion/eliminar', [\App\Http\Controllers\Empresa\ConfiguracionController::class, 'eliminarCuenta'])->name('configuracion.eliminar');
});

// Admin
Route::middleware(['auth', 'es.admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');

    // Usuarios
    Route::get('/usuarios', [\App\Http\Controllers\Admin\UsuarioController::class, 'index'])->name('usuarios.index');
    Route::patch('/usuarios/{user}/estado', [\App\Http\Controllers\Admin\UsuarioController::class, 'cambiarEstado'])->name('usuarios.estado');

    // Empresas
    Route::get('/empresas', [\App\Http\Controllers\Admin\EmpresaController::class, 'index'])->name('empresas.index');
    Route::post('/empresas', [\App\Http\Controllers\Admin\EmpresaController::class, 'store'])->name('empresas.store');
    Route::get('/empresas/{empresa}/edit', [\App\Http\Controllers\Admin\EmpresaController::class, 'edit'])->name('empresas.edit');
    Route::put('/empresas/{empresa}', [\App\Http\Controllers\Admin\EmpresaController::class, 'update'])->name('empresas.update');
    Route::patch('/empresas/{empresa}/estado', [\App\Http\Controllers\Admin\EmpresaController::class, 'cambiarEstado'])->name('empresas.estado');
    Route::delete('/empresas/{empresa}', [\App\Http\Controllers\Admin\EmpresaController::class, 'destroy'])->name('empresas.destroy');

    // Trabajadores
    Route::get('/trabajadores', [\App\Http\Controllers\Admin\TrabajadorController::class, 'index'])->name('trabajadores.index');
    Route::post('/trabajadores', [\App\Http\Controllers\Admin\TrabajadorController::class, 'store'])->name('trabajadores.store');
    Route::get('/trabajadores/{trabajador}/edit', [\App\Http\Controllers\Admin\TrabajadorController::class, 'edit'])->name('trabajadores.edit');
    Route::put('/trabajadores/{trabajador}', [\App\Http\Controllers\Admin\TrabajadorController::class, 'update'])->name('trabajadores.update');
    Route::patch('/trabajadores/{trabajador}/estado', [\App\Http\Controllers\Admin\TrabajadorController::class, 'cambiarEstado'])->name('trabajadores.estado');
    Route::delete('/trabajadores/{trabajador}', [\App\Http\Controllers\Admin\TrabajadorController::class, 'destroy'])->name('trabajadores.destroy');

    // Administradores
    Route::get('/administradores', [\App\Http\Controllers\Admin\AdministradorController::class, 'index'])->name('administradores.index');
    Route::post('/administradores', [\App\Http\Controllers\Admin\AdministradorController::class, 'store'])->name('administradores.store');
    Route::get('/administradores/{user}/edit', [\App\Http\Controllers\Admin\AdministradorController::class, 'edit'])->name('administradores.edit');
    Route::put('/administradores/{user}', [\App\Http\Controllers\Admin\AdministradorController::class, 'update'])->name('administradores.update');
    Route::patch('/administradores/{user}/estado', [\App\Http\Controllers\Admin\AdministradorController::class, 'cambiarEstado'])->name('administradores.estado');
    Route::delete('/administradores/{user}', [\App\Http\Controllers\Admin\AdministradorController::class, 'destroy'])->name('administradores.destroy');

    // Ofertas
    Route::get('/ofertas', [\App\Http\Controllers\Admin\OfertaAdminController::class, 'index'])->name('ofertas.index');
    Route::get('/ofertas/{oferta}/edit', [\App\Http\Controllers\Admin\OfertaAdminController::class, 'edit'])->name('ofertas.edit');
    Route::put('/ofertas/{oferta}', [\App\Http\Controllers\Admin\OfertaAdminController::class, 'update'])->name('ofertas.update');
    Route::patch('/ofertas/{oferta}/estado', [\App\Http\Controllers\Admin\OfertaAdminController::class, 'cambiarEstado'])->name('ofertas.estado');
    Route::delete('/ofertas/{oferta}', [\App\Http\Controllers\Admin\OfertaAdminController::class, 'destroy'])->name('ofertas.destroy');

    // Rubros
    Route::get('/rubros', [\App\Http\Controllers\Admin\RubroController::class, 'index'])->name('rubros.index');
    Route::post('/rubros', [\App\Http\Controllers\Admin\RubroController::class, 'store'])->name('rubros.store');
    Route::get('/rubros/{rubro}/edit', [\App\Http\Controllers\Admin\RubroController::class, 'edit'])->name('rubros.edit');
    Route::put('/rubros/{rubro}', [\App\Http\Controllers\Admin\RubroController::class, 'update'])->name('rubros.update');
    Route::patch('/rubros/{rubro}/estado', [\App\Http\Controllers\Admin\RubroController::class, 'cambiarEstado'])->name('rubros.estado');
    Route::delete('/rubros/{rubro}', [\App\Http\Controllers\Admin\RubroController::class, 'destroy'])->name('rubros.destroy');

    // Especialidades
    Route::get('/especialidades', [\App\Http\Controllers\Admin\EspecialidadController::class, 'index'])->name('especialidades.index');
    Route::post('/especialidades', [\App\Http\Controllers\Admin\EspecialidadController::class, 'store'])->name('especialidades.store');
    Route::get('/especialidades/{especialidad}/edit', [\App\Http\Controllers\Admin\EspecialidadController::class, 'edit'])->name('especialidades.edit');
    Route::put('/especialidades/{especialidad}', [\App\Http\Controllers\Admin\EspecialidadController::class, 'update'])->name('especialidades.update');
    Route::patch('/especialidades/{especialidad}/estado', [\App\Http\Controllers\Admin\EspecialidadController::class, 'cambiarEstado'])->name('especialidades.estado');
    Route::delete('/especialidades/{especialidad}', [\App\Http\Controllers\Admin\EspecialidadController::class, 'destroy'])->name('especialidades.destroy');
});


require __DIR__.'/auth.php';
