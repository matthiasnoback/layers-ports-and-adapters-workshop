# Code and assignments for the "Layers, Ports & Adapters" workshop module

## Requirements

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Getting started

- Clone this repository and `cd` into it.
- Run `docker-compose pull`.
- Run `bin/composer.sh install --prefer-dist` to install the project's dependencies.
- [Follow the instructions](https://github.com/matthiasnoback/php-workshop-tools/blob/master/README.md) for setting environment variables and configuring PhpStorm for debugging.
- Run `docker-compose up -d` to start the web server.
- Go to [http://localhost/](http://localhost/) in a browser. You should see the homepage of the meetup application.

## Running development tools

- Run `bin/composer.sh` to use Composer (e.g. `bin/composer.sh require symfony/var-dumper`).
- Run `bin/run_tests.sh` to run the tests.
- Run `bin/deptrac.sh` to analyze dependencies.
- Run `bin/meetup.sh` to use the CLI tool for scheduling meetups.
