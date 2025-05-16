FROM php:8.1-apache

# Install dependency packages
RUN apt-get update
# DP deps: mariadb-client gettext aspell aspell-fr pngcheck
# Handy dev deps: unzip git
RUN apt-get install -y mariadb-client gettext aspell aspell-fr pngcheck git unzip

# Install PHP extensions
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions intl mbstring memcached gettext mysqli xml zip

# And composer
COPY install_composer.sh /tmp
RUN bash /tmp/install_composer.sh && mv composer.phar /usr/local/bin/composer && rm /tmp/install_composer.sh

# Install nodejs/npm courtesy of https://deb.nodesource.com/
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && apt-get install -y nodejs

# Create the dyndir mount point with the right permissions
RUN mkdir -p /var/www/html/d && chown www-data:www-data /var/www/html/d

COPY index_redirect.php /var/www/html/index.php
COPY container_startup.sh /usr/local/bin

# Create the mount point for our source code and make it the WORKDIR
# This means when we exec into the container we start at the base of the
# source tree.
RUN mkdir -p /var/www/html/c
WORKDIR /var/www/html/c

CMD ["/usr/local/bin/container_startup.sh"]
