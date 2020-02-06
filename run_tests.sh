#!/usr/bin/env bash

set -e

vendor/bin/phpunit --testsuite "Unit tests"
vendor/bin/phpunit --testsuite "Adapter tests"
vendor/bin/behat
