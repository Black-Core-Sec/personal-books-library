build:
	docker-compose -f docker-compose.yml --env-file=./docker/.env build --no-cache

up:
	rm -f ./docker/logs/*.log	rm -f ./docker/logs/*.txt
	docker-compose -f docker-compose.yml --env-file=./docker/.env up -d
	make migrate

stop:
	docker-compose -f docker-compose.yml --env-file=./docker/.env stop

restart:
	docker-compose -f docker-compose.yml --env-file=./docker/.env restart

down:
	docker-compose -f docker-compose.yml --env-file=./docker/.env down

rm:
	docker-compose -f docker-compose.yml --env-file=./docker/.env rm -sf

### EXEC

# Use - make php c="{command}"
# Ex: make php c="php --version"
php:
	docker exec -it bookslib_php-fpm $(c)

php-www:
	docker exec -it --user www-data bookslib_php-fpm $(c)

console:
	docker exec -it bookslib_php-fpm php bin/console $(c)

migrate:
	docker exec -it bookslib_php-fpm php bin/console doctrine:migrations:migrate --no-interaction

user:
	docker exec -it bookslib_php-fpm php bin/console fos:user:create

test:
	#docker exec -it bookslib_php-fpm php bin/console --env=test doctrine:database:drop --force
	docker exec -it bookslib_php-fpm php bin/console --env=test doctrine:database:create --no-interaction
	docker exec -it bookslib_php-fpm php bin/console --env=test doctrine:migrations:migrate --no-interaction

testing:
	docker exec -it bookslib_php-fpm php bin/phpunit

fixtures:
	docker exec -it bookslib_php-fpm php bin/console --env=test doctrine:fixtures:load --no-interaction