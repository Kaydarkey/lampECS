#!/bin/bash
set -e

# Set ServerName to suppress warning
echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set up Apache environment variables
echo "export DB_USER=$DB_USERNAME" >> /etc/apache2/envvars
echo "export DB_PASS=$DB_PASSWORD" >> /etc/apache2/envvars
echo "export DB_NAME=$DB_NAME" >> /etc/apache2/envvars
echo "export DB_HOST=$DB_HOST" >> /etc/apache2/envvars

# Configure Apache to serve from the correct directory
echo "<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/php

    <Directory /var/www/html/php>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Health check route
    # Alias /health /var/www/html/php/health.html
    # <Location /health>
    #     Require all granted
    # </Location>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# Set correct permissions
chown -R www-data:www-data /var/www/html/php
chmod -R 755 /var/www/html/php

# echo "Starting CloudWatch Logs agent..."
# /opt/aws/amazon-cloudwatch-agent/bin/amazon-cloudwatch-agent -config /opt/aws/amazon-cloudwatch-agent/etc/amazon-cloudwatch-agent.json &

# Start Apache in the foreground (this will keep the container running)
echo "Starting Apache server..."
apache2-foreground
