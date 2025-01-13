#!/bin/bash

set -e

# migration run
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate --no-interaction || true

exec "$@"