# Beanworks Xero Sync App

## Prerequisites

- [docker](https://docs.docker.com/install/)
- [docker-compose](https://docs.docker.com/compose/install/)

## Getting started

Install Project

```bash
make init
```

## Symfony based API
http://localhost:8888/

## Intervative API Documents
https://documenter.getpostman.com/view/2272502/SW7Z599h?version=latest

## Beanworks react based App
http://localhost:3000/

## Login with Sample User
```bash
email: admin@beanworks.com
```
```bash
password: admin
```

#### PHP Unit Test
```bash
make test-api
```

#### PHP Code Standard Fixer
```bash
make lint
```

#### PHP Static Code Analysis
```bash
make static-analysis
```
We are using `PHPStan` [https://github.com/phpstan/phpstan](https://github.com/phpstan/phpstan) for static code analysis.
* Please make sure you get `[OK] No errors` before pushing new code.
                                                                        
                                                                      
#### React Unit Test
```bash
make test-react
```

Checking messages in RabbitMQ

```bash
make rabbit-status
```

Running consumer
```bash
docker-compose exec fpm bin/console messenger:consume
```    

Check logs to confirm messages are processed
                                                                                                                     
```bash
docker-compose exec fpm tail -f var/log/local-<date>.log 
``` 
Supervisor
http://supervisord.org

Login to FPM container

```bash
docker-compose exec fpm bash
```

Start Service
```bash
service supervisor start
```

Status
```bash
supervisorctl status
```

Start
```bash
supervisorctl start messenger-consume-pipeline-processing
```

```bash
supervisorctl start all
```

Stop
```bash
supervisorctl stop all
```

```bash
supervisorctl stop messenger-consume-pipeline-processing
```
