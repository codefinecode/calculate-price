#!/bin/sh
set -e

# Wait for database to be ready
until nc -z database 5432; do
  echo "Waiting for database to be available..."
  sleep 1
done

# Install dependencies if not already installed
if [ ! -d "vendor" ]; then
    composer install
fi

if [ ! -f ".env" ]; then
    cp .env.dev .env
fi

if [ "$APP_ENV" = "dev" ]; then
#    php bin/console doctrine:database:drop --force --if-exists
#    php bin/console doctrine:database:create --if-not-exists --env=dev || true
#    php bin/console doctrine:migrations:migrate --no-interaction --env=dev
#    php bin/console doctrine:fixtures:load --no-interaction --env=dev
elif [ "$APP_ENV" = "test" ]; then
#    php bin/console doctrine:database:drop --env=test --force --if-exists || true
#    php bin/console doctrine:database:create --if-not-exists --env=test || true
#    php bin/console doctrine:migrations:migrate --no-interaction --env=test
#    php bin/console doctrine:fixtures:load --group=test --no-interaction --env=test
fi

exec "$@"