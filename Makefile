USER_ID=$(shell id -u)
export APP_ENV=dev

DC = @USER_ID=$(USER_ID) docker compose -f docker-compose.yml
DC_RUN = ${DC} run --rm sio_test
DC_EXEC = ${DC} exec sio_test

PHONY: help
.DEFAULT_GOAL := help

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

init: down build install up drop-test-db drop-db db success-message console ## Initialize environment

build: ## Build services.
	${DC} build $(c)

up: ## Create and start services.
	${DC} up -d $(c)

stop: ## Stop services.
	${DC} stop $(c)

start: ## Start services.
	${DC} start $(c)

down:  ## Stop and remove containers and volumes.
	${DC} down -v $(c)

clean: ##
	${DC} down -v $(c)
	${DC} down --remove-orphans

network: ## Create network.
	docker network create app-network

restart: stop start ## Restart services.

console: ## Login in console.
	${DC_EXEC} /bin/bash

install: ## Install dependencies without running the whole application.
	${DC_RUN} composer install

test:  drop-test-db ## Run tests
	${DC_EXEC} sh -c "APP_ENV=test php -d xdebug.mode=coverage vendor/bin/phpunit --coverage-text > build/coverage/coverage.txt"
#	${DC_EXEC} php -APP_ENV=test -d xdebug.mode=coverage vendor/bin/phpunit --coverage-text > build/coverage/coverage.txt

drop-db:  ## Drop and recreate database with tables and fixtures data
	APP_ENV=dev
	${DC_EXEC} php bin/console doctrine:database:drop --force --if-exists --env=dev || true


drop-test-db: ## Drop and recreate test database with tables and fixtures data
	APP_ENV=test
	${DC_EXEC} sh -c "APP_ENV=test php bin/console doctrine:database:drop --env=test --force --if-exists  || true"

db:
	APP_ENV=dev
	${DC_EXEC} php bin/console doctrine:database:create --if-not-exists --env=dev
	${DC_EXEC} php bin/console doctrine:migrations:migrate --no-interaction --env=dev
	${DC_EXEC} php bin/console doctrine:fixtures:load --no-interaction --env=dev
	APP_ENV=test
	${DC_EXEC} sh -c "APP_ENV=test php bin/console doctrine:database:create --if-not-exists --env=test || true"
	${DC_EXEC} sh -c "APP_ENV=test php bin/console doctrine:migrations:migrate --no-interaction --env=test"
	${DC_EXEC} sh -c "APP_ENV=test php bin/console doctrine:fixtures:load --group=test --no-interaction --env=test"

success-message:
	@echo $(APP_ENV)
	@echo "You can now access the application at http://localhost:8337"
	@echo "Adminer (PostgreSQL UI) is available at http://localhost:8080"
	@echo "Redis can be accessed at localhost:6379 (optional: uncomment in docker-compose.yml)"
	@echo "Good luck! 🚀"