# Команды

## Установка приложения

```
symfony new api.symfony --version="6.4.*"
```

## Установка пакета orm-pack

```
composer require symfony/orm-pack
```


## Запуск локального сервера

```
symfony serve
```

## Установка MakerBundle

```
composer require --dev symfony/maker-bundle
```

## Создание сущности

```
php bin/console make:entity
```

## Создание миграции

```
php bin/console make:migration
```

```
php bin/console doctrine:migrations:diff
```

## Выполнение миграции

```
php bin/console doctrine:migrations:migrate
php bin/console doctrine:migrations:migrate --env=test
```

Удаление таблиц

```sql
DROP SCHEMA public CASCADE;
CREATE SCHEMA public;
```

## Создание фикстур

Устанавливаем пакет orm-fixtures.

```
composer require --dev orm-fixtures
```

Создаем фикстуры BookCategoryFixtures.

```
php bin/console make:fixtures
```

Запускаем установку фикстур.

```
php bin/console doctrine:fixture:load --purge-with-truncate
```

## Установка линтеров

### PHP CS Fixer

```
composer require --dev friendsofphp/php-cs-fixer
```

Просмотр команд.

```
./vendor/bin/php-cs-fixer
```

Запускаем команду.

```
./vendor/bin/php-cs-fixer fix
```

### PHPStan

```
composer require --dev phpstan/phpstan
```

```
vendor/bin/phpstan analyse src
```

## Тестирование

Установим необходимые пакеты.

```
composer require --dev symfony/test-pack

symfony composer req phpunit --dev
```

Команда для запуска тестов.

```
php bin/phpunit
```