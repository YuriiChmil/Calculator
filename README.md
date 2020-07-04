# Refactor test project

## Configuring Development environment with Docker:

1. Install docker and docker-compose
2. In root project directory run ```docker-compose up -d```
3. Go inside container ```docker exec -it refactor-php-fpm bash```
4. Run  ```composer install```
5. To run refactored code sample run ```php index.php input.txt ```
6. To run old code sample run ```php app.php input.txt ```
7. To run test run ```./vendor/bin/phpunit tests ```
