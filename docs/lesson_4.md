# Получение книг по категории

## Обновление сущности Book

```
php bin/console make:entity
```

Запускаем команду создания миграции.

```
php bin/console make:migration
```

Выполняем миграции.

```
php bin/console doctrine:migrations:migrate
```

Запускаем установку фикстур.

```
php bin/console doctrine:fixture:load --purge-with-truncate
```

## Создание моделей для сущности Book

Создаем модели BookListResponse и BookListItem.

## Создание сервиса BookService

## Создание контроллера BookController