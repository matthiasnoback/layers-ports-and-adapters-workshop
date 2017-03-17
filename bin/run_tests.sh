#!/usr/bin/env bash

docker-compose up -d
rm var/meetups.txt
docker-compose run --rm devtools /bin/bash -c "vendor/bin/phpunit && vendor/bin/behat -v"
