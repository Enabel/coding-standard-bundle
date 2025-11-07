#!/bin/bash

# Install supervisor
apt update -qq
apt install supervisor -yqq

# Start supervisor daemon
supervisord -c /home/site/wwwroot/.azure/supervisor/supervisord.conf
mkdir -p /home/LogFiles/supervisor

# Update supervisor configuration
supervisorctl reread
supervisorctl update

# Start supervisor workers
supervisorctl start messenger-async:*
