<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - Proven</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <style>
        :root {
            --verde-bosque: #2E8B57;
            --verde-hoja: #3CB371;
            --verde-claro: #90EE90;
            --marron-tierra: #8B4513;
            --beige-arena: #F5DEB3;
            --azul-cielo: #87CEEB;
            --blanco-nube: #F8F9FA;
            --gris-arbol: #6C757D;
        }

        .login-page {
            background: linear-gradient(135deg, var(--verde-bosque) 0%, var(--verde-hoja) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .login-logo {
            font-size: 2.5rem;
            font-weight: 300;
            color: white !important;
            margin-bottom: 1rem;
        }

        .login-logo i {
            color: var(--verde-claro);
        }

        .login-card-body {
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
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

        .register-link {
            color: var(--verde-bosque);
            text-decoration: none;
        }

        .register-link:hover {
            color: var(--verde-hoja);
            text-decoration: underline;
        }

        .alert {
            border-radius: 5px;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <!-- Logo -->
    <div class="login-logo">
        <a href="{{ route('login') }}">
            <i class="fas fa-leaf"></i> <b>Proven</b>
        </a>
    </div>

    <!-- Card -->
    <div class="card login-card-body">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Inicia sesión para continuar</p>

            <!-- Alertas -->
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

            <!-- Formulario -->
            <form action="{{ route('login') }}" method="post">
                @csrf

                <!-- Email -->
                <div class="input-group mb-3">
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        placeholder="Correo electrónico"
                        value="{{ old('email') }}"
                        required
                        autofocus
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

                <!-- Password -->
                <div class="input-group mb-3">
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        placeholder="Contraseña"
                        required
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

                <!-- Remember Me -->
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">
                                Recordarme
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt me-2"></i>Entrar
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <!-- Registro -->
            <p class="mb-1 mt-3 text-center">
                <a href="{{ route('register') }}" class="register-link">
                    <i class="fas fa-user-plus me-2"></i>¿No tienes cuenta? Regístrate
                </a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>

