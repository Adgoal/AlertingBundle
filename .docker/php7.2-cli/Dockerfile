FROM php:7.2-cli

RUN apt-get update && apt upgrade -y

RUN apt-get install -y git unzip

# -------------------- Installing PHP Extension: redis --------------------
RUN set -eux \
    # Installation: Generic
    # Type:         PECL extension
    # Default:      Pecl command
    && pecl install redis \
    && docker-php-ext-enable redis \
    && true

COPY php.ini /usr/local/etc/php/conf.d/default-php.ini
