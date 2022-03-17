up:
	rm -f ./docker/logs/*.log	rm -f ./docker/logs/*.txt
	docker-compose -f docker-compose.yml --env-file=./docker/.env up -d

build:
	docker-compose -f docker-compose.yml --env-file=./docker/.env build --no-cache

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

console:
	docker exec -it bookslib_php-fpm php bin/console $(c)

migrate:
	docker exec -it bookslib_php-fpm php bin/console doctrine:migrations:migrate

user:
	docker exec -it bookslib_php-fpm php bin/console fos:user:create
