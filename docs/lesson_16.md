# Refresh-токены

Для реализации работы с refresh-токеном будем использовать готовый бандл JWTRefreshTokenBundle.

```
composer require gesdinet/jwt-refresh-token-bundle
```

При выполнении команды php bin/console doctrine:migrations:diff произошла ошибка

```
Duplicate definition of column 'id' on entity 'App\Entity\RefreshToken' in a field or discriminator column mapping.
```

пришлось временно убрать наследование от BaseRefreshToken и выполнить команду.

Теперь если регистрируем пользователя 

```
[POST] http://api.symfony/api/v1/auth/signUp
```

то в ответ приходит объект

```json
{
    "token": "...",
    "refresh_token": "..."
}
```

А если отправить запрос на http://api.symfony/api/v1/auth/refresh с refresh_token полученным ранее,
то в ответ придут новые token и refresh_token/

```json
{
    "token": "...",
    "refresh_token": "..."
}
```