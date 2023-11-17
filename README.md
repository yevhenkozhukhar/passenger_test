# Passenger PHP test challenge

# Requirements to project

Using docker:
 - Docker
 - Docker Compose

Without docker:
- PHP 8.2
- PostgreSQL 16
- Composer 2

## Description
Project import data for postcodes from data-source and gave api endpoints to get this data

Source code consist fully managed docker-container, that makes run services very easy

Description containers:
 - messenger-consumer-1, messenger-consumer-2 - two consumers running async and handle messages
 - nginx-service - web server 
 - php82-service - main php8.2 running with web-server
 - passenger-postgres - database postgreSQL, save data

## Getting Started

Provide instructions on how to get your project up and running. Include any prerequisites, installation steps.

### Installation

Provide step-by-step instructions on how to install and configure your project.

```
git clone git@github.com:yevhenkozhukhar/passenger_test.git
```

### Installation via make file(recommended - easy to use)

Should have before installation docker and docker-compose

```
make deps
```

### Alternative installation via docker-compose by steps

Start containers:
```
docker-compose up -d
```

Install composer dependencies via docker
```
docker-compose exec php82-service composer install
```

Setup transports 
```
docker-compose exec php82-service bin/console messenger:setup-transports
docker-compose exec php82-service bin/console messenger:setup-transports --env=test
```

Create database and fill with migrations

```
docker-compose exec php82-service bin/console doctrine:database:create --if-not-exists
docker-compose exec php82-service bin/console doctrine:database:create --if-not-exists --env=test
docker-compose exec php82-service bin/console doctrine:migrations:migrate
docker-compose exec php82-service bin/console doctrine:migrations:migrate --env=test
```

### Import postcode data

Command responsible for import data
```
bin/console postcodes:load
```

Running command throw make:
```
make import
```

### Unit tests

Run via make
```
make unit
```

Command for test
```
./vendor/bin/phpunit
```

### Project url running with docker-compose 
```
http://localhost:8080
```

### Api documentation (Swagger UI)

```
http://localhost:8080/api/doc
```
