PHP=docker-compose exec php82-service

start:
	docker-compose up -d

down:
	docker-compose down

install:
	$(PHP) composer install

db:
	$(PHP) bin/console doctrine:database:create --if-not-exists

composer-req:
	$(PHP) composer require $(args)

php:
	docker-compose exec php82-service bash

psql:
	docker-compose exec postgres-service psql -U root -W -d passenger_db2

consume:
	$(PHP) php -dmemory_limit=-1 bin/console messenger:consume -vv

setup-consumer:
	$(PHP) bin/console messenger:setup-transports