# Goodlord PHP Interview Test

# Requirements

- PHP 8.2
- Composer 2

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

- `bin/console app:affordability-check ./files/bank_statement.csv ./files/properties.csv`

# Running the tests

- `./vendor/bin/simple-phpunit`
