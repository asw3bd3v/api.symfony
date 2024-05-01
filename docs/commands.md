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

## Выполнение миграции

```
php bin/console doctrine:migrations:migrate
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