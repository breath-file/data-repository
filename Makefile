.PHONY: build

default:
	@docker run -v $(shell pwd):/project -w /project php:7-cli-alpine php src/metrics.php

build:
	@docker build -t weather-exporter .
