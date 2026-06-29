<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdministradorController;
use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\EspecialidadController;
use App\Http\Controllers\Admin\OfertaAdminController;
use App\Http\Controllers\Admin\RubroController;
use App\Http\Controllers\Admin\TrabajadorController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Empresa\EmpresaDashboardController;
use App\Http\Controllers\Empresa\OfertaController;
use App\Http\Controllers\OfertaPublicaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Trabajador\ConfiguracionController;
use App\Http\Controllers\Trabajador\NotificacionController;
use App\Http\Controllers\Trabajador\PerfilController;
use App\Http\Controllers\Trabajador\PostulacionController;
use App\Http\Controllers\WelcomeController;
use App\Models\Empresa;
use App\Models\Oferta;
use App\Models\Provincia;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('home');

Route::get('/nosotros', function () {
    $totalTrabajadores = User::where('tipo', 'trabajador')->where('estado', true)->count();
    $totalEmpresas = Empresa::where('estado', 'activo')->count();
    $totalOfertas = Oferta::where('estado', 'Activa')->count();

    return view('nosotros', compact('totalTrabajadores', 'totalEmpresas', 'totalOfertas'));
})->name('nosotros');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Ruta ofertas
Route::get('/ofertas', [OfertaPublicaController::class, 'index'])->name('ofertas.index');
Route::get('/ofertas/{oferta}', [OfertaPublicaController::class, 'show'])->name('ofertas.show');

// Trabajador
Route::middleware(['auth', 'es.trabajador'])->prefix('trabajador')->name('trabajador.')->group(function () {
    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
    Route::get('/postulaciones', [PostulacionController::class, 'index'])->name('postulaciones.index');
    Route::post('/postulaciones/{postulacion}/cancelar', [PostulacionController::class, 'cancelar'])->name('postulaciones.cancelar');
    // ofertas
    Route::get('/ofertas/{oferta}/postular', [PostulacionController::class, 'crear'])->name('postulaciones.crear');
    Route::post('/ofertas/{oferta}/postular', [PostulacionController::class, 'store'])->name('postulaciones.store');
    // Notificaciones
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::post('/notificaciones/{id}/leer', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.leer');
    Route::post('/notificaciones/leer-todas', [NotificacionController::class, 'marcarTodasLeidas'])->name('notificaciones.leerTodas');
    // Configuracion
    Route::get('/configuracion', [ConfiguracionController::class, 'edit'])->name('configuracion.edit');
    Route::post('/configuracion/visibilidad', [ConfiguracionController::class, 'toggleVisibilidad'])->name('configuracion.visibilidad');
    Route::post('/configuracion/password', [ConfiguracionController::class, 'cambiarPassword'])->name('configuracion.password');
    Route::post('/configuracion/eliminar', [ConfiguracionController::class, 'eliminarCuenta'])->name('configuracion.eliminar');

});

// Localidades
Route::get('/api/localidades/{provincia}', function (Provincia $provincia) {
    return $provincia->localidades()->orderBy('nombre')->get(['id', 'nombre']);
});

// Empresa
Route::middleware(['auth', 'es.empresa'])->prefix('empresa')->name('empresa.')->group(function () {
    Route::get('/dashboard', [EmpresaDashboardController::class, 'index'])->name('dashboard');
    Route::resource('ofertas', OfertaController::class)->except('show');
    Route::patch('/ofertas/{oferta}/toggle', [OfertaController::class, 'toggleEstado'])->name('ofertas.toggle');
    Route::get('/borradores', [OfertaController::class, 'borradores'])->name('borradores.index');
    Route::patch('/borradores/{oferta}/restaurar', [OfertaController::class, 'restaurar'])->name('borradores.restaurar');
    Route::delete('/borradores/{oferta}', [OfertaController::class, 'eliminarDefinitiva'])->name('borradores.destroy');
    Route::get('/postulaciones', [App\Http\Controllers\Empresa\PostulacionController::class, 'index'])->name('postulaciones.index');
    Route::patch('/postulaciones/{postulacion}/estado', [App\Http\Controllers\Empresa\PostulacionController::class, 'cambiarEstado'])->name('postulaciones.estado');
    Route::post('/postulaciones/{postulacion}/notas', [App\Http\Controllers\Empresa\PostulacionController::class, 'guardarNotas'])->name('postulaciones.notas');
    Route::get('/postulaciones/{trabajador}/perfil', [App\Http\Controllers\Empresa\PostulacionController::class, 'perfil'])->name('postulaciones.perfil');

    Route::get('/perfil', [App\Http\Controllers\Empresa\PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [App\Http\Controllers\Empresa\PerfilController::class, 'update'])->name('perfil.update');

    Route::get('/configuracion', [App\Http\Controllers\Empresa\ConfiguracionController::class, 'edit'])->name('configuracion.edit');
    Route::post('/configuracion/password', [App\Http\Controllers\Empresa\ConfiguracionController::class, 'cambiarPassword'])->name('configuracion.password');
    Route::post('/configuracion/privacidad', [App\Http\Controllers\Empresa\ConfiguracionController::class, 'togglePrivacidad'])->name('configuracion.privacidad');
    Route::post('/configuracion/eliminar', [App\Http\Controllers\Empresa\ConfiguracionController::class, 'eliminarCuenta'])->name('configuracion.eliminar');
});

// Admin
Route::middleware(['auth', 'es.admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::patch('/usuarios/{user}/estado', [UsuarioController::class, 'cambiarEstado'])->name('usuarios.estado');

    // Empresas
    Route::get('/empresas', [EmpresaController::class, 'index'])->name('empresas.index');
    Route::post('/empresas', [EmpresaController::class, 'store'])->name('empresas.store');
    Route::get('/empresas/{empresa}/edit', [EmpresaController::class, 'edit'])->name('empresas.edit');
    Route::put('/empresas/{empresa}', [EmpresaController::class, 'update'])->name('empresas.update');
    Route::patch('/empresas/{empresa}/estado', [EmpresaController::class, 'cambiarEstado'])->name('empresas.estado');
    Route::delete('/empresas/{empresa}', [EmpresaController::class, 'destroy'])->name('empresas.destroy');

    // Trabajadores
    Route::get('/trabajadores', [TrabajadorController::class, 'index'])->name('trabajadores.index');
    Route::post('/trabajadores', [TrabajadorController::class, 'store'])->name('trabajadores.store');
    Route::get('/trabajadores/{trabajador}/edit', [TrabajadorController::class, 'edit'])->name('trabajadores.edit');
    Route::put('/trabajadores/{trabajador}', [TrabajadorController::class, 'update'])->name('trabajadores.update');
    Route::patch('/trabajadores/{trabajador}/estado', [TrabajadorController::class, 'cambiarEstado'])->name('trabajadores.estado');
    Route::delete('/trabajadores/{trabajador}', [TrabajadorController::class, 'destroy'])->name('trabajadores.destroy');

    // Administradores
    Route::get('/administradores', [AdministradorController::class, 'index'])->name('administradores.index');
    Route::post('/administradores', [AdministradorController::class, 'store'])->name('administradores.store');
    Route::get('/administradores/{user}/edit', [AdministradorController::class, 'edit'])->name('administradores.edit');
    Route::put('/administradores/{user}', [AdministradorController::class, 'update'])->name('administradores.update');
    Route::patch('/administradores/{user}/estado', [AdministradorController::class, 'cambiarEstado'])->name('administradores.estado');
    Route::delete('/administradores/{user}', [AdministradorController::class, 'destroy'])->name('administradores.destroy');

    // Ofertas
    Route::get('/ofertas', [OfertaAdminController::class, 'index'])->name('ofertas.index');
    Route::get('/ofertas/{oferta}/edit', [OfertaAdminController::class, 'edit'])->name('ofertas.edit');
    Route::put('/ofertas/{oferta}', [OfertaAdminController::class, 'update'])->name('ofertas.update');
    Route::patch('/ofertas/{oferta}/estado', [OfertaAdminController::class, 'cambiarEstado'])->name('ofertas.estado');
    Route::delete('/ofertas/{oferta}', [OfertaAdminController::class, 'destroy'])->name('ofertas.destroy');

    // Rubros
    Route::get('/rubros', [RubroController::class, 'index'])->name('rubros.index');
    Route::post('/rubros', [RubroController::class, 'store'])->name('rubros.store');
    Route::get('/rubros/{rubro}/edit', [RubroController::class, 'edit'])->name('rubros.edit');
    Route::put('/rubros/{rubro}', [RubroController::class, 'update'])->name('rubros.update');
    Route::patch('/rubros/{rubro}/estado', [RubroController::class, 'cambiarEstado'])->name('rubros.estado');
    Route::delete('/rubros/{rubro}', [RubroController::class, 'destroy'])->name('rubros.destroy');

    // Especialidades
    Route::get('/especialidades', [EspecialidadController::class, 'index'])->name('especialidades.index');
    Route::post('/especialidades', [EspecialidadController::class, 'store'])->name('especialidades.store');
    Route::get('/especialidades/{especialidad}/edit', [EspecialidadController::class, 'edit'])->name('especialidades.edit');
    Route::put('/especialidades/{especialidad}', [EspecialidadController::class, 'update'])->name('especialidades.update');
    Route::patch('/especialidades/{especialidad}/estado', [EspecialidadController::class, 'cambiarEstado'])->name('especialidades.estado');
    Route::delete('/especialidades/{especialidad}', [EspecialidadController::class, 'destroy'])->name('especialidades.destroy');
});

require __DIR__.'/auth.php';
