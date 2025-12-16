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
    
    Route::get('/carrito', [App\Http\Controllers\CarritoController::class, 'index'])->name('carrito');
    Route::post('/carrito/agregar/{producto}', [App\Http\Controllers\CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::patch('/carrito/actualizar/{producto}', [App\Http\Controllers\CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::delete('/carrito/eliminar/{producto}', [App\Http\Controllers\CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::post('/carrito/vaciar', [App\Http\Controllers\CarritoController::class, 'vaciar'])->name('carrito.vaciar');
    
    Route::middleware(['auth', 'role:cliente'])->group(function () {
        Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout/cotizar', [App\Http\Controllers\CheckoutController::class, 'cotizar'])->name('checkout.cotizar');
        Route::post('/checkout/generar-qr', [App\Http\Controllers\CheckoutController::class, 'generarQR'])->name('checkout.generar-qr');
        Route::post('/checkout/confirmar', [App\Http\Controllers\CheckoutController::class, 'confirmar'])->name('checkout.confirmar');
        
        // Rutas de callback de Mercado Pago
        Route::get('/checkout/success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
        Route::get('/checkout/failure', [App\Http\Controllers\CheckoutController::class, 'failure'])->name('checkout.failure');
        Route::get('/checkout/pending', [App\Http\Controllers\CheckoutController::class, 'pending'])->name('checkout.pending');
    });
    
    // Webhook de Mercado Pago (sin autenticación, solo verificación de IP)
    Route::post('/checkout/webhook', [App\Http\Controllers\CheckoutController::class, 'webhook'])->name('checkout.webhook');
        Route::get('/mis-pedidos', [App\Http\Controllers\PedidoClienteController::class, 'index'])->name('mis-pedidos');
        Route::get('/mis-pedidos/{pedido}', [App\Http\Controllers\PedidoClienteController::class, 'show'])->name('pedido.detalle');
        Route::get('/mi-perfil', [App\Http\Controllers\PerfilController::class, 'index'])->name('perfil');
        Route::put('/mi-perfil', [App\Http\Controllers\PerfilController::class, 'update'])->name('perfil.update');
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
    
    Route::resource('solicitudes', App\Http\Controllers\Admin\SolicitudController::class);
    Route::patch('/solicitudes/{solicitud}/recibir', [App\Http\Controllers\Admin\SolicitudController::class, 'recibir'])
        ->name('solicitudes.recibir');
    
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