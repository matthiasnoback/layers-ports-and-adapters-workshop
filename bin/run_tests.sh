#!/usr/bin/env bash

docker-compose up -d
docker-compose run --rm devtools /bin/bash -c \
    "vendor/bin/phpunit --testsuite unit && \
    vendor/bin/phpunit --testsuite integration && \
    vendor/bin/behat --suite acceptance -v && \
    vendor/bin/behat --suite system
    "
