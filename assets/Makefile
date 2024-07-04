# Setup ————————————————————————————————————————————————————————————————————————
# Target OS detection
ifeq ($(OS),Windows_NT)
	# Config & environment variable on Windows
	SHELL			= powershell -NoProfile
	DIR_SEPARATOR	= \\
	SYMFONY_BIN		= symfony.exe
	DOCKER			= docker
	PURGE_CMD		= cmd /c rmdir /Q /S
else
	ifeq ($(shell uname -s),Linux)
		# Config & environment variable on Linux
		SHELL			= bash
		DIR_SEPARATOR	= /
		USER			= $(shell id -u)
		GROUP			= $(shell id -g)
		SYMFONY_BIN		= symfony
		DOCKER_ENV		= USER_ID=$(USER) GROUP_ID=$(GROUP)
		DOCKER			= $(DOCKER_ENV) docker
		PURGE_CMD		= sudo rm -Rf
	else
		$(error OS not supported by this Makefile)
	endif
endif

PROJECT_NAME	= project_name
DOCKER_COMP		= $(DOCKER) compose
PHP				= $(SYMFONY_BIN) php
SYMFONY			= $(SYMFONY_BIN) console
COMPOSER		= $(SYMFONY_BIN) composer
PCOV			= -dpcov.enabled=1 -dpcov.directory=. -dpcov.exclude="~vendor~"
PHPUNIT			= XDEBUG_MODE=off $(PHP) $(PCOV) bin/phpunit -d memory_limit=-1 --stop-on-failure --testdox
PHPUNIT_CI		= XDEBUG_MODE=coverage $(PHP) bin/phpunit -d memory_limit=-1 --stop-on-failure --testdox
PHPQA			= $(DOCKER_COMP) run --rm phpqa
.DEFAULT_GOAL	= help
.PHONY: $(filter-out vendor,$(MAKECMDGOALS))
# Default arguments values ————————————————————————————————————————————————————
env = dev

## —— THE Symfony Makefile 🍺 ——————————————————————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

wait: docker-wait-database docker-wait-redis ## Sleep until service are ready

## —— Project 🛠———————————————————————————————————————————————————————————————
install-up: docker-up composer-install wait assets

install: install-up docker-down ## Install requirements (create database, install php dependencies and build assets)

update: docker-pull composer-update ## Update requirements (Docker images & composer dependencies)

run: install-up symfony-start ## Start docker and start the web server

abort: symfony-stop docker-down ## Stop docker and the Symfony binary server

log: ## Show symfony log
	$(SYMFONY_BIN) server:log

security: ## Launch dependencies security check
	$(SYMFONY_BIN) check:security

requirements: ## Launch symfony requirements check
	$(SYMFONY_BIN) check:requirements

ci: security lint analyze test ## Launch the ci locally

purge: ## Purge the dependencies
	sudo rm -Rf vendor/
	sudo rm -Rf var/cache/*
	sudo rm -Rf var/log/*

## —— Composer 🧙‍♂️ ————————————————————————————————————————————————————————————
vendor: composer.lock
	@if [ ! -d "vendor" ]; then \
		$(COMPOSER) install --no-progress --prefer-dist --optimize-autoloader; \
	else \
		echo "Vendor directory already exists. Skipping composer-install."; \
	fi

composer-install: vendor  ## Install vendors according to the current composer.lock file

composer-update: composer.json ## Update vendors according to the composer.json file
	$(COMPOSER) update

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
cc: ## Clear the cache. DID YOU CLEAR YOUR CACHE????
	$(SYMFONY) doctrine:cache:clear-metadata
	$(SYMFONY) doctrine:cache:clear-query
	$(SYMFONY) doctrine:cache:clear-result
	$(SYMFONY) cache:clear --env=$(env)

## —— Symfony binary 💻 ————————————————————————————————————————————————————————
cert-install: ## Install the local HTTPS certificates
	$(SYMFONY_BIN) server:ca:install

symfony-proxy: ## Start proxy and attach domain to the local server
	$(SYMFONY_BIN) proxy:change:tld wip
	$(SYMFONY_BIN) proxy:start
	$(SYMFONY_BIN) proxy:domain:attach $(PROJECT_NAME)

symfony-start: symfony-proxy ## Serve the application with HTTPS support
	$(SYMFONY_BIN) serve --daemon

symfony-stop: ## Stop the web server
	$(SYMFONY_BIN) server:stop

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
docker-up: compose.yaml ## Start the docker hub (MySQL,redis,phpmyadmin,mailcatcher)
	$(DOCKER_ENV) $(DOCKER_COMP) up -d

docker-pull: compose.yaml ## Pull docker image (MySQL,redis,phpmyadmin,mailcatcher)
	$(DOCKER_ENV) $(DOCKER_COMP) pull

docker-down: compose.yaml ## Stop the docker hub
	$(DOCKER_ENV) $(DOCKER_COMP) down --remove-orphans

docker-database-up: compose.yaml ## Start the database container
	$(DOCKER_ENV) $(DOCKER_COMP) up -d database

docker-redis-up: compose.yaml ## Start the redis container
	$(DOCKER_ENV) $(DOCKER_COMP) up -d redis

docker-typesense-up: compose.yaml ## Start the typesense container
	$(DOCKER_ENV) $(DOCKER_COMP) up -d typesense

docker-phpqa-up: compose.yaml ## Start the phpqa container
	$(DOCKER_ENV) $(DOCKER_COMP) up -d phpqa

docker-wait-database: compose.yaml ## Wait for docker [database] to be ready
	@./docker/bin/wait-for-database.sh

docker-wait-redis: compose.yaml ## Wait for docker [redis] to be ready
	@./docker/bin/wait-for-redis.sh

## —— CI: Tests ✅ —————————————————————————————————————————————————————————————
tests: ## Run the PHPUnit tests
	make cc env=test
	make db-drop-force env=test
	make db-fixtures env=test
	$(PHPUNIT)

test: phpunit.xml.dist ## Run tests with optional suite and filter [eg: make test testsuite=unit or make test filter=testRedirectToLogin]
	make db-drop-force env=test
	make db-fixtures env=test
	@$(eval testsuite ?= 'all')
	@$(eval filter ?= '.')
	@$(PHPUNIT) --testsuite=$(testsuite) --filter=$(filter)

re-test: phpunit.xml.dist ## Re-run tests with optional suite and filter [eg: make re-test testsuite=unit or make re-test filter=testRedirectToLogin]
	@$(eval testsuite ?= 'all')
	@$(eval filter ?= '.')
	@$(PHPUNIT) --testsuite=$(testsuite) --filter=$(filter)

test-ci: composer-install ## Run the PHPUnit tests
	make db-drop-force env=test
	make db-fixtures env=test
	$(PHPUNIT_CI) --log-junit=./var/coverage/tests.xml --coverage-cobertura=./var/coverage/coverage.xml

## —— CI: Coding standards ✨ ——————————————————————————————————————————————————
analyze: php-cs php-mess php-stan php-insights ## Run static analysis tools
fix: php-insights-fix php-cbf ## Fix coding standards
refactor: php-rector ## Refactor the code source with rector
recommend: php-rector-dry ## Refactor the code source with rector dry mode

php-cs: ## Run php_codesniffer with PSR12 standard
	$(PHPQA) phpcs -v --standard=vendor/enabel/coding-standard-bundle/tools/phpcs.xml

php-cbf: ## Run PHP Code Beautifier and Fixer
	$(PHPQA) phpcbf -v --standard=vendor/enabel/coding-standard-bundle/tools/phpcs.xml

php-mess: ## Run PHP Mess Detector
	$(PHPQA) phpmd src/,tests/ ansi vendor/enabel/coding-standard-bundle/tools/.phpmd.xml

php-stan: docker-typesense-up ## Run PHPStan
	$(PHPQA) phpstan analyse -c vendor/enabel/coding-standard-bundle/tools/phpstan.neon --no-interaction

php-insights: ## Run PHP Insights
	$(PHPQA) phpinsights --no-interaction -v --flush-cache --config-path=vendor/enabel/coding-standard-bundle/tools/phpinsights.php

php-insights-fix: ## Run PHP Insights
	$(PHPQA) phpinsights --fix --flush-cache --no-interaction -v --config-path=vendor/enabel/coding-standard-bundle/tools/phpinsights.php

php-cpd: ## Run PHP Copy/Paste Detector
	$(PHPQA) phpcpd --min-lines 70 src/

php-rector-dry: ## Run Rector (dry) - Instant Upgrades and Automated Refactoring
	$(PHPQA) rector process --dry-run --config vendor/enabel/coding-standard-bundle/tools/rector.php src/

php-rector: ## Run Rector - Instant Upgrades and Automated Refactoring
	$(PHPQA) rector process  --config vendor/enabel/coding-standard-bundle/tools/rector.php src/

php-loc: ## Run PHPLOC - Measure the overall size of the project
	$(PHPQA) phploc src/

## —— CI: Linter 🧯 ————————————————————————————————————————————————————————————
lint: lint-yaml lint-xliff lint-container lint-doctrine lint-composer lint-twig ## Run linters

lint-yaml: composer-install ## Lint YAML files
	$(SYMFONY) lint:yaml config --parse-tags

lint-xliff: composer-install ## Lint XLIFF translations
	$(SYMFONY) lint:xliff translations

lint-container: composer-install ## Lint Parameters and Services
	$(SYMFONY) lint:container --no-debug

lint-doctrine: composer-install ## Lint Doctrine mapping
	$(SYMFONY) doctrine:schema:validate --skip-sync -v --no-interaction

lint-composer: ## Lint Composer config
	$(COMPOSER) validate --strict

lint-twig: composer-install ## Run Twig Lint
	$(SYMFONY) lint:twig templates

## —— Database 📑 ——————————————————————————————————————————————————————————————
db-cache: ## Clear database metadata cache
	$(SYMFONY) doctrine:cache:clear-metadata --env=$(env)

db-create: db-cache ## Create the database
	$(SYMFONY) doctrine:database:create --if-not-exists --env=$(env)

db-drop-force: docker-database-up docker-wait-database composer-install ## Force re-create the database
	$(SYMFONY) doctrine:database:drop --if-exists --force --env=$(env)

db-schema: db-create ## Update the database schema
	$(SYMFONY) doctrine:migrations:migrate --env=$(env) -q --allow-no-migration

db-fixtures: db-schema db-load-default ## Load the fixtures
	$(SYMFONY) doctrine:fixtures:load --env=$(env) -n

db-clean: ## Clean the database (drop, create and load schema)
	$(eval CONFIRM := $(shell read -p "Are you sure you want to cleanup the database? [y/N] " CONFIRM && echo $${CONFIRM:-N}))
	@if [ "$(CONFIRM)" = "y" ]; then \
		$(MAKE) db-drop-force; \
		$(MAKE) db-schema; \
	fi

db-reset: ## Drop, create & fill database with fixtures.
	$(eval CONFIRM := $(shell read -p "Are you sure you want to reset the database? [y/N] " CONFIRM && echo $${CONFIRM:-N}))
	@if [ "$(CONFIRM)" = "y" ]; then \
		$(MAKE) db-drop-force; \
		$(MAKE) db-schema; \
		$(MAKE) db-fixtures; \
	fi

## —— Assets 💄 ——————————————————————————————————————————————————————————
assets: ## Run sass:build to watch assets
	$(SYMFONY) sass:build --watch

assets-prod: build-sass ## Run AssetMapper to compile production assets
	$(SYMFONY) asset-map:compile

build-sass: ## Run sass:build to compile assets
	$(SYMFONY) sass:build

asset-audit: ## Run vulnerability audit against the importmap
	$(SYMFONY) importmap:audit

## —— Translations 🏳️ ———————————————————————————————————————————————————
translation-pull: composer-install ## Get translation from remote storage [Loco]
	$(SYMFONY) translation:download
	$(SYMFONY) cache:clear --env=$(env)

translation-push: composer-install ## Push translation to remote storage [Loco]
	$(SYMFONY) translation:push loco

## —— Indexing 📇 ———————————————————————————————————————————————————————————
index-create: ## Create the search engine
	$(SYMFONY) typesense:create -n --env=$(env)
index-import: index-create ## Import data into the search engine
	$(SYMFONY) typesense:import -n --env=$(env)
indexing: ## Reindex the search engine
	$(SYMFONY) typesense:import -n --env=$(env)

## —— Tools 🧰 ———————————————————————————————————————————————————————————
open: docker-up symfony-start ## Open the project webinterface
	$(SYMFONY_BIN) open:local

open-coverage: symfony-start ## Open coverage report
	$(SYMFONY_BIN) open:local --path=/coverage/

open-phpmyadmin: docker-up symfony-start ## Open PHPMyAdmin
	$(SYMFONY_BIN) open:local:service phpmyadmin

open-mailer: docker-up symfony-start ## Open MailCatcher
	$(SYMFONY_BIN) open:local:webmail

open-redis: docker-up symfony-start ## Open PhpRedisAdmin
	$(SYMFONY_BIN) open:local:service phpredisadmin
