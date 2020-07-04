FROM debian:10.3-slim
RUN \
  apt-get update -qq && \
  apt-get install -qq --no-install-recommends -y \
  curl \
  wget \
  git \
  apt-transport-https \
  lsb-release \
  ca-certificates \
  apt-utils && \
  # add php-7.4 repository
  wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
  && echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php7.4.list && \
  #
  apt-get update -qq --fix-missing && \
  apt-get install -qq -y --no-install-recommends \
  php7.4 \
  php7.4-fpm \
  php7.4-cli \
  php-xdebug \
  php7.4-common \
  php7.4-opcache \
  php7.4-zip \
  php7.4-curl \
  php7.4-xml \
  php7.4-mbstring  && \
  # composer installation
  curl -sSk https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
  mkdir -p /run/php && \
  rm -rf /var/lib/apt/lists/*

WORKDIR /code

COPY ./code/composer.json /code/


ENTRYPOINT ["/usr/sbin/php-fpm7.4","--nodaemonize"]