# clinic/collection

Лёгкая обёртка над массивами для Pop-it MVC. Предоставляет fluent-методы (`map`, `filter`, `groupBy`, `sum`, `avg`, `sortBy`, `pluck` и т.д.) и глобальный хелпер `collection()`.

## Установка

```bash
composer require clinic/collection
```

При разработке локально можно подключить репозиторий через блок `repositories` и тип `path`.

## Использование

```php
use function Clinic\Collection\collection;

$doctors = collection($users)
    ->filter(fn ($user) => $user['role'] === 'Врач')
    ->sortBy('name')
    ->pluck('name')
    ->all();
```


