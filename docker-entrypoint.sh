#!/bin/bash

# Generar clave si no existe
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Limpiar caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Crear link de storage
php artisan storage:link 2>/dev/null || true

# Ejecutar migraciones
php artisan migrate --force

# Iniciar Apache
apache2-foreground
