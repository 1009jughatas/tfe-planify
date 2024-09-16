## Installer project
php artisan migrate
composer install

## Factory sur base d'un model + seeder
php artisan make:factory UserFactory --model=User
php artisan make:seeder UserSeeder
php artisan db:seed

## Clear
composer dump-autoload 
php artisan route:clear                             
php artisan config:clear
php artisan cache:clear

## Lister les routes
php artisan route:list 