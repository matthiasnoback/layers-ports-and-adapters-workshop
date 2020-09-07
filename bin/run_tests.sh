#!/usr/bin/env bash

docker-compose up -d
docker-compose run --rm php sh -c "./run_tests.sh"
