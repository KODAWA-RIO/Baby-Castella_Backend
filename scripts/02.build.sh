#!/bin/bash

docker build --target web-dev -t laravel:web ./Docker && docker build --target app-dev -t laravel:app ./Docker && docker build --target db-dev -t laravel:db ./Docker && docker build --target admin -t laravel:admin ./Docker \
&& mkdir -p ./src/vendor