FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libzip-dev \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Increase upload limits
COPY docker/php-uploads.ini /usr/local/etc/php/conf.d/99-uploads.ini

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files and install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy package files and install Node dependencies + build assets
COPY package.json package-lock.json* ./
RUN npm install
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
RUN npm run build

# Copy the rest of the application
COPY . .

# Re-run composer scripts after full code is available
RUN composer dump-autoload --optimize

# Create storage directories
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

# Start the application
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php artisan codex:seed-if-empty --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8000}




