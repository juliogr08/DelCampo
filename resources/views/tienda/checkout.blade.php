@extends('tienda.layouts.app')

@section('title', 'Checkout')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #mapa {
        height: 350px;
        border-radius: 12px;
        border: 2px solid var(--verde-suave);
        z-index: 1;
    }
    .ubicacion-info {
        background: var(--verde-suave);
        border-radius: 8px;
        padding: 12px;
        margin-top: 10px;
    }
    .ubicacion-info i {
        color: var(--verde-campo);
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-lock me-2"></i>Checkout</h2>
    
    <form action="{{ route('tienda.checkout.confirmar') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <!-- Dirección de entrega con Mapa -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Ubicación de Entrega</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-hand-pointer me-2"></i>
                            <strong>Haz clic en el mapa</strong> para marcar tu ubicación exacta de entrega.
                        </div>
                        
                        <!-- Mapa Interactivo -->
                        <div id="mapa"></div>
                        
                        <!-- Info de ubicación seleccionada -->
                        <div class="ubicacion-info" id="ubicacionInfo" style="display: none;">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <div class="col">
                                    <strong>Ubicación seleccionada</strong><br>
                                    <small id="coordenadasTexto">--</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Campos ocultos para coordenadas -->
                        <input type="hidden" name="latitud" id="latitud" value="{{ old('latitud', $user->latitud) }}">
                        <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud', $user->longitud) }}">
                        
                        <div class="mt-3">
                            <label class="form-label">Dirección o referencias adicionales *</label>
                            <textarea name="direccion_entrega" class="form-control" rows="2" required
                                      placeholder="Calle, número, zona, referencias para encontrarte...">{{ old('direccion_entrega', $user->direccion) }}</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Almacén de origen -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-warehouse me-2"></i>Almacén de Origen</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Selecciona el almacén *</label>
                            <select name="almacen_id" id="almacen_id" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                @foreach($almacenes as $almacen)
                                    <option value="{{ $almacen->id }}" 
                                            data-lat="{{ $almacen->latitud }}" 
                                            data-lng="{{ $almacen->longitud }}">
                                        {{ $almacen->nombre_almacen }} - {{ $almacen->ubicacion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="button" class="btn btn-outline-primary" id="btnCotizar">
                            <i class="fas fa-calculator me-2"></i>Calcular Costo de Envío
                        </button>
                        
                        <div id="cotizacionResult" class="mt-3" style="display: none;">
                            <div class="alert alert-success">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Distancia:</strong> <span id="distanciaKm">--</span> km
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Costo de Envío:</strong> <span id="costoEnvio">--</span> Bs
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Método de pago -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Método de Pago</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metodo_pago" 
                                   id="efectivo" value="efectivo" checked>
                            <label class="form-check-label" for="efectivo">
                                <i class="fas fa-money-bill-wave me-2"></i>Efectivo contra entrega
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metodo_pago" 
                                   id="qr" value="qr">
                            <label class="form-check-label" for="qr">
                                <i class="fas fa-qrcode me-2"></i>Pago QR
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metodo_pago" 
                                   id="transferencia" value="transferencia">
                            <label class="form-check-label" for="transferencia">
                                <i class="fas fa-university me-2"></i>Transferencia Bancaria
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Observaciones -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <label class="form-label">Observaciones (opcional)</label>
                        <textarea name="observaciones" class="form-control" rows="2"
                                  placeholder="Instrucciones adicionales para la entrega..."></textarea>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Resumen del pedido -->
                <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Resumen del Pedido</h5>
                    </div>
                    <div class="card-body">
                        @foreach($productos as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $item['producto']->nombre }} x{{ $item['cantidad'] }}</span>
                                <span>{{ number_format($item['subtotal'], 2) }} Bs</span>
                            </div>
                        @endforeach
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>{{ number_format($subtotal, 2) }} Bs</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Costo de Envío</span>
                            <span id="costoEnvioResumen">-- Bs</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total</strong>
                            <strong class="text-primary fs-5" id="totalFinal">{{ number_format($subtotal, 2) }} Bs</strong>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <button type="submit" class="btn btn-primary w-100 btn-lg" id="btnConfirmar">
                            <i class="fas fa-check me-2"></i>Confirmar Pedido
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Coordenadas iniciales (Santa Cruz de la Sierra)
const defaultLat = {{ $user->latitud ?? -17.7833 }};
const defaultLng = {{ $user->longitud ?? -63.1821 }};
const subtotal = {{ $subtotal }};

// Inicializar mapa
const mapa = L.map('mapa').setView([defaultLat, defaultLng], 13);

// Capa de OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(mapa);

// Marcador de ubicación seleccionada
let marcadorUbicacion = null;

// Icono personalizado verde
const iconoVerde = L.icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});

// Si ya hay coordenadas, mostrar marcador
if (defaultLat && defaultLng && defaultLat !== -17.7833) {
    marcadorUbicacion = L.marker([defaultLat, defaultLng], {icon: iconoVerde}).addTo(mapa);
    marcadorUbicacion.bindPopup('📍 Tu ubicación de entrega').openPopup();
    document.getElementById('ubicacionInfo').style.display = 'block';
    document.getElementById('coordenadasTexto').textContent = `${defaultLat.toFixed(6)}, ${defaultLng.toFixed(6)}`;
}

// Evento de clic en el mapa
mapa.on('click', function(e) {
    const lat = e.latlng.lat;
    const lng = e.latlng.lng;
    
    // Actualizar campos ocultos
    document.getElementById('latitud').value = lat;
    document.getElementById('longitud').value = lng;
    
    // Mostrar info
    document.getElementById('ubicacionInfo').style.display = 'block';
    document.getElementById('coordenadasTexto').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    
    // Actualizar o crear marcador
    if (marcadorUbicacion) {
        marcadorUbicacion.setLatLng([lat, lng]);
    } else {
        marcadorUbicacion = L.marker([lat, lng], {icon: iconoVerde}).addTo(mapa);
    }
    
    marcadorUbicacion.bindPopup('📍 Tu ubicación de entrega').openPopup();
});

// Intentar obtener ubicación del usuario
if (navigator.geolocation) {
    document.getElementById('mapa').insertAdjacentHTML('afterend', 
        `<button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="btnMiUbicacion">
            <i class="fas fa-crosshairs me-1"></i>Usar mi ubicación actual
        </button>`
    );
    
    document.getElementById('btnMiUbicacion').addEventListener('click', function() {
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Obteniendo...';
        this.disabled = true;
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                mapa.setView([lat, lng], 16);
                
                document.getElementById('latitud').value = lat;
                document.getElementById('longitud').value = lng;
                document.getElementById('ubicacionInfo').style.display = 'block';
                document.getElementById('coordenadasTexto').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                
                if (marcadorUbicacion) {
                    marcadorUbicacion.setLatLng([lat, lng]);
                } else {
                    marcadorUbicacion = L.marker([lat, lng], {icon: iconoVerde}).addTo(mapa);
                }
                marcadorUbicacion.bindPopup('📍 Tu ubicación actual').openPopup();
                
                document.getElementById('btnMiUbicacion').innerHTML = '<i class="fas fa-crosshairs me-1"></i>Usar mi ubicación actual';
                document.getElementById('btnMiUbicacion').disabled = false;
            },
            function(error) {
                alert('No se pudo obtener tu ubicación. Por favor, marca en el mapa.');
                document.getElementById('btnMiUbicacion').innerHTML = '<i class="fas fa-crosshairs me-1"></i>Usar mi ubicación actual';
                document.getElementById('btnMiUbicacion').disabled = false;
            }
        );
    });
}

// Mostrar almacenes en el mapa
const almacenes = [
    @foreach($almacenes as $almacen)
    {
        id: {{ $almacen->id }},
        nombre: "{{ $almacen->nombre_almacen }}",
        lat: {{ $almacen->latitud ?? 'null' }},
        lng: {{ $almacen->longitud ?? 'null' }}
    },
    @endforeach
];

// Icono de almacén (azul)
const iconoAlmacen = L.icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
});

almacenes.forEach(function(almacen) {
    if (almacen.lat && almacen.lng) {
        L.marker([almacen.lat, almacen.lng], {icon: iconoAlmacen})
            .addTo(mapa)
            .bindPopup(`🏪 ${almacen.nombre}`);
    }
});

// Cotizar envío
document.getElementById('btnCotizar').addEventListener('click', function() {
    const almacenId = document.getElementById('almacen_id').value;
    const latitud = document.getElementById('latitud').value;
    const longitud = document.getElementById('longitud').value;
    
    if (!almacenId) {
        alert('Por favor selecciona un almacén');
        return;
    }
    
    if (!latitud || !longitud) {
        alert('Por favor marca tu ubicación en el mapa');
        return;
    }
    
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Calculando...';
    this.disabled = true;
    
    fetch('{{ route("tienda.checkout.cotizar") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            almacen_id: almacenId,
            latitud: latitud,
            longitud: longitud
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('distanciaKm').textContent = data.distancia;
        document.getElementById('costoEnvio').textContent = data.costo_envio;
        document.getElementById('costoEnvioResumen').textContent = data.costo_envio + ' Bs';
        document.getElementById('totalFinal').textContent = (subtotal + data.costo_envio).toFixed(2) + ' Bs';
        document.getElementById('cotizacionResult').style.display = 'block';
        
        this.innerHTML = '<i class="fas fa-calculator me-2"></i>Calcular Costo de Envío';
        this.disabled = false;
    })
    .catch(error => {
        alert('Error al calcular el envío');
        console.error(error);
        this.innerHTML = '<i class="fas fa-calculator me-2"></i>Calcular Costo de Envío';
        this.disabled = false;
    });
});

// Validar antes de enviar
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const latitud = document.getElementById('latitud').value;
    const longitud = document.getElementById('longitud').value;
    
    if (!latitud || !longitud) {
        e.preventDefault();
        alert('Por favor marca tu ubicación de entrega en el mapa');
        return false;
    }
});
</script>
@endpush
