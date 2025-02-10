FROM php:8.3-cli-alpine AS sio_test
RUN apk add --no-cache git zip bash

RUN apk add --no-cache netcat-openbsd
# Setup php extensions
RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo_pgsql pdo_mysql

ENV COMPOSER_CACHE_DIR=/tmp/composer-cache
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Setup php app user
ARG USER_ID=1000
RUN adduser -u ${USER_ID} -D -H app

COPY --chown=app ./.env.dev /app/.env
COPY --chown=app --chmod=+x docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER app

COPY --chown=app . /app
WORKDIR /app

ENTRYPOINT ["entrypoint.sh"]

EXPOSE 8337

CMD ["php", "-S", "0.0.0.0:8337", "-t", "public"]