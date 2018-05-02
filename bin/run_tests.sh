#!/usr/bin/env bash

docker-compose up -d
docker-compose run --rm devtools /bin/bash -c \
    "vendor/bin/phpunit --group unit && \
     vendor/bin/phpunit --group integration && \
     vendor/bin/behat --suite acceptance && \
     vendor/bin/behat --suite system -v"
