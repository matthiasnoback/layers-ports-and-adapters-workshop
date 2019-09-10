#!/usr/bin/env bash

docker-compose run --rm devtools php -d memory_limit=-1 $(which composer) $@
