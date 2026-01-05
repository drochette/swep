DOCKER=docker compose exec -it php

.DEFAULT_GOAL := help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

start: ##start the project
	docker compose up -d

stop: ##Stop
	docker compose stop

fix-cs: ##Fix code style
	$(DOCKER) vendor/bin/php-cs-fixer fix

purge-fixtures: ##Fixtures
	$(DOCKER) bin/console doctrine:fixtures:load --no-interaction

db-migration: ##Db migrate
	$(DOCKER) bin/console doctrine:migrations:migrate --no-interaction
