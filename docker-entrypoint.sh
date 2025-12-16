#!/bin/bash
set -e

# Crear directorios necesarios
mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache

# Dar permisos
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Limpiar caches (sin depender de la BD)
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Crear link de storage
php artisan storage:link 2>/dev/null || true

# Ejecutar migraciones
echo "==> Running migrations..."
php artisan migrate --force || echo "Migration failed, continuing anyway..."

echo "==> Your service is live"

# Iniciar Apache
exec apache2-foreground
