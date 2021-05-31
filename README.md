# Sandbox project for the "Advanced Web Application Architecture" training

You'll find all the available training programs here: <https://matthiasnoback.nl/training/>

## Installation

On most machines the easiest way to install this project is with Docker. If you have trouble running Docker, or just want to use the PHP runtime that's installed on your machine already, take a look at option 2 below. 

### Option 1: Docker 

#### Requirements

- Docker Engine
- Docker Compose
- Git
- Bash

#### Getting started

- Clone this repository (`git clone git@github.com:matthiasnoback/layers-ports-and-adapters-workshop.git`) and `cd` into it.
- Run `bin/install`.
- Open <http://localhost:8080> in a browser. You should see the homepage of the meetup application.

If port 8080 is no longer available on your local machine, modify `docker-compose.yml` to publish to another port:

```yaml
ports:
    # To try port 8081:
    - "8081:8080"
```

#### Running development tools

- Run `bin/composer.sh` to use Composer (e.g. `bin/composer.sh require symfony/var-dumper`).
- Run `bin/run_tests.sh` to run the tests.
- Run `bin/deptrac.sh` to analyze dependencies.
- Run `bin/meetup.sh` to use the CLI tool for scheduling meetups.

#### Cleaning up after the workshop

- Run `bin/cleanup` to remove all containers for this project, their images, and their volumes.
- Remove the project directory.
- If you don't use Docker normally, you can shut it down or uninstall it too.

### Option 2: With PHP and Composer

#### Requirements

- PHP (>=7.4)
- Composer
- Git
- Bash

#### Getting started

- Clone this repository (`git clone git@github.com:matthiasnoback/layers-ports-and-adapters-workshop.git`) and `cd` into it.
- Run `composer install --prefer-dist`.
- Start the webserver by running `php -S 0.0.0.0:8080 -t public/`, and keep this process running.
- Open <http://localhost:8080/> in a browser. You should see the homepage of the meetup application.
- Optionally install [deptrac](https://github.com/sensiolabs-de/deptrac).

#### Running development tools

- Run `./run_tests.sh` to run all the tests (make sure that you also still have the webserver running). 
- Run `./meetup` to work with the command-line version of this application.

#### Cleaning up after the workshop

- Just remove the project directory.
