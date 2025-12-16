<?php

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::prefix('tienda')->name('tienda.')->group(function () {
    Route::get('/', [App\Http\Controllers\TiendaController::class, 'home'])->name('home');
    Route::get('/catalogo', [App\Http\Controllers\TiendaController::class, 'catalogo'])->name('catalogo');
    Route::get('/producto/{producto}', [App\Http\Controllers\TiendaController::class, 'producto'])->name('producto');
    Route::get('/t/{slug}', [App\Http\Controllers\TiendaController::class, 'perfil'])->name('perfil');
    
    Route::get('/carrito', [App\Http\Controllers\CarritoController::class, 'index'])->name('carrito');
    Route::post('/carrito/agregar/{producto}', [App\Http\Controllers\CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::patch('/carrito/actualizar/{producto}', [App\Http\Controllers\CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::delete('/carrito/eliminar/{producto}', [App\Http\Controllers\CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::post('/carrito/vaciar', [App\Http\Controllers\CarritoController::class, 'vaciar'])->name('carrito.vaciar');
    
    Route::middleware(['auth', 'role:cliente'])->group(function () {
        Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout/cotizar', [App\Http\Controllers\CheckoutController::class, 'cotizar'])->name('checkout.cotizar');
        Route::post('/checkout/confirmar', [App\Http\Controllers\CheckoutController::class, 'confirmar'])->name('checkout.confirmar');
        Route::get('/mis-pedidos', [App\Http\Controllers\PedidoClienteController::class, 'index'])->name('mis-pedidos');
        Route::get('/mis-pedidos/{pedido}', [App\Http\Controllers\PedidoClienteController::class, 'show'])->name('pedido.detalle');
        Route::get('/mi-perfil', [App\Http\Controllers\PerfilController::class, 'index'])->name('mi-perfil');
        Route::put('/mi-perfil', [App\Http\Controllers\PerfilController::class, 'update'])->name('mi-perfil.update');
    });
});

Route::get('/', function () {
    return redirect()->route('tienda.home');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']);
    
    Route::resource('productos', App\Http\Controllers\Admin\ProductoController::class);
    
    Route::resource('almacenes', App\Http\Controllers\Admin\AlmacenController::class)->parameters([
        'almacenes' => 'almacen'
    ]);
    
    Route::resource('pedidos', App\Http\Controllers\Admin\PedidoController::class)->only([
        'index', 'show', 'update'
    ]);
    Route::patch('/pedidos/{pedido}/estado', [App\Http\Controllers\Admin\PedidoController::class, 'cambiarEstado'])
        ->name('pedidos.estado');
    
    Route::resource('clientes', App\Http\Controllers\Admin\ClienteController::class)->only([
        'index', 'show'
    ]);
    
    // Tiendas afiliadas (B2B - Admin como proveedor)
    Route::get('/tiendas', [App\Http\Controllers\Admin\TiendaController::class, 'index'])->name('tiendas.index');
    Route::get('/tiendas/{tienda}', [App\Http\Controllers\Admin\TiendaController::class, 'show'])->name('tiendas.show');
    
    Route::resource('solicitudes', App\Http\Controllers\Admin\SolicitudController::class);
    Route::patch('/solicitudes/{solicitud}/recibir', [App\Http\Controllers\Admin\SolicitudController::class, 'recibir'])
        ->name('solicitudes.recibir');
    
    // Propuestas de productos (tiendas proponen, admin aprueba)
    Route::get('/propuestas', [App\Http\Controllers\Admin\PropuestaController::class, 'index'])->name('propuestas.index');
    Route::get('/propuestas/{producto}', [App\Http\Controllers\Admin\PropuestaController::class, 'show'])->name('propuestas.show');
    Route::patch('/propuestas/{producto}/aprobar', [App\Http\Controllers\Admin\PropuestaController::class, 'aprobar'])->name('propuestas.aprobar');
    Route::patch('/propuestas/{producto}/rechazar', [App\Http\Controllers\Admin\PropuestaController::class, 'rechazar'])->name('propuestas.rechazar');
    
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ReporteController::class, 'index'])->name('index');
        
        Route::get('/ventas', [App\Http\Controllers\Admin\ReporteController::class, 'ventas'])->name('ventas');
        Route::get('/ventas/pdf', [App\Http\Controllers\Admin\ReporteController::class, 'ventasPdf'])->name('ventas.pdf');
        Route::get('/ventas/excel', [App\Http\Controllers\Admin\ReporteController::class, 'ventasExcel'])->name('ventas.excel');
        
        Route::get('/productos', [App\Http\Controllers\Admin\ReporteController::class, 'productos'])->name('productos');
        Route::get('/productos/pdf', [App\Http\Controllers\Admin\ReporteController::class, 'productosPdf'])->name('productos.pdf');
        Route::get('/productos/excel', [App\Http\Controllers\Admin\ReporteController::class, 'productosExcel'])->name('productos.excel');
        
        Route::get('/stock-bajo', [App\Http\Controllers\Admin\ReporteController::class, 'stockBajo'])->name('stock-bajo');
        Route::get('/stock-bajo/pdf', [App\Http\Controllers\Admin\ReporteController::class, 'stockBajoPdf'])->name('stock-bajo.pdf');
        Route::get('/stock-bajo/excel', [App\Http\Controllers\Admin\ReporteController::class, 'stockBajoExcel'])->name('stock-bajo.excel');
    });
});

Route::prefix('tienda/panel')->name('tienda.panel.')->middleware(['auth', 'es.tienda'])->group(function () {
    Route::get('/', [App\Http\Controllers\Tienda\DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('productos', App\Http\Controllers\Tienda\ProductoController::class);
    
    Route::resource('almacenes', App\Http\Controllers\Tienda\AlmacenController::class)->parameters([
        'almacenes' => 'almacen'
    ]);
    
    Route::get('/ventas', [App\Http\Controllers\Tienda\VentaController::class, 'index'])->name('ventas.index');
    Route::get('/ventas/{pedido}', [App\Http\Controllers\Tienda\VentaController::class, 'show'])->name('ventas.show');
    Route::patch('/ventas/{pedido}/confirmar', [App\Http\Controllers\Tienda\VentaController::class, 'confirmar'])->name('ventas.confirmar');
    Route::patch('/ventas/{pedido}/estado', [App\Http\Controllers\Tienda\VentaController::class, 'cambiarEstado'])->name('ventas.estado');
    
    Route::resource('solicitudes', App\Http\Controllers\Tienda\SolicitudController::class);
    
    // Catálogo de productos maestros del admin
    Route::get('/catalogo-admin', [App\Http\Controllers\Tienda\CatalogoAdminController::class, 'index'])->name('catalogo-admin');
    Route::post('/catalogo-admin/{producto}/adoptar', [App\Http\Controllers\Tienda\CatalogoAdminController::class, 'adoptar'])->name('catalogo-admin.adoptar');
    
    // Proponer nuevo producto
    Route::get('/proponer-producto', [App\Http\Controllers\Tienda\PropuestaController::class, 'create'])->name('proponer-producto');
    Route::post('/proponer-producto', [App\Http\Controllers\Tienda\PropuestaController::class, 'store'])->name('proponer-producto.store');
    Route::get('/mis-propuestas', [App\Http\Controllers\Tienda\PropuestaController::class, 'index'])->name('mis-propuestas');
    
    // Cierre de Caja
    Route::get('/caja', [App\Http\Controllers\Tienda\CajaController::class, 'index'])->name('caja');
    Route::post('/caja/apertura', [App\Http\Controllers\Tienda\CajaController::class, 'apertura'])->name('caja.apertura');
    Route::post('/caja/cierre', [App\Http\Controllers\Tienda\CajaController::class, 'cierre'])->name('caja.cierre');
    Route::get('/caja/historial', [App\Http\Controllers\Tienda\CajaController::class, 'historial'])->name('caja.historial');
    Route::get('/caja/{cierre}/pdf', [App\Http\Controllers\Tienda\CajaController::class, 'exportPdf'])->name('caja.pdf');
    Route::get('/caja/{cierre}/excel', [App\Http\Controllers\Tienda\CajaController::class, 'exportExcel'])->name('caja.excel');
    
    Route::get('/configuracion', [App\Http\Controllers\Tienda\ConfiguracionController::class, 'index'])->name('configuracion');
    Route::put('/configuracion', [App\Http\Controllers\Tienda\ConfiguracionController::class, 'update'])->name('configuracion.update');
});