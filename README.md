# Goodlord PHP Interview Test

# Requirements

- PHP 8.2
- Symfony 6.4
- Composer 2
- PHPUnit 9.5
- Docker 3.4
- CS fixer 3.65

# Running the command

```bash
# Start docker containers
$ docker-compose up -d

# Install dependencies
$ composer install
```

# Check database
```bash
# I have added a DB management tool such as Adminer in the Docker build
$ http://localhost:8090/
```

## CS fixer

```bash
$ ./vendor/bin/php-cs-fixer fix src
```

## Command run

```bash
$ bin/console app:affordability-check ./files/bank_statement.csv ./files/properties.csv
```

# Running the tests

- `./vendor/bin/simple-phpunit`
