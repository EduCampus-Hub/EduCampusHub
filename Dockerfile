FROM php:8.2-apache

# Copia l'eseguibile di Composer direttamente dall'immagine ufficiale
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Abilita l'estensione PDO MySQL per comunicare col DB
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Abilita il modulo rewrite di Apache
RUN a2enmod rewrite

# Permette al file .htaccess di sovrascrivere le regole di Apache
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf