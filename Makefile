.PHONY: build

default:
	@docker run -v $(shell pwd):/project -w /project php:7-cli-alpine php public/index.php

#build:
#	@docker build -t omeglast/weather-exporter:latest .

cron:
	@docker-compose run --rm cli cron
#	@docker-compose run --rm cli php public/index.php cron

