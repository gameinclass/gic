#!/usr/bin/env bash

# Tenta corrigir os erros de estilo de código.
vendor/bin/phpcbf --standard=PSR2 app
vendor/bin/phpcbf --standard=PSR2 tests
# Verifica os erros de estilo de código.
vendor/bin/phpcs --standard=PSR2 --extensions=php app
vendor/bin/phpcs --standard=PSR2 --extensions=php tests
# Executa o teste unitário
vendor/bin/phpunit --coverage-text --colors=never