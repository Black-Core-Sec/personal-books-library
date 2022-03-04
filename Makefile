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

# Use - make s_cli command="{command}"
# Ex: make s_cli command="check:requirements"
s_cli:
	docker exec -it bookslib_php-fpm ./symfony $(command)