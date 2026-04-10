FROM php:8.2-cli

# 必要なパッケージ
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Composerインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリ
WORKDIR /app

# ファイルコピー
COPY . .

# Laravelセットアップ
RUN composer install --no-dev --optimize-autoloader

# ポート
EXPOSE 10000

# 起動
CMD php artisan serve --host=0.0.0.0 --port=10000