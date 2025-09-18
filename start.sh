#!/bin/bash

# Step 1: Ask for DB credentials
echo "Enter database name:"
read DB_NAME

echo "Enter database user:"
read DB_USER

echo "Enter database password:"
read -s DB_PASS
echo ""

DB_HOST="127.0.0.1"
DB_PORT="3306"

# Step 2: Attempt to create the database
echo "Creating database '$DB_NAME'..."
mysql -h $DB_HOST -P $DB_PORT -u $DB_USER -p$DB_PASS -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if [ $? -ne 0 ]; then
    echo "Error: Unable to create database '$DB_NAME'. Please check your credentials and try again."
    exit 1
fi

echo "Database '$DB_NAME' created or already exists."

# Step 3: Generate .env file
cat > .env <<EOL
APP_NAME="SlimApp"
APP_ENV="development"
APP_DEBUG=true

DB_HOST=$DB_HOST
DB_PORT=$DB_PORT
DB_NAME=$DB_NAME
DB_USER=$DB_USER
DB_PASS=$DB_PASS
DB_CHARSET=utf8mb4

OAUTH_PRIVATE_KEY_PATH=oauth-private.key
OAUTH_ENCRYPTION_KEY=lxZFUEsBCJ2Yb14IF2ygAHI5N4+ZAUXXaSeeJm6+twsUmIen


ACCESS_TOKEN_TTL=3600       # 1 hour in seconds
REFRESH_TOKEN_TTL=1209600   # 14 days in seconds

EOL

echo ".env file created successfully."

# Step 4: Composer install
if [ -f "composer.json" ]; then
    echo "Running composer install..."
    rm composer.lock
    composer install
    if [ $? -ne 0 ]; then
        echo "Error: Composer install failed."
        exit 1
    fi
else
    echo "Warning: composer.json not found. Skipping composer install."
fi

# Step 5: Ask to run migrations
read -p "Do you want to run migrations now? (y/n): " RUN_MIGRATIONS
if [[ "$RUN_MIGRATIONS" == "y" || "$RUN_MIGRATIONS" == "Y" ]]; then
    if [ -f "src/Database/Migrations/migrate.php" ]; then
        echo "Running migrations..."
        php src/Database/Migrations/migrate.php
        if [ $? -ne 0 ]; then
            echo "Error: Migrations failed."
            exit 1
        fi
    else
        echo "Error: migrate.php not found. Cannot run migrations."
    fi
fi

# Step 6: Ask to run seeders
read -p "Do you want to run seeders now? (y/n): " RUN_SEEDS
if [[ "$RUN_SEEDS" == "y" || "$RUN_SEEDS" == "Y" ]]; then
    if [ -f "src/Database/Seeders/Run.php" ]; then
        echo "Running seeders..."
        php src/Database/Seeders/Run.php
        if [ $? -ne 0 ]; then
            echo "Error: Seeding failed."
            exit 1
        fi
    else
        echo "Error: Run.php not found. Cannot run seeders."
    fi
fi

echo "\nLet's update composer once\n"
composer update
echo "\nDump Autoload Now"
composer dump-autoload
echo "\n\nSetup completed successfully!\n"

echo "------Let's Start Server----\n"
php -S localhost:8000 -t public