# Setup ————————————————————————————————————————————————————————————————————————
SHELL         = bash
PROJECT       = app
EXEC_PHP      = php
GIT           = git
GIT_AUTHOR    = dcognata
SYMFONY       = $(EXEC_PHP) bin/console
PHPUNIT       = $(EXEC_PHP) bin/phpunit
SYMFONY_BIN   = symfony
COMPOSER      = composer
DOCKER        = docker
DOCKER_COMP   = docker-compose
.DEFAULT_GOAL = help
#.PHONY       = # Not needed for now

## —— 🐝 The dcognata from Strangebuzz Symfony Makefile 🐝 ———————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

wait: ## Sleep 5 seconds
	sleep 5

sf:
	$(SYMFONY)

## —— Composer 🧙‍♂️ ————————————————————————————————————————————————————————————
install: composer.lock ## Install vendors according to the current composer.lock file
	$(COMPOSER) install --no-progress --prefer-dist --optimize-autoloader

install-dev: composer.lock ## Install vendors according to the current composer.lock file
	$(COMPOSER) install --no-progress --prefer-dist

update: composer.json ## Update vendors according to the composer.json file
	$(COMPOSER) update

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
cc: ## Clear the cache. DID YOU CLEAR YOUR CACHE????
	$(SYMFONY) c:c

ccc: cc ## Clear the cache. DID YOU CLEAR YOUR CACHE????
	$(SYMFONY) cache:warmup
	$(SYMFONY) doctrine:cache:clear-metadata
	$(SYMFONY) doctrine:cache:clear-result

fix-perms: ## Fix permissions of all var files
	chmod -R 777 var/*

assets: purge ## Install the assets with symlinks in the public folder
	$(SYMFONY) assets:install public/ --symlink --relative

purge: ## Purge cache and logs
	rm -rf var/cache/* var/logs/*

## —— Local Server 🐳 ————————————————————————————————————————————————————————————————

start:
	$(DOCKER_COMP) up -d
	$(SYMFONY_BIN) server:start -d
	$(SYMFONY_BIN) proxy:start

stop:
	$(SYMFONY_BIN) server:stop
	$(DOCKER_COMP) stop

#down: ./docker-compose.yml ## Stop the docker hub
#	$(DOCKER_COMP) down --remove-orphans

# dpsn: ## List Docker containers for the project
# 	$(DOCKER_COMP) images

## —— Project 🐝 ———————————————————————————————————————————————————————————————
commands: ## Display all commands in the project namespace
	$(SYMFONY) list $(PROJECT)

## —— Tests ✅ —————————————————————————————————————————————————————————————————
test: ## phpunit.xml ## Launch main functional and unit tests
	$(PHPUNIT) --testsuite=main --stop-on-failure

## —— Deploy & Prod 🚀 —————————————————————————————————————————————————————————
deploy: install js-install
	git pull
	$(SYMFONY) c:c
	$(SYMFONY) doctrine:cache:clear-result
	$(SYMFONY) doctrine:migration:migrate --no-interaction
	yarn encore production
	$(SYMFONY) messenger:stop


## —— Yarn 🐱 / JavaScript —————————————————————————————————————————————————————
js-install: ##
	npm install
	yarn install

y-dev: ## Rebuild assets for the dev env
	yarn encore dev

y-watch: ## Watch files and build assets when needed for the dev env
	yarn encore dev --watch

y-build: ## Build assets for production
	yarn encore production
