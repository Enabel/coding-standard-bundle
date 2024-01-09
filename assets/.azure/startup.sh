#!/bin/bash

# Change site root to /public
sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g' "/etc/nginx/sites-available/default"

# Change nginx config to rewrite to index.php
sed -i 's|index  index.php index.html index.htm hostingstart.html;|try_files $uri /index.php$is_args$args;|g' "/etc/nginx/sites-available/default"

# Remove custom error page
sed -i 's|error_page   500 502 503 504  /50x.html;|#error_page   500 502 503 504  /50x.html;|' "/etc/nginx/sites-available/default"

# Symfony .env file
echo "DATABASE_URL=mysql://$AZURE_MYSQL_USERNAME:$AZURE_MYSQL_PASSWORD@$AZURE_MYSQL_HOST:$AZURE_MYSQL_PORT/$AZURE_MYSQL_DBNAME" > /home/site/wwwroot/.env.local
echo "REDIS_URL=rediss://$AZURE_REDIS_PASSWORD@$AZURE_REDIS_HOST:$AZURE_REDIS_PORT/$AZURE_REDIS_DATABASE" >> /home/site/wwwroot/.env.local

# Restart nginx
service nginx reload

# Symfony cache
php /home/site/wwwroot/bin/console cache:warmup

# Symfony database
php /home/site/wwwroot/bin/console doctrine:migrations:migrate --no-interaction

# Symfony translations
php /home/site/wwwroot/bin/console translation:download
