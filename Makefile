.PHONY: build

default:
	@docker run -v $(shell pwd):/project -w /project php:7-cli-alpine php public/index.php

build:
	@docker build -t weather-exporter .
