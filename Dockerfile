# Dockerfile
FROM php:apache

# Instalação das dependências do Adianti Framework
RUN apt-get update && \
    apt-get install -y \
        unzip \
        libmcrypt-dev \
        zlib1g-dev \
        libicu-dev \
    && docker-php-ext-install -j$(nproc) mcrypt intl pdo_mysql

# Adiciona as configurações do Apache para o Adianti Framework
COPY apache/adianti.conf /etc/apache2/sites-available/adianti.conf
RUN a2ensite adianti.conf && service apache2 restart