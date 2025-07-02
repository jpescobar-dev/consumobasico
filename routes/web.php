<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CfinancieroController;
use App\Http\Controllers\CcostoController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\LicitacionController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\DteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteMedidorController;
use App\Http\Controllers\DetalledteController;
use App\Http\Controllers\CdpController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

use App\Http\Controllers\ExcelPreviewController;






Route::resource('users', UserController::class)->names('users');

// Route::get('/admin', function () {
//     return 'Panel de admin';
// })->middleware('role:admin');

Route::resource('roles', RoleController::class)->middleware(['auth', 'role:admin']);


Route::get('/usuarios', [UserController::class, 'index'])->middleware('permission:users.index');

Route::get('/admin', fn() => 'Panel Admin')->middleware('role:admin');


Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'verified'])->name('welcome');



Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

});

require __DIR__.'/auth.php';


// Ruta para la página principal de consulta de órdenes de compra
Route::get('/ordenesdecompra', function () {
    return view('purchase_orders.index');
});



Route::get('/api-oc', function () {
    return view('apis.api-ordenescompras');
})->name('api-ordenescompras');


Route::get('/api-licitacion', function () {
    return view('apis.api-licitaciones');
})->name('api-licitaciones');



// Rutas de autenticación
Auth::routes();

Route::resource('ccostos', CcostoController::class)->names('ccostos');
Route::resource('cfinancieros', CfinancieroController::class)->names('cfinancieros');

Route::resource('items', ItemController::class)->names('items');
Route::resource('catalogos', CatalogoController::class)->names('catalogos');
Route::resource('proveedores', ProveedorController::class)->parameters(['proveedores' => 'proveedor']);

Route::resource('licitaciones', LicitacionController::class)->names('licitaciones')->parameters(['licitaciones'=>'licitacion']);

Route::resource('estados', EstadoController::class)->names('estados');
Route::resource('dtes', DteController::class)->names('dtes');

Route::get('/importar', [DteController::class, 'showImportForm'])->name('dtes.import.form');
Route::post('/importar', [DteController::class, 'import'])->name('dtes.import');
Route::get('/dtes-importados', [DteController::class, 'dtesImportados'])->name('dtes.importados');

Route::resource('proyectos', ProyectoController::class)->names('proyectos');
Route::resource('cdps', CdpController::class)->names('cdps');

Route::resource('clientesmedidores', ClienteMedidorController::class)->names('clientesmedidores')->parameters(['clientesmedidores' => 'clientemedidor']);

// ****** CONSUMOS BASICOS ******************//
    Route::get('/consultas/electricidad', [ConsultaController::class, 'DtesElectricidad'])->name('consultas.electricidad.index');
    Route::get('/consultas/agua', [ConsultaController::class, 'DtesAguaPatagonia'])->name('consultas.agua.index');
 Route::resource('detalledtes', DetalledteController::class);

Route::resource('ordenescompras', OrdenCompraController::class)->names('ordenescompras');
Route::resource('clientesmedidores', ClienteMedidorController::class)->names('clientesmedidores')->parameters(['clientesmedidores' => 'ClienteMedidor']);

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/usuarios', \App\Http\Livewire\Admin\Users::class)->name('admin.usuarios');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/roles', \App\Http\Livewire\Admin\Roles::class)->name('admin.roles');
});

Route::middleware(['auth', 'role:admin'])->get('/admin/roles-permisos', \App\Http\Livewire\Admin\RolesPermisos::class)->name('admin.roles-permisos');








Route::get('/importar-excel', [ExcelPreviewController::class, 'showForm'])->name('excel.form');
Route::post('/importar-excel', [ExcelPreviewController::class, 'import'])->name('excel.import');



















