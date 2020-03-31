COLOR_RESET   = \033[0m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m

## Composer install
composer:
	composer install --verbose

## Cache
cache-clear:
	@printf "${COLOR_COMMENT}Clearing cache:${COLOR_RESET}\n"
	@test -f bin/console && bin/console cache:clear --no-warmup || rm -rf var/cache/*


###########
## Database
###########
db-drop:
	bin/console doctrine:database:drop --if-exists --force --connection default

db-create:
	bin/console doctrine:database:create --connection default --if-not-exists

db-migrations:
	bin/console doctrine:migrations:migrate --em default --no-interaction

db-fixtures:
	bin/console doctrine:fixtures:load --append --em default --no-interaction


phpunit:
	bin/phpunit

build:
	composer db-drop db-create db-migrations cache-clear

install:
	composer db-drop db-create db-migrations db-fixtures cache-clear