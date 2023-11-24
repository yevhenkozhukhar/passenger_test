PHP=docker-compose exec php82-service

start:
	docker-compose up -d

down:
	docker-compose down

install:
	$(PHP) composer install

deps:
	docker-compose up -d
	@$(MAKE) install
	$(PHP) bin/console messenger:setup-transports
	$(PHP) bin/console messenger:setup-transports --env=test
	$(PHP) bin/console doctrine:database:create --if-not-exists
	$(PHP) bin/console doctrine:database:create --if-not-exists --env=test
	$(PHP) bin/console doctrine:migrations:migrate
	$(PHP) bin/console doctrine:migrations:migrate --env=test

db:
	$(PHP) bin/console doctrine:database:create --if-not-exists

composer-req:
	$(PHP) composer require $(args)

php:
	docker-compose exec php82-service bash

psql:
	docker-compose exec postgres-service psql -U root -W -d passenger

consume:
	$(PHP) php -dmemory_limit=-1 bin/console messenger:consume async -vv

setup-consumer:
	$(PHP) bin/console messenger:setup-transports

import:
	$(PHP) bin/console postcodes:load

unit:
	$(PHP) ./vendor/bin/phpunit $(args)