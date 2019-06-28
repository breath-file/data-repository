.PHONY: build

default:
	@docker run -v $(shell pwd):/project -w /project php:7-cli-alpine php public/index.php

build:
	@docker build -t weather-exporter .

weather:
	@docker-compose run --rm fpm php public/index.php OpenWeatherMap

breezometer:
	@docker-compose run --rm fpm php public/index.php Breezometer

task:
	@docker-compose run --rm fpm php public/index.php TaskRunner
