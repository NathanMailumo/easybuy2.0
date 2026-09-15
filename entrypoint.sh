#!/bin/sh

# Run database migrations automatically on startup
php artisan migrate --force

# Start the Laravel application
exec php artisan serve --host=0.0.0.0 --port=8000