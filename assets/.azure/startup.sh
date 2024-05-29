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

# Symfony .env file [Need to redefine all the variables (release pipeline) here, because the messenger worker doesn't have access to the environment variables]
echo "DATABASE_URL=mysql://$AZURE_MYSQL_USERNAME:$AZURE_MYSQL_PASSWORD@$AZURE_MYSQL_HOST:$AZURE_MYSQL_PORT/$AZURE_MYSQL_DBNAME" > /home/site/wwwroot/.env.local
echo "REDIS_URL=rediss://$AZURE_REDIS_PASSWORD@$AZURE_REDIS_HOST:$AZURE_REDIS_PORT/$AZURE_REDIS_DATABASE" >> /home/site/wwwroot/.env.local
echo "APP_ENV=prod" >> /home/site/wwwroot/.env.local
echo "TRUSTED_PROXIES=$TRUSTED_PROXIES" >> /home/site/wwwroot/.env.local
echo "TRUSTED_HOSTS=$TRUSTED_HOSTS" >> /home/site/wwwroot/.env.local
echo "MAILER_DSN=$MAILER_DSN" >> /home/site/wwwroot/.env.local

# Get composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Compile .env file
composer dump-env prod -nq -d /home/site/wwwroot

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

# Symfony translations
#php /home/site/wwwroot/bin/console translation:download

# Symfony clear cache
php /home/site/wwwroot/bin/console cache:clear

# Symfony recreate index
#php /home/site/wwwroot/bin/console typesense:create -n

# Symfony reindex data
#php /home/site/wwwroot/bin/console typesense:import -n

# Install cron
apt update -qq
apt install cron -yqq

# Add a cron job to run a worker for messenger [async] every 15 minutes
(crontab -l ; echo "*/15 * * * * /usr/local/bin/php /home/site/wwwroot/bin/console messenger:consume async --time-limit=1000 --env=prod -v 2>&1 | /usr/bin/logger -t CRONOUTPUT") | crontab -

# Start the cron service
service cron start
