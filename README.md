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

## Interactive API Documents
https://documenter.getpostman.com/view/2272502/SW7Z599h?version=latest

## Product Hunt
https://www.producthunt.com/posts/beanworks-xero-sync

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

# Beanworks Challenge

Email: ashwani4u488@gmail.com

# Synopsis

A sync solution that reflects Beanworks core product truths (user-friendly, easy on the

eyes, informative).

# Functional use case

[https://drive.google.com/file/d/1nWXz\_kZptxF9Ldm1uiJNTiiAZUkeP294/view](https://drive.google.com/file/d/1nWXz_kZptxF9Ldm1uiJNTiiAZUkeP294/view)


# Technical choices

- React Based Front-End
- Symfony Framework based rest API Back-End [https://symfony.com/](https://symfony.com/)
- RabbitMQ [https://www.rabbitmq.com/](https://www.rabbitmq.com/)

RabbitMQ will be the backend in charge of queueing messages. The Frontend application(react app) will call API (Symfony(PHP) based Backend APP) and Backend App will send new messages to it, then PHP consumers will be in charge of consuming them and take action accordingly.

- MongoDB
- Docker 
- Supervisor

Supervisor will be used to run sync consumers

# Detailed solution

Xero: Data Attributes

[https://developer.xero.com/documentation/api/types](https://developer.xero.com/documentation/api/types)

# Mongo DB Data schema

Database: beanworks

Collections:

User: User detail

Accounts: Account record imported from Zero

Vendors: Vendor record imported from Xero

Pipelines: Sync pipeline document

Pipeline_logs: Logs related to a particular pipeline


# User Clicks Sync Button in UI backend API  is invoked

- Initialize new sync pipeline
- Insert in sync document in mongo
- Dispatch sync message (push message to RabbbitMQ)

# Pipeline processing
- Sync message will be handled by API consumer
- Update sync document, Pipeline logs

# How Account records are synched with XERO
- Export accounts from Xero
- Check is account id exists in MongoDB
  - If yes remove the document
- Insert a new document
- Update the Pipeline and Pipeline_logs document with progress metadata

# How Vendor Records are synched with XERO

- Export Vendors (contacts type Supplier) from Xero
- Check is account id exists in MongoDB
  - If yes remove the document
- Insert a new document
- Update the Pipeline and Pipeline_logs document with progress metadata

# API

Detailed Interactive API Documentation:

[https://documenter.getpostman.com/view/2272502/SW7Z599h?version=latest](https://documenter.getpostman.com/view/2272502/SW7Z599h?version=latest)

# Next step If I had time

# Features
- Outh2 and 2-factor authentication
- Elastic search integration
- Account and Vendor free text search
- Pushing application logs (Haproxy logs, api logs etc)
- Kibana Dashboard for reporting


# Deployment and Build
- Use Content Delivery Network(CDN) like cloud flare for Beanworks react App
- MongoDB clusters
- Deploy solution on cloud (pref AWS)
- Travis For building Image
- Jenkins for deploying a tag-based release

# Monitoring and Alerts
- Sentry Integration
- Data Dog Dashboard
- MongoDB connections and usage
- Api status dashboard
- RabittMQ status
- Datadog Alerts and Pagerduty Alerts

