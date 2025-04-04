.PHONY: help build composer-install up start stop down test
.DEFAULT_GOAL := help

CMD_PHP := docker exec card_payment_service_php_fpm

## build containers
build:
	docker-compose build

## composer install
composer-install:
	${CMD_PHP} /bin/bash -c 'composer install'

## Docker-compose development
up: build
	docker-compose up -d

## start containers
start:
	docker-compose stop

## stop containers
stop:
	docker-compose stop

## stop and remove containers
down:
	docker-compose down

## run tests in container
test:
	${CMD_PHP} /bin/bash -c 'vendor/bin/phpunit'

## help
help:
	@printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	@printf " make [target]\n\n"
	@printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	@awk '/^[a-zA-Z\-_0-9\.@]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf " ${COLOR_INFO}%-16s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
	} \
{ lastLine = $$0 }' $(MAKEFILE_LIST)
