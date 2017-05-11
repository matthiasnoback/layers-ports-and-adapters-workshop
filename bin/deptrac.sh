#!/usr/bin/env bash

docker-compose run --rm devtools deptrac analyze --formatter-graphviz-dump-image=/opt/var/dependency-graph.png
