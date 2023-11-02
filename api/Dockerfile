FROM node:18-alpine AS frontend-build

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

RUN npm run build


FROM webdevops/php-nginx:8.2-alpine

WORKDIR /var/www/

ENV PHP_DISMOD=bz2,calendar,exif,ffi,intl,gettext,ldap,imap,pdo_pgsql,pgsql,mysqli,soap,sockets,sysvmsg,sysvsm,sysvshm,shmop,xsl,zip,gd,apcu,vips,yaml,imagick,mongodb,amqp

COPY ./composer.json ./composer.lock* ./
RUN composer install --no-scripts --no-dev --no-interaction --no-progress --ignore-platform-reqs

COPY . .
COPY --from=frontend-build /app/public/build /var/www/public/build

RUN chown -R application:application .  \
    && php artisan storage:link  \
    && composer dumpautoload --optimize
