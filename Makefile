
.PHONY: init
init:
	$(MAKE) build
	docker-compose up -d
	$(MAKE) composer-install
	$(MAKE) npm-install
	$(MAKE) initialize-mongodb
	$(MAKE) create-admin-user
	$(MAKE) supervisor-service-start
	$(MAKE) supervisor-workers-up

build:
	docker-compose build --build-arg DOCKER_UID=$(shell id -u) --compress

.PHONY: initialize-mongodb
initialize-mongodb:
	docker-compose exec fpm ./bin/console doctrine:mongodb:schema:create

.PHONY: create-admin-user
create-admin-user:
	docker-compose exec fpm ./bin/console app:initialize:database


.PHONY: test-api
test-api:
	docker-compose exec fpm bin/phpunit

.PHONY: static-analysis
static-analysis:
	docker-compose exec -u www-data fpm ./vendor/bin/phpstan --level=1 --memory-limit=1G analyse src 

.PHONY: enable-xdebug
enable-xdebug:
	docker-compose exec fpm docker-php-ext-enable xdebug
	docker-compose restart fpm nginx

.PHONY: disable-xdebug
disable-xdebug:
	docker-compose exec fpm rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
	docker-compose restart fpm nginx

.PHONY: lint
lint:
	docker-compose exec -u www-data fpm vendor/bin/php-cs-fixer fix $(if $(TRAVIS),--diff-format=udiff --dry-run,-v)

.PHONY: composer-install
composer-install:
	docker-compose exec -u www-data fpm composer install $(if $(TRAVIS),--no-progress --no-suggest)

.PHONY: npm-install
npm-install:
	docker-compose exec react-app npm install

.PHONY: test-react
test-react:
	docker-compose exec react-app npm test

.PHONY: app-up
app-up:

	docker-compose up -d

.PHONY: app-down
app-down:
	docker-compose down

.PHONY: rabbit-status
rabbit-status:
	docker-compose exec rabbitmq rabbitmqctl list_queues


.PHONY: supervisor-service-start
supervisor-service-start:
	docker-compose exec fpm service supervisor start

.PHONY: supervisor-workers-up
supervisor-workers-up:
	docker-compose exec fpm supervisorctl start all

PHONY: supervisor
supervisor:
	docker-compose exec fpm supervisorctl



