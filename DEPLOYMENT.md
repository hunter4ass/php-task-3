# Развёртывание проекта на Linux (wsr.ru и другие хостинги)

## Пошаговая инструкция для wsr.ru

### Шаг 1. Подготовка проекта локально

1. Убедитесь, что переименован файл `core/Src/view.php` → `core/Src/View.php` (уже сделано в репозитории).

2. Для хостингов без поддержки symlink откройте `composer.json` и в `repositories` замените:
   ```json
   "options": {"symlink": true}
   ```
   на:
   ```json
   "options": {"symlink": false}
   ```

3. Установите зависимости (если ещё не установлены):
   ```bash
   composer install
   ```

4. Создайте папку для загрузок локально: `public/uploads/appointments` — её нужно загрузить по FTP вместе с проектом.

### Шаг 2. Загрузка на сервер

1. Через **FTP/SFTP** (FileZilla, WinSCP и т.п.) загрузите в корень сайта (обычно `public_html` или `www`):

   - `.htaccess` (в корне проекта)
   - папку `public/` (с `index.php`, `.htaccess`, `css/` и др.)
   - папки `app/`, `core/`, `config/`, `database/`, `packages/`, `routes/`, `views/`
   - папку `vendor/` (после `composer install`)
   - файл `composer.json`
   - файл `composer.lock`

2. Структура на сервере должна быть:
   ```
   public_html/
   ├── .htaccess
   ├── public/
   │   ├── .htaccess
   │   ├── index.php
   │   └── ...
   ├── app/
   ├── core/
   ├── config/
   ├── vendor/
   └── ...
   ```

3. Корневой `.htaccess` перенаправляет все запросы в `public/`. Убедитесь, что на хостинге включён **mod_rewrite**.

### Шаг 3. База данных

1. В панели хостинга создайте базу MySQL и пользователя.

2. Отредактируйте `config/db.php`:
   ```php
   return [
       'driver' => 'mysql',
       'host' => 'localhost',        // часто localhost или имя сервера БД
       'database' => 'ваша_база',
       'username' => 'ваш_пользователь',
       'password' => 'ваш_пароль',
       'charset' => 'utf8',
       'collation' => 'utf8_unicode_ci',
       'prefix' => '',
   ];
   ```

3. Импортируйте схему:
   - Через phpMyAdmin: откройте `database/schema.sql`, скопируйте и выполните.
   - Или по SSH:
     ```bash
     mysql -u пользователь -p имя_базы < database/schema.sql
     ```

### Шаг 4. Права на папку загрузок

**Без прав администратора (обычный пользователь):** при загрузке через FTP права обычно достаточны. Если загрузки не работают — в панели хостинга откройте файловый менеджер, найдите `public/uploads/appointments` и установите права **755** или **775** через контекстное меню (часто «Права доступа» / «Change permissions»).

**При доступе по SSH** (если он есть и вы владелец папки):
```bash
chmod 775 public/uploads/appointments
```

### Шаг 5. Проверка

Откройте сайт в браузере. Если появится ошибка:

- **500 Internal Server Error** — проверьте логи сервера и `.htaccess`.
- **Class not found** — убедитесь, что `View.php` с заглавной V и `vendor/` загружены.
- **Ошибка подключения к БД** — проверьте `config/db.php` и доступность MySQL.
- **mod_rewrite не работает** — уточните в поддержке хостинга, как включить ЧПУ/rewrite.

---

## Переменные окружения (опционально)

Если хостинг поддерживает переменные окружения, можно не трогать `config/db.php` и задать:

- `DB_HOST`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`
- `DB_CHARSET` (по умолчанию `utf8`)
- `DB_COLLATION` (по умолчанию `utf8_unicode_ci`)

---

## Развёртывание по SSH (если хостинг даёт доступ)

На shared-хостинге SSH часто недоступен. Если есть:

```bash
cd ~/public_html   # или путь к корню сайта

composer install   # устанавливает зависимости в домашней папке — root не нужен

mkdir -p public/uploads/appointments
chmod 775 public/uploads/appointments   # только для своих файлов — root не нужен

# Импорт БД — если есть доступ к mysql в консоли
mysql -u user -p database_name < database/schema.sql
```

Без SSH: `composer install` выполните на своём компьютере и загрузите папку `vendor/` по FTP. Схему БД импортируйте через phpMyAdmin.

---

## Дополнительно: nginx

Если вместо Apache используется nginx, добавьте в конфиг сайта:

```nginx
location / {
    try_files $uri $uri/ /public/index.php?$query_string;
}
```
