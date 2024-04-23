#!/bin/bash

# Change site root to /public
sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g' "/etc/nginx/sites-available/default"

# Change nginx config to rewrite to index.php
sed -i 's|index  index.php index.html index.htm hostingstart.html;|try_files $uri /index.php$is_args$args;|g' "/etc/nginx/sites-available/default"

# Remove custom error page
sed -i 's|error_page   500 502 503 504  /50x.html;|#error_page   500 502 503 504  /50x.html;|' "/etc/nginx/sites-available/default"

# Force HTTPS
sed -i 's|fastcgi_param HTTP_PROXY "";|fastcgi_param HTTP_PROXY "";\n\tfastcgi_param HTTPS "on";|' "/etc/nginx/sites-available/default"

# Increase client_max_body_size
sed -i 's|server {|server {\n\tclient_max_body_size 100M;|' "/etc/nginx/sites-available/default"

# Symfony .env file
echo "DATABASE_URL=mysql://$AZURE_MYSQL_USERNAME:$AZURE_MYSQL_PASSWORD@$AZURE_MYSQL_HOST:$AZURE_MYSQL_PORT/$AZURE_MYSQL_DBNAME" > /home/site/wwwroot/.env.local
echo "REDIS_URL=rediss://$AZURE_REDIS_PASSWORD@$AZURE_REDIS_HOST:$AZURE_REDIS_PORT/$AZURE_REDIS_DATABASE" >> /home/site/wwwroot/.env.local
echo "APP_ENV=prod" >> /home/site/wwwroot/.env.local

# PHP.ini
echo "date.timezone=Europe/Brussels" > /usr/local/etc/php/conf.d/symfony.ini
echo "post_max_size=80M" >> /usr/local/etc/php/conf.d/symfony.ini
echo "upload_max_filesize=50M" >> /usr/local/etc/php/conf.d/symfony.ini

# Restart nginx
service nginx reload

# Symfony cache
php /home/site/wwwroot/bin/console cache:warmup

# Symfony database
php /home/site/wwwroot/bin/console doctrine:migrations:migrate --no-interaction

## Translations section uncomment if you use Translations
# Symfony translations
#php /home/site/wwwroot/bin/console translation:download

# Symfony clear cache
php /home/site/wwwroot/bin/console cache:clear

## Typesense section uncomment if you use Typesense
# Symfony recreate index
#php /home/site/wwwroot/bin/console typesense:create -n

# Symfony reindex data
#php /home/site/wwwroot/bin/console typesense:import -n
