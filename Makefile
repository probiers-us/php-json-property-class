.PHONY: clean code-style coverage help test static-analysis infection-testing install-dependencies update-dependencies
.DEFAULT_GOAL := test

PHPUNIT = ./vendor/bin/phpunit -c ./phpunit.xml
PHPSTAN = ./vendor/bin/phpstan --no-progress
COVCHECK = ./vendor/bin/coverage-check
PHPCS = ./vendor/bin/phpcs --extensions=php -v
PHPCBF = ./vendor/bin/phpcbf ./app --standard=PSR12
INFECTION = ./vendor/bin/infection

clean:
	rm -rf ./build ./vendor

fix-code-style:
	${PHPCBF}

code-style:
	mkdir -p ./build/logs/phpcs/
	${PHPCS}

coverage:
	${PHPUNIT} && ${COVCHECK} build/logs/phpunit/coverage/coverage.xml 100

test:
	${PHPUNIT} --no-coverage

static-analysis:
	${PHPSTAN} analyse

infection-testing:
	make coverage
	${INFECTION} --coverage=build/logs/phpunit/coverage --min-msi=100 --threads=`nproc`

install-dependencies:
	composer install

update-dependencies:
	composer update

help:
	# Usage:
	#   make <target> [OPTION=value]
	#
	# Targets:
	#   clean               Cleans the coverage and the vendor directory
	#   code-style          Check codestyle using phpcs
	#   static-analysis     Static Code Analysis
	#   coverage            Generate code coverage (html, clover)
	#   help                You're looking at it!
	#   test (default)      Run all the tests with phpunit
	#   test-unit           Run all the tests with phpunit
	#   test-integration    Run all the tests with phpunit
	#   static-analysis     Run static analysis using phpstan
	#   infection-testing   Run infection/mutation testing
	#   install-dependencies Run composerupdate
	#   update-dependencies  Run composer update
