#!/bin/bash

# Replace nginx config
cp /home/site/wwwroot/.azure/nginx/default /etc/nginx/sites-available/default

# Add health check
cp /home/site/wwwroot/.azure/nginx/health.php /home/site/wwwroot/public/health.php

# Add symfony php.ini
cp /home/site/wwwroot/.azure/php/php.ini /usr/local/etc/php/conf.d/symfony.ini

# Add php-apcu [Background process]
/home/site/wwwroot/.azure/apcu/install.sh

# Symfony .env file [Need to redefine all the variables (release pipeline) here, because the messenger worker doesn't have access to the environment variables]
echo "DATABASE_URL=mysql://$AZURE_MYSQL_USERNAME:$AZURE_MYSQL_PASSWORD@$AZURE_MYSQL_HOST:$AZURE_MYSQL_PORT/$AZURE_MYSQL_DBNAME" > /home/site/wwwroot/.env.local
echo "REDIS_URL=rediss://$AZURE_REDIS_PASSWORD@$AZURE_REDIS_HOST:$AZURE_REDIS_PORT/$AZURE_REDIS_DATABASE" >> /home/site/wwwroot/.env.local
echo "APP_ENV=prod" >> /home/site/wwwroot/.env.local
echo "AZURE_CLIENT_ID=$AZURE_CLIENT_ID" >> /home/site/wwwroot/.env.local
echo "AZURE_CLIENT_SECRET=$AZURE_CLIENT_SECRET" >> /home/site/wwwroot/.env.local
echo "TRUSTED_PROXIES=$TRUSTED_PROXIES" >> /home/site/wwwroot/.env.local
echo "TRUSTED_HOSTS=$TRUSTED_HOSTS" >> /home/site/wwwroot/.env.local
echo "MAILER_DSN=$MAILER_DSN" >> /home/site/wwwroot/.env.local

# Get composer
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Allow composer to run as root
export COMPOSER_ALLOW_SUPERUSER=1;

# Compile .env files for production use
composer dump-env prod -nq -d /home/site/wwwroot

# Download the latest version of Adminer
wget -O /home/site/wwwroot/public/adminer.php https://www.adminer.org/latest.php

# Restart nginx
service nginx restart

# Move old cache
mv /home/site/wwwroot/var/cache/prod /home/site/wwwroot/var/cache/old

# Symfony clear cache pools
php /home/site/wwwroot/bin/console cache:pool:clear --all
php /home/site/wwwroot/bin/console cache:clear

# Symfony database
php /home/site/wwwroot/bin/console doctrine:migrations:migrate --no-interaction

# Test mailer (generate a test email & warning to no allowed ip)
php /home/site/wwwroot/bin/console mailer:test --from noreply@enabel.be --subject "Test mailer: $WEBSITE_SITE_NAME" --body "This is a test email from $WEBSITE_SITE_NAME" dl@enabel.be

# Add supervisor [Background process]
/home/site/wwwroot/.azure/supervisor/install.sh

# Remove old cache
rm -rf /home/site/wwwroot/var/cache/old
