build: ## [host] docker-compose build
	docker-compose build --pull

run:  ## [host] run containers
	docker-compose up -d

reset-db: ## [host] Remove database and recreate full schema
	docker-compose exec -T php-fpm sh -c "\
		./bin/console doctrine:database:drop --force --if-exists && \
		./bin/console doctrine:database:create  --if-not-exists && \
    	./bin/console doctrine:migration:migrate --no-interaction && \
    	./bin/console hautelook:fixtures:load --no-interaction"

clean: ## [host] Clean cache, logs
	docker-compose exec php-fpm rm -r var/cache/* var/log/*

consume-messages: ## [host] Consume async messages
	docker-compose exec php-fpm sh -c "bin/console messenger:consume async -vv --limit=100 --time-limit=3600"

tests: run ## [host] Consume async messages
	docker-compose exec -T php-fpm bin/phpunit

