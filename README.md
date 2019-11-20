# Beanworks Xero Sync App

## Prerequisites

- [docker](https://docs.docker.com/install/)
- [docker-compose](https://docs.docker.com/compose/install/)

## Getting started

Install Project

```bash
make init
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
                                                                        

#### Pipeline messenger with async transport using RabbitMQ 


```bash
docker-compose build
docker-compose up -d

docker-compose exec fpm composer install
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