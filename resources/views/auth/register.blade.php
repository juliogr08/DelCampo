<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro - Del Campo</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        :root {
            --verde-bosque: #3D6B1E;
            --verde-hoja: #4A7C23;
            --verde-claro: #5A8A2E;
            --marron-tierra: #8B4513;
            --beige-arena: #F5DEB3;
            --azul-cielo: #87CEEB;
            --blanco-nube: #F8F9FA;
            --gris-arbol: #6C757D;
        }

        .login-page {
            background: linear-gradient(135deg, #3D6B1E 0%, #4A7C23 50%, #5A8A2E 100%);
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 20px;
            overflow-y: auto;
        }

        .login-box {
            width: 100%;
            max-width: 480px;
            margin: 20px auto;
            transition: max-width 0.3s ease;
        }

        .login-box.expanded {
            max-width: 550px;
        }

        .login-logo {
            font-size: 2.5rem;
            font-weight: 300;
            margin-bottom: 1rem;
        }

        .login-logo a {
            color: white !important;
        }

        .login-logo i {
            color: white !important;
        }

        .login-card-body {
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-height: 85vh;
            overflow-y: auto;
        }

        .btn-primary {
            background-color: var(--verde-bosque);
            border-color: var(--verde-bosque);
        }

        .btn-primary:hover {
            background-color: var(--verde-hoja);
            border-color: var(--verde-hoja);
        }

        .input-group-text {
            background-color: var(--verde-bosque);
            color: white;
            border-color: var(--verde-bosque);
        }

        .form-control:focus {
            border-color: var(--verde-hoja);
            box-shadow: 0 0 0 0.2rem rgba(46, 139, 87, 0.25);
        }

        .login-link {
            color: var(--verde-bosque);
            text-decoration: none;
        }

        .login-link:hover {
            color: var(--verde-hoja);
            text-decoration: underline;
        }

        .alert {
            border-radius: 5px;
        }

        .password-requirements {
            font-size: 0.875rem;
            color: var(--gris-arbol);
            margin-top: 0.5rem;
        }

        .password-requirements ul {
            margin-bottom: 0;
            padding-left: 1.5rem;
        }

        /* Toggle de tipo de cuenta */
        .account-type-toggle {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .account-type-card {
            flex: 1;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .account-type-card:hover {
            border-color: var(--verde-hoja);
            transform: translateY(-2px);
        }

        .account-type-card.active {
            border-color: var(--verde-bosque);
            background: linear-gradient(135deg, rgba(46, 139, 87, 0.1) 0%, rgba(60, 179, 113, 0.1) 100%);
            box-shadow: 0 4px 15px rgba(46, 139, 87, 0.2);
        }

        .account-type-card i {
            font-size: 1.5rem;
            margin-bottom: 5px;
            color: var(--gris-arbol);
        }

        .account-type-card.active i {
            color: var(--verde-bosque);
        }

        .account-type-card h5 {
            margin: 0;
            font-weight: 600;
            color: var(--gris-arbol);
        }

        .account-type-card.active h5 {
            color: var(--verde-bosque);
        }

        .account-type-card small {
            color: #999;
        }

        /* Sección de tienda */
        .tienda-fields {
            display: none;
            animation: slideDown 0.3s ease;
        }

        .tienda-fields.show {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: var(--gris-arbol);
        }

        .section-divider::before,
        .section-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }

        .section-divider span {
            padding: 0 15px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Mapa Leaflet */
        #map {
            height: 150px;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 2px solid #e0e0e0;
        }

        .map-instructions {
            font-size: 0.8rem;
            color: var(--gris-arbol);
            margin-bottom: 10px;
        }

        /* Logo preview */
        .logo-preview-container {
            text-align: center;
            margin-bottom: 15px;
        }

        .logo-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--verde-bosque);
            display: none;
        }

        .logo-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 3px dashed #ccc;
            cursor: pointer;
        }

        .logo-placeholder i {
            font-size: 1.5rem;
            color: #999;
        }

        .form-group {
            margin-bottom: 0.75rem;
        }

        .form-control-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .char-counter {
            font-size: 0.75rem;
            color: #999;
            text-align: right;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box" id="loginBox">
    <div class="login-logo">
        <a href="{{ route('register') }}">
            <i class="fas fa-seedling"></i> <b>Del Campo</b>
        </a>
    </div>

    <div class="card login-card-body">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Crea tu cuenta</p>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Toggle tipo de cuenta -->
            <div class="account-type-toggle">
                <div class="account-type-card active" id="clienteCard" onclick="selectAccountType('cliente')">
                    <i class="fas fa-shopping-cart"></i>
                    <h5>Cliente</h5>
                    <small>Quiero comprar</small>
                </div>
                <div class="account-type-card" id="tiendaCard" onclick="selectAccountType('tienda')">
                    <i class="fas fa-store"></i>
                    <h5>Tienda</h5>
                    <small>Quiero vender</small>
                </div>
            </div>

            <form action="{{ route('register') }}" method="post" enctype="multipart/form-data" id="registerForm">
                @csrf
                <input type="hidden" name="tipo_cuenta" id="tipoCuenta" value="{{ old('tipo_cuenta', 'cliente') }}">

                <!-- Campos básicos -->
                <div class="input-group mb-3">
                    <input 
                        type="text" 
                        name="name" 
                        class="form-control @error('name') is-invalid @enderror" 
                        placeholder="Nombre completo"
                        value="{{ old('name') }}"
                        required
                        autofocus
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        placeholder="Correo electrónico"
                        value="{{ old('email') }}"
                        required
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        placeholder="Contraseña"
                        required
                        id="password"
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="password-requirements">
                    <small>La contraseña debe tener al menos:</small>
                    <ul>
                        <li>8 caracteres</li>
                        <li>Una letra</li>
                        <li>Un número</li>
                    </ul>
                </div>

                <div class="input-group mb-3">
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        class="form-control" 
                        placeholder="Confirmar contraseña"
                        required
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <!-- Campos de tienda (ocultos por defecto) -->
                <div class="tienda-fields" id="tiendaFields">
                    <div class="section-divider">
                        <span><i class="fas fa-store me-2"></i>Datos de la Tienda</span>
                    </div>

                    <!-- Logo de tienda -->
                    <div class="logo-preview-container">
                        <div class="logo-placeholder" onclick="document.getElementById('logo').click()">
                            <i class="fas fa-camera"></i>
                        </div>
                        <img id="logoPreview" class="logo-preview" src="" alt="Logo preview">
                        <input type="file" name="logo" id="logo" accept="image/*" style="display: none" onchange="previewLogo(this)">
                        <small class="text-muted d-block mt-2">Click para subir logo (opcional)</small>
                    </div>

                    <div class="input-group mb-3">
                        <input 
                            type="text" 
                            name="nombre_tienda" 
                            class="form-control @error('nombre_tienda') is-invalid @enderror" 
                            placeholder="Nombre de la tienda"
                            value="{{ old('nombre_tienda') }}"
                        >
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-store"></span>
                            </div>
                        </div>
                        @error('nombre_tienda')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <textarea 
                            name="descripcion_tienda" 
                            class="form-control @error('descripcion_tienda') is-invalid @enderror" 
                            placeholder="Describe tu tienda (máx. 280 caracteres)"
                            rows="3"
                            maxlength="280"
                            id="descripcionTienda"
                            onkeyup="updateCharCounter()"
                        >{{ old('descripcion_tienda') }}</textarea>
                        <div class="char-counter"><span id="charCount">0</span>/280</div>
                        @error('descripcion_tienda')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input 
                            type="tel" 
                            name="telefono_tienda" 
                            class="form-control @error('telefono_tienda') is-invalid @enderror" 
                            placeholder="Teléfono de contacto"
                            value="{{ old('telefono_tienda') }}"
                        >
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-phone"></span>
                            </div>
                        </div>
                        @error('telefono_tienda')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <input 
                            type="text" 
                            name="direccion_tienda" 
                            class="form-control @error('direccion_tienda') is-invalid @enderror" 
                            placeholder="Dirección de la tienda"
                            value="{{ old('direccion_tienda') }}"
                            id="direccionTienda"
                        >
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-map-marker-alt"></span>
                            </div>
                        </div>
                        @error('direccion_tienda')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Mapa -->
                    <p class="map-instructions">
                        <i class="fas fa-info-circle"></i> Haz clic en el mapa para marcar la ubicación de tu tienda
                    </p>
                    <div id="map"></div>
                    <input type="hidden" name="latitud" id="latitud" value="{{ old('latitud') }}">
                    <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud') }}">

                    <div class="alert alert-info mb-3" style="font-size: 0.85rem;">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Nota:</strong> Tu tienda quedará pendiente de aprobación. Se activará cuando hagas tu primera solicitud de productos.
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-user-plus me-2"></i><span id="btnText">Registrarse como Cliente</span>
                        </button>
                    </div>
                </div>
            </form>

            <p class="mb-1 mt-3 text-center">
                <a href="{{ route('login') }}" class="login-link">
                    <i class="fas fa-sign-in-alt me-2"></i>¿Ya tienes cuenta? Inicia sesión
                </a>
            </p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map = null;
    let marker = null;

    function selectAccountType(type) {
        document.getElementById('tipoCuenta').value = type;
        
        const clienteCard = document.getElementById('clienteCard');
        const tiendaCard = document.getElementById('tiendaCard');
        const tiendaFields = document.getElementById('tiendaFields');
        const loginBox = document.getElementById('loginBox');
        const btnText = document.getElementById('btnText');

        if (type === 'cliente') {
            clienteCard.classList.add('active');
            tiendaCard.classList.remove('active');
            tiendaFields.classList.remove('show');
            loginBox.classList.remove('expanded');
            btnText.textContent = 'Registrarse como Cliente';
            
            // Quitar required de campos de tienda
            document.querySelectorAll('#tiendaFields input[type="text"], #tiendaFields input[type="tel"]').forEach(el => {
                el.removeAttribute('required');
            });
        } else {
            tiendaCard.classList.add('active');
            clienteCard.classList.remove('active');
            tiendaFields.classList.add('show');
            loginBox.classList.add('expanded');
            btnText.textContent = 'Registrarse como Tienda';
            
            // Agregar required a campos de tienda
            document.querySelector('input[name="nombre_tienda"]').setAttribute('required', 'required');
            document.querySelector('input[name="telefono_tienda"]').setAttribute('required', 'required');
            document.querySelector('input[name="direccion_tienda"]').setAttribute('required', 'required');
            
            // Inicializar mapa si no existe
            setTimeout(() => {
                if (!map) {
                    initMap();
                } else {
                    map.invalidateSize();
                }
            }, 300);
        }
    }

    function initMap() {
        // Coordenadas por defecto (Bolivia - Santa Cruz)
        const defaultLat = -17.7833;
        const defaultLng = -63.1821;
        
        map = L.map('map').setView([defaultLat, defaultLng], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Si hay coordenadas guardadas, usarlas
        const savedLat = document.getElementById('latitud').value;
        const savedLng = document.getElementById('longitud').value;
        
        if (savedLat && savedLng) {
            const lat = parseFloat(savedLat);
            const lng = parseFloat(savedLng);
            map.setView([lat, lng], 15);
            addMarker(lat, lng);
        }

        // Click en el mapa
        map.on('click', function(e) {
            addMarker(e.latlng.lat, e.latlng.lng);
        });

        // Intentar obtener ubicación actual
        if (navigator.geolocation && !savedLat) {
            navigator.geolocation.getCurrentPosition(function(position) {
                map.setView([position.coords.latitude, position.coords.longitude], 15);
            });
        }
    }

    function addMarker(lat, lng) {
        if (marker) {
            map.removeLayer(marker);
        }
        
        marker = L.marker([lat, lng], {
            icon: L.divIcon({
                html: '<i class="fas fa-store" style="color: #2E8B57; font-size: 24px;"></i>',
                iconSize: [24, 24],
                className: 'custom-marker'
            })
        }).addTo(map);
        
        document.getElementById('latitud').value = lat.toFixed(8);
        document.getElementById('longitud').value = lng.toFixed(8);
    }

    function previewLogo(input) {
        const preview = document.getElementById('logoPreview');
        const placeholder = document.querySelector('.logo-placeholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updateCharCounter() {
        const textarea = document.getElementById('descripcionTienda');
        const counter = document.getElementById('charCount');
        counter.textContent = textarea.value.length;
    }

    // Inicializar al cargar página
    document.addEventListener('DOMContentLoaded', function() {
        const tipoCuenta = document.getElementById('tipoCuenta').value;
        if (tipoCuenta === 'tienda') {
            selectAccountType('tienda');
        }
        updateCharCounter();
    });
</script>
</body>
</html>
