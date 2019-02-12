Some info behind task..

Roles and Permissions currently is a little bit messy and "a bit" complicated
It's not how I see them in PROD ready application but because of limited time for this task I was not able to implement polymorphism in database

Some of permissions is higher like

view-others-tasks > view-tasks
update-others-tasks > update-tasks

Currently it's only API without UI.
Please run INTEGRATION TESTS to test Application flow.

To setup project

php artisan migrate
php artisan db:seed
php artisan jwt:secret

# Tested on

Ubuntu, Linux
PHP 7.2
MySQL Distrib 5.7.25

If something will fail please contact me and I will add docker for virtualisation.


POST
 auth/login 


email user@gmail.com password 123456

Response: token


All other actions requires authorization header with provided token.

Please look at web.php file for all routes.

No API documentation provided for the moment because I'm out of time limit.
If you will have problems with current code base, let me know. :)

Please see DatabaseSeeder to better understand relationships and etc.
