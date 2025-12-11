@extends('tienda.layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="fas fa-user me-2"></i>Mi Perfil</h2>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('tienda.perfil.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $user->name) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            <small class="text-muted">El email no puede ser cambiado</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" 
                                   value="{{ old('telefono', $user->telefono) }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <textarea name="direccion" class="form-control" rows="2">{{ old('direccion', $user->direccion) }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Ciudad</label>
                            <input type="text" name="ciudad" class="form-control" 
                                   value="{{ old('ciudad', $user->ciudad) }}">
                        </div>
                        
                        <hr>
                        
                        <h5>Cambiar Contraseña (opcional)</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nueva Contraseña</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-user fa-2x text-white"></i>
                    </div>
                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <p class="text-muted small">Miembro desde {{ $user->created_at->format('M Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
