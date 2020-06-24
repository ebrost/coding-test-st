# ReadMe

This project uses 4 docker containers (Nginx+ Php-fpm+ MariaDB+RabbitMQ).
The whole configuration is on the `docker-compose` file and in the `docker` folder.
the worker is actually running on the php-pm container. This is obviously not suitable for production as it should run on a dedicated container.

By default, Nginx is exposed through port 80 and you can access to rabbitMQ's management console on port 15672

####Side note

doctrine entities are exposed as ApiResource. On a "real" project, it might be more relevant to use DTO as input/outpout for request and response of the api.

## Installation
A Makefile is available at the root of the project to make things easy .. 

clone this repository then `make build` then `make run`. (If you prefer docker commands, `docker-compose up --build -d` should do the trick)

You will have to create the database and load the fixtures with `make reset-db`

To consume the message : `make consume-messages` 

## Api

The api is based on api-platform project (https://api-platform.com/).
You can use the web interface at http:// 127.0.0.1 and have a look to the curl commands and parameters to use.

## Testing

the `test` environment uses the `in-memory`transport. All tests reside in `test` folder

## Export
the export command can be launched with 

`docker-compose exec php-fpm bin/console app:history:export <entityClass> [<entityId>]` 

