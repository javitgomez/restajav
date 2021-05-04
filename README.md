# use this when the projects starts
php bin/console doctrine:schema:update --force
php bin/console assets:install public
composer install
