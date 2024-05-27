# Регистрация и аутентификация

Устанавливаем пакет lexik/jwt-authentication-bundle.

```
composer require lexik/jwt-authentication-bundle
```

## Генерируем ключи

Если следующие команды, завершаются с ошибкой

```
php bin/console lexik:jwt:generate-keypair
php bin/console lexik:jwt:generate-keypair
```

то можно из консоли git

```
mkdir -p config/jwt
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

или

```
openssl genrsa -out config/jwt/private.pem
openssl rsa -in config/jwt/private.pem -pubout > config/jwt/public.pem
```