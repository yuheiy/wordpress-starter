---
to: wordpress/Dockerfile
---
FROM wordpress:php7.3-apache

RUN apt-get update && apt-get -y install vim

RUN curl -sSLO https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 \
    && chmod +x mhsendmail_linux_amd64 \
    && mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail \
    && echo 'sendmail_path = "/usr/local/bin/mhsendmail --smtp-addr=mailhog:1025"' > /usr/local/etc/php/conf.d/sendmail.ini

RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp \
    && wp --info

RUN docker-php-ext-install pdo_mysql

RUN { \
  echo 'upload_max_filesize=100M'; \
} > /usr/local/etc/php/conf.d/wp-recommended.ini


COPY wp-setup.sh /tmp
RUN chmod +x /tmp/wp-setup.sh
