FROM alpine:latest
#6
# for laravel lumen run smoothly
RUN apk --no-cache add \
php7 \
php7-fpm \
php7-pdo \
php7-mbstring \
php7-openssl \
    php7-pdo_pgsql \
    php7-pgsql
# for our code run smoothly
RUN apk --no-cache add \
php7-json \
php7-dom \
curl \
php7-curl

# for swagger run smoothly
RUN apk --no-cache add \
php7-tokenizer

# for composer & our project depency run smoothly
RUN apk --no-cache add \
php7-phar \
php7-xml \
php7-xmlwriter

# if need composer to update plugin / vendor used
RUN php -r "copy('http://getcomposer.org/installer', 'composer-setup.php');" && \
php composer-setup.php --install-dir=/usr/bin --filename=composer && \
php -r "unlink('composer-setup.php');"

# copy all of the file in folder to /src
COPY . /src
WORKDIR /src

RUN composer install
