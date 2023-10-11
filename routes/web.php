<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\PresentacioneController;
use App\Http\Controllers\ProductoController;
use App\Models\Presentacione;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('template');
});

Route::view('/panel', 'panel.index')->name('panel');

// Route::resource('categorias', CategoriaController::class);
// Route::resource('marcas', MarcaController::class);
// Route::resource('presentaciones', PresentacioneController::class);

Route::resources([
    'categorias' => CategoriaController::class,
    'presentaciones' => PresentacioneController::class,
    'marcas' => MarcaController::class,
    'productos' => ProductoController::class
]);

/** ********************************************************************  */

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/401', function () {
    return view('pages.401');
});
Route::get('/404', function () {
    return view('pages.404');
});
Route::get('/500', function () {
    return view('pages.500');
});
