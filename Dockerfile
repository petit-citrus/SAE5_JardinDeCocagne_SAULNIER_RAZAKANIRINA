
FROM php:8.3-cli-bookworm

# Installer les bibliothèques nécessaires à Symfony
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install \
        intl \
        pdo_pgsql \
        zip \
    && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dossier de travail dans le conteneur
WORKDIR /app

# Configuration de développement
ENV APP_ENV=dev

# Port utilisé par le serveur PHP
EXPOSE 8000

# Démarrer l'application Symfony
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public", "public/router.php"]