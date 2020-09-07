#!/usr/bin/env sh

set -e

vendor/bin/phpunit -vvv test/
vendor/bin/behat
