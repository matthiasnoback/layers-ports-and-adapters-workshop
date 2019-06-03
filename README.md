# Code and assignments for the "Layers, Ports & Adapters" workshop module

## Installation

On most machines the best way to install this project will be to use Docker. If you have trouble running Docker, or just want to use the PHP runtime that's installed on your machine already, take a look at option 2 below. 

### Option 1: Docker 

#### Requirements

- [Docker Engine](https://docs.docker.com/engine/installation/)
- [Docker Compose](https://docs.docker.com/compose/install/)

#### Getting started

- Make sure the `HOST_UID` and `HOST_GUID` environment variables have been defined:

    ```
    export HOST_GID=$(id -g)
    export HOST_UID=$(id -u)
    ```

- Clone this repository (`git clone git@github.com:matthiasnoback/layers-ports-and-adapters-workshop.git`) and `cd` into it.
- Run `docker-compose pull`.
- Run `bin/composer.sh install --prefer-dist` to install the project's dependencies.
- Run `docker-compose up -d` to start the web server.
- Open <http://localhost/> in a browser. You should see the homepage of the meetup application.

#### Running development tools

- Run `bin/composer.sh` to use Composer (e.g. `bin/composer.sh require symfony/var-dumper`).
- Run `bin/run_tests.sh` to run the tests.
- Run `bin/deptrac.sh` to analyze dependencies.
- Run `bin/meetup.sh` to use the CLI tool for scheduling meetups.

### Option 2: With PHP and Composer

#### Requirements

- PHP (>=7.1)
- Composer

#### Getting started

- Clone this repository (`git clone git@github.com:matthiasnoback/layers-ports-and-adapters-workshop.git`) and `cd` into it.
- Run `composer install --prefer-dist`.
- Start the webserver by running `php -S 0.0.0.0:8080 -t public/`, and keep this process running.
- Open <http://localhost:8080/> in a browser. You should see the homepage of the meetup application.
- Optionally install [deptrac](https://github.com/sensiolabs-de/deptrac).

#### Running development tools

- Run `vendor/bin/phpunit && vendor/bin/behat -v` to run all the tests (make sure that you also still have the webserver running). 
- Run `./meetup` to work with the command-line version of this application.
