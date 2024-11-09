FROM php:8.2-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copier Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers composer.json et composer.lock
COPY composer.json ./

# Installer les dépendances de Composer
# RUN composer install --no-interaction --optimize-autoloader

# Copier le reste du code
COPY . .

# Exécuter les scripts post-installation
RUN composer dump-autoload --optimize

# Changer les permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
