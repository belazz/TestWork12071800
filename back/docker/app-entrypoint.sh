#!/usr/bin/env sh
set -e
php artisan migrate
exec "$@";
