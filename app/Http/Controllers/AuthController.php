<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tienda;
use App\Models\Almacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('success', '¡Bienvenido al panel de administración!');
            }

            if ($user->isTienda()) {
                return redirect()->route('tienda.panel.dashboard')
                    ->with('success', '¡Bienvenido al panel de tu tienda!');
            }

            return redirect()->route('tienda.home')
                ->with('success', '¡Bienvenido a nuestra tienda!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->withInput($request->only('email'));
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $tipoCuenta = $request->input('tipo_cuenta', 'cliente');

        $rules = [
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
            'tipo_cuenta' => 'required|in:cliente,tienda',
        ];

        $messages = [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];

        if ($tipoCuenta === 'tienda') {
            $rules['nombre_tienda'] = 'required|string|max:100|unique:tiendas,nombre';
            $rules['telefono_tienda'] = 'required|string|max:20';
            $rules['direccion_tienda'] = 'required|string|max:500';
            $rules['descripcion_tienda'] = 'nullable|string|max:280';
            // Logo validation done manually to avoid MIME detection issues
            $rules['latitud'] = 'required|numeric|between:-90,90';
            $rules['longitud'] = 'required|numeric|between:-180,180';

            $messages['nombre_tienda.required'] = 'El nombre de la tienda es obligatorio.';
            $messages['nombre_tienda.unique'] = 'Ya existe una tienda con este nombre.';
            $messages['telefono_tienda.required'] = 'El teléfono de contacto es obligatorio.';
            $messages['direccion_tienda.required'] = 'La dirección de la tienda es obligatoria.';
            $messages['latitud.required'] = 'Debes seleccionar la ubicación en el mapa.';
            $messages['longitud.required'] = 'Debes seleccionar la ubicación en el mapa.';
        }

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => $tipoCuenta,
                'telefono' => $tipoCuenta === 'tienda' ? $request->telefono_tienda : null,
                'direccion' => $tipoCuenta === 'tienda' ? $request->direccion_tienda : null,
                'latitud' => $tipoCuenta === 'tienda' ? $request->latitud : null,
                'longitud' => $tipoCuenta === 'tienda' ? $request->longitud : null,
            ]);

            if ($tipoCuenta === 'tienda') {
                $logoPath = null;
                
                // Use native PHP to avoid Laravel's MIME detection
                if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['logo']['tmp_name'];
                    $originalName = $_FILES['logo']['name'];
                    $size = $_FILES['logo']['size'];
                    
                    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    
                    if (in_array($extension, $allowedExtensions) && $size <= 2048000) {
                        $filename = 'logo_' . time() . '_' . uniqid() . '.' . $extension;
                        $destination = storage_path('app/public/tiendas/logos/' . $filename);
                        
                        if (move_uploaded_file($tmpName, $destination)) {
                            $logoPath = 'tiendas/logos/' . $filename;
                        }
                    }
                }

                $tienda = Tienda::create([
                    'user_id' => $user->id,
                    'nombre' => $request->nombre_tienda,
                    'logo_path' => $logoPath,
                    'descripcion' => $request->descripcion_tienda,
                    'telefono' => $request->telefono_tienda,
                    'direccion' => $request->direccion_tienda,
                    'latitud' => $request->latitud,
                    'longitud' => $request->longitud,
                    'estado' => 'pendiente',
                ]);

                Almacen::create([
                    'tienda_id' => $tienda->id,
                    'nombre_almacen' => 'Sede Principal - ' . $tienda->nombre,
                    'ubicacion' => $request->direccion_tienda,
                    'latitud' => $request->latitud,
                    'longitud' => $request->longitud,
                    'capacidad' => 100,
                    'unidad_capacidad' => 'm2',
                    'tipo_almacenamiento' => 'ambiente',
                    'responsable' => $request->name,
                    'telefono_contacto' => $request->telefono_tienda,
                    'activo' => true,
                    'es_principal' => false,
                    'es_sede_principal' => true,
                ]);
            }

            DB::commit();

            Auth::login($user);

            if ($tipoCuenta === 'tienda') {
                return redirect()->route('tienda.panel.dashboard')
                    ->with('success', '¡Tienda registrada exitosamente! Tu tienda está pendiente de aprobación. Se activará cuando hagas tu primera solicitud de productos.');
            }

            return redirect()->route('tienda.home')
                ->with('success', '¡Cuenta creada exitosamente! Bienvenido a nuestra tienda.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error en registro: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Sesión cerrada exitosamente.');
    }
}

