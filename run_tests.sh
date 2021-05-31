#!/usr/bin/env sh

set -e

vendor/bin/phpstan analyze
vendor/bin/phpunit -vvv
vendor/bin/behat --suite system
