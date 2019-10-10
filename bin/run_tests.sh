#!/usr/bin/env bash

docker-compose up -d
docker-compose run --rm devtools /bin/bash -c "./run_tests.sh"
