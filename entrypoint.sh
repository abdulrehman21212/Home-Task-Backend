#!/bin/bash

set -e


until php artisan migrate --seed; do
  sleep 1
done
# Display a message in the terminal
printf "Step 2: Migration and seeding completed successfully."


php artisan fetch:articles;
printf "Step 3: Command To Fetch Articles ran succesffully."

# Start PHP-FPM
exec php-fpm
