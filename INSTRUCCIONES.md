# Guía de Instalación y Ejecución - Proyecto DelCampo

Esta es una aplicación Laravel 9 que requiere PHP 8.0.2 o superior, MySQL y Node.js.

## ✅ Pasos Completados Automáticamente

Los siguientes pasos ya se han completado:
- ✅ Archivo `.env` creado
- ✅ Dependencias de PHP instaladas (`composer install`)
- ✅ Clave de aplicación generada
- ✅ Dependencias de Node.js instaladas (`npm install`)

## 📋 Pasos Restantes para Ejecutar el Proyecto

### 1. Configurar la Base de Datos

Abre el archivo `.env` y configura las credenciales de tu base de datos MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=delcampo
DB_USERNAME=root
DB_PASSWORD=tu_contraseña_aqui
```

### 2. Crear la Base de Datos Automáticamente

**¡Buenas noticias!** El proyecto incluye un comando que crea la base de datos automáticamente:

```bash
php artisan db:create
```

Este comando:
- ✅ Verifica si la base de datos ya existe
- ✅ Crea la base de datos si no existe
- ✅ Usa las credenciales de tu archivo `.env`

**Alternativa manual:** Si prefieres crearla manualmente en MySQL:

```sql
CREATE DATABASE delcampo;
```

### 3. Ejecutar las Migraciones

Ejecuta las migraciones para crear las tablas en la base de datos:

```bash
php artisan migrate
```

### 4. Ejecutar los Seeders (Datos de Prueba)

Para poblar la base de datos con datos iniciales:

```bash
php artisan db:seed
```

O si quieres ejecutar seeders específicos:

```bash
php artisan db:seed --class=RolesPermisosSeeder
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=ProductosSeeder
php artisan db:seed --class=AlmacenSeeder
```

### 5. Crear el Enlace Simbólico de Storage

Para que las imágenes y archivos se puedan acceder públicamente:

```bash
php artisan storage:link
```

### 6. Compilar los Assets Frontend

En una terminal, ejecuta el servidor de desarrollo de Vite:

```bash
npm run dev
```

**Nota:** Deja esta terminal abierta mientras trabajas en el proyecto.

### 7. Iniciar el Servidor de Laravel

En otra terminal, ejecuta el servidor de desarrollo de Laravel:

```bash
php artisan serve
```

El servidor estará disponible en: `http://localhost:8000`

## 🚀 Comandos Rápidos

### Ejecutar todo en una sola vez (después de configurar la BD):

```bash
# Terminal 1 - Frontend (Vite)
npm run dev

# Terminal 2 - Backend (Laravel)
php artisan db:create    # Crea la base de datos automáticamente
php artisan migrate       # Crea las tablas
php artisan db:seed      # Pobla la base de datos
php artisan storage:link # Crea el enlace simbólico
php artisan serve        # Inicia el servidor
```

## 📝 Notas Importantes

1. **Base de Datos:** Asegúrate de que MySQL esté corriendo y que hayas creado la base de datos `delcampo`.

2. **Puertos:**
   - Laravel: `http://localhost:8000`
   - Vite: `http://localhost:5173` (por defecto)

3. **Permisos:** Si tienes problemas con permisos en Windows, asegúrate de que las carpetas `storage` y `bootstrap/cache` tengan permisos de escritura.

4. **Variables de Entorno:** Si necesitas cambiar alguna configuración, edita el archivo `.env`.

## 🔧 Solución de Problemas

### Error de conexión a la base de datos
- Verifica que MySQL esté corriendo
- Revisa las credenciales en el archivo `.env`
- Asegúrate de que la base de datos `delcampo` exista

### Error al ejecutar migraciones
- Verifica que la base de datos esté creada
- Revisa que las credenciales en `.env` sean correctas
- Intenta ejecutar: `php artisan migrate:fresh` (esto eliminará todas las tablas y las recreará)

### Los assets no se cargan
- Asegúrate de que `npm run dev` esté corriendo
- Verifica que Vite esté escuchando en el puerto correcto

## 📚 Estructura del Proyecto

- **Backend:** Laravel 9 (PHP)
- **Frontend:** Vite + JavaScript
- **Base de Datos:** MySQL
- **Paquetes principales:**
  - Spatie Laravel Permission (roles y permisos)
  - Laravel Sanctum (autenticación API)
  - DomPDF (generación de PDFs)
  - Maatwebsite Excel (importación/exportación de Excel)

## 🎯 Próximos Pasos

Una vez que el proyecto esté corriendo:
1. Accede a `http://localhost:8000`
2. Revisa las rutas en `routes/web.php` para ver las páginas disponibles
3. Consulta los controladores en `app/Http/Controllers` para entender la funcionalidad

¡Listo! Tu proyecto debería estar funcionando correctamente.

