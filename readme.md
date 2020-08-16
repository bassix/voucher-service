# Voucher service

This repository holds the source of a **voucher service**.

For correct working inside the project note following dependencies:

- Docker
- Docker Compose
- PHP 7.4
- Symfony 5 Flex
- Doctrine
- PHPUnit

**Note:** For more detailed dependencies take a look into the configurations:

* Docker Compose config: `docker-compose.yml`
* Application Docker config: `app/Dockerfile`
* Symfony application config: `app/composer.json`

To get involved into the development of this project you need to get a local copy of this repository:

```bash
git clone git@github.com:bassix/voucher-service.git
cd voucher-service
```

_**Note:** This project is basing on the [GitFlow](http://nvie.com/posts/a-successful-git-branching-model/) branching model and workflow._

Now generate an environment specific configuration specially for local development:

```bash
./env.sh
```

In the next step we need to install all dependencies:

```bash
composer install
```

## Voucher application

The main part of this service is the Symfony application it self. This is located inside the `app/` directory. To run the following commands, change to this location:

```bash
cd app
```

### Doctrine

By default, the database schema will be created by migration (or database fixtures located at `mariadb/fixtures/voucher.sql`).

Alternative the Doctrine commands can be used to start with a empty database:

```bash
bin/console doctrine:database:create
bin/console make:migration
bin/console doctrine:migrations:migrate
```

**Note:** if something went horribly wrong, you can start from the ground up by recreating the whole database (all existing migrations should be deleted):

```bash
bin/console doctrine:database:drop --force
```

### Code quality tools

Run [PHPUnit](https://phpunit.de/) tests:

```bash
./bin/phpunit
```

Run [phpstan](https://github.com/phpstan/phpstan) to make statical analyse of the code. (Level from 0 to 7, where 0 is the most loose, 7 is the strongest. 0 is default):

```bash
vendor/phpstan/phpstan/phpstan analyse ./src --level 7
```

Run [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) to fix errors in code (use `--dry-run` option only to see errors):

```bash
vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix ./src
```

Documentation and constructor with more detailed information could be found at [https://mlocati.github.io/php-cs-fixer-configurator](https://mlocati.github.io/php-cs-fixer-configurator).

## Service application creation

This service is based on Symfony Flex. The following steps were performed to create the basic project and application structure.

### Create new Symfony application

Create a new project from the Symfony Flex recipe:

```bash
composer create-project symfony/skeleton app
```

Install Doctrine support via the orm Symfony pack, as well as the MakerBundle, which will help generate some code:

```bash
composer require symfony/orm-pack
```

After the entities were created the initial schema migration was generated:

```bash
docker exec --user www-data app bin/console doctrine:migrations:diff
```

### Install code quality tools

The PHPUnit Testing Framework:

```bash
composer require --dev symfony/phpunit-bridge
```

PHPStan Symfony Framework extensions and rules:

```bash
composer require --dev phpstan/phpstan-symfony
```

PHPStan Symfony Framework extensions and rules:

```bash
composer require --dev friendsofphp/php-cs-fixer
```
