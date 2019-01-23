#!/usr/bin/env bash

vendor/bin/phpcbf --standard=PSR2 app
vendor/bin/phpcbf --standard=PSR2 tests

vendor/bin/phpcs --standard=PSR2 --extensions=php app
vendor/bin/phpcs --standard=PSR2 --extensions=php tests