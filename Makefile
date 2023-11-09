PHP=docker-compose exec php82-service

install:
	$(PHP) composer install

db:
	$(PHP) bin/console doctrine:database:create --if-not-exists