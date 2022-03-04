up:
	rm -f ./docker/logs/*.log	rm -f ./docker/logs/*.txt
	docker-compose -f docker-compose.yml --env-file=./docker/.env up -d

stop:
	docker-compose -f docker-compose.yml --env-file=./docker/.env stop

restart:
	docker-compose -f docker-compose.yml --env-file=./docker/.env restart

down:
	docker-compose -f docker-compose.yml --env-file=./docker/.env down

rm:
	docker-compose -f docker-compose.yml --env-file=./docker/.env rm -sf