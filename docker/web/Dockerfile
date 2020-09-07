# Build from official PHP image
FROM php:7.4-cli-alpine
COPY php.ini ${PHP_INI_DIR}

# Prepare for mounting the project's code as a volume
VOLUME /app
WORKDIR /app

# Expose a running instance of PHP's built-in web server

# The built-in PHP webserver only responds to SIGINT, not to SIGTERM
STOPSIGNAL SIGINT

EXPOSE 8080
ENTRYPOINT ["php", "-S", "0.0.0.0:8080", "-t", "public/"]
