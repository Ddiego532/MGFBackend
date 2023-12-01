<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\TransbankController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/mostrarUsuarios',[UsuarioController::class,'mostrarUsuarios']);
Route::get('/mostrarArticulos',[TiendaController::class,'mostrarArticulos']);
Route::get('/mostrarSubastas',[TiendaController::class,'mostrarSubastas']);
Route::post('/crearUsuario',[UsuarioController::class,'crearUsuario']);
Route::post('/login',[UsuarioController::class,'login']);
Route::get('/pruebaCorreo',[UsuarioController::class,'pruebaCorreo']);
Route::get('/mostrarSubasta/{id_subasta}', [TiendaController::class, 'mostrarSubasta'])->where('id_subasta', '.*');
Route::get('/mostrarUsuario/{email}', [UsuarioController::class, 'mostrarUsuario'])->where('email', '.*');
Route::get('/mostrarArticulo/{id_articulo}', [TiendaController::class, 'mostrarArticulo'])->where('id_articulo', '.*');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/actualizarUsuario',[UsuarioController::class,'actualizarUsuario']);
    Route::get('/mostrarDueno/{dueno}', [TiendaController::class, 'mostrarDueno'])->where('dueno', '.*');
    Route::get('/mostrarPerfil', [UsuarioController::class, 'mostrarPerfil']);
    Route::get('/verCartera',[UsuarioController::class,'verCartera']);
    Route::get('/mostrarCompras', [TiendaController::class, 'mostrarCompras']);
    Route::get('/eliminarUsuario',[UsuarioController::class,'eliminarUsuario']);
    Route::post('/crearArticulo',[TiendaController::class,'crearArticulo']);
    Route::get('/logout',[UsuarioController::class,'logout']);
    Route::get('/mostrarCarrito', [TiendaController::class, 'mostrarCarrito']);
    Route::get('/cerrarSubasta/{id_subasta}', [TiendaController::class, 'cerrarSubasta'])->where('id_subasta', '.*');
    Route::get('/crearSubasta/{id_articulo}', [TiendaController::class, 'crearSubasta'])->where('id_articulo', '.*');
    Route::post('/agregarSubastador', [TiendaController::class, 'agregarSubastador']);
    Route::get('/agregarCarrito/{id_articulo}', [TiendaController::class, 'agregarCarrito'])->where('id_articulo', '.*');
    Route::get('/removerCarrito/{id_articulo}', [TiendaController::class, 'removerCarrito'])->where('id_articulo', '.*');
    Route::post('/iniciarCompra',[TransbankController::class,'iniciarCompra']);
    Route::post('/iniciarDeposito',[TransbankController::class,'iniciarDeposito']);
});



Route::get('/confirmarPago',[TransbankController::class,'confirmarPago'])->name('confirmarPago');