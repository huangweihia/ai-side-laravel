FROM php:8.2-fpm

# 国内服务器构建时 apt 访问官方源可能较慢；构建前统一替换为阿里云镜像并增加重试
RUN set -eux; \
  for f in /etc/apt/sources.list /etc/apt/sources.list.d/*.list /etc/apt/sources.list.d/*.sources; do \
    [ -f "$f" ] || continue; \
    sed -i 's|http://deb.debian.org|https://mirrors.aliyun.com|g' "$f"; \
    sed -i 's|https://deb.debian.org|https://mirrors.aliyun.com|g' "$f"; \
    sed -i 's|http://security.debian.org|https://mirrors.aliyun.com|g' "$f"; \
    sed -i 's|https://security.debian.org|https://mirrors.aliyun.com|g' "$f"; \
  done; \
  printf 'Acquire::Retries "5";\nAcquire::http::Timeout "30";\nAcquire::https::Timeout "30";\nAcquire::ForceIPv4 "true";\n' > /etc/apt/apt.conf.d/99retries

# 安装系统依赖
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    nodejs \
    npm \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

# 安装 PHP 扩展
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# 上传与后台表单（Livewire / Filament）
RUN printf '%s\n' \
        'upload_max_filesize=64M' \
        'post_max_size=64M' \
        'max_execution_time=120' \
        'max_input_time=120' \
    > /usr/local/etc/php/conf.d/uploads.ini

# 安装 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 设置工作目录
WORKDIR /var/www/html

# 创建系统用户
RUN useradd -G www-data,root -u 1000 -d /home/laravel laravel
RUN mkdir -p /home/laravel/.composer && \
    chown -R laravel:laravel /home/laravel

# 设置文件权限
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
