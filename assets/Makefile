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

DOCKER_COMP		= $(DOCKER) compose
PHP				= $(SYMFONY_BIN) php
SYMFONY			= $(SYMFONY_BIN) console
COMPOSER		= $(SYMFONY_BIN) composer
PCOV			= -dpcov.enabled=1 -dpcov.directory=. -dpcov.exclude="~vendor~"
PHPUNIT			= XDEBUG_MODE=off $(PHP) $(PCOV) bin/phpunit -d memory_limit=-1 --stop-on-failure --testdox
PHPUNIT_CI		= XDEBUG_MODE=coverage $(PHP) bin/phpunit -d memory_limit=-1 --stop-on-failure --testdox
PHPQA			= $(DOCKER_COMP) run --rm phpqa
YARN			= $(DOCKER_COMP) run --rm node yarn
.DEFAULT_GOAL	= help
.PHONY: $(filter-out vendor node_modules,$(MAKECMDGOALS))
# Default arguments values ————————————————————————————————————————————————————
env = dev

## —— THE Symfony Makefile 🍺 ——————————————————————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
