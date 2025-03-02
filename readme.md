## Пиццафабрика

### Инит проекта

1. Быстрый путь:
    - Выполнить `task init`
    - Заполнить параметры для `app/phinx.php`:
   ```
   'name' => 'значение DB_DATABASE из .env',
   'user' => 'значение DB_USERNAME из .env',
   'pass' => 'значение DB_PASSWORD из .env',
   ```

2. Если в системе нет [task](https://taskfile.dev/):
    - `cp .env.example .env`
    - `docker compose up --build -d`
    - `docker compose exec php php composer install`
    - `cp app/phinx.example.php app/phinx.php`
    - Заполнить параметры для `app/phinx.php`:
   ```
   'name' => 'значение DB_DATABASE из .env',
   'user' => 'значение DB_USERNAME из .env',
   'pass' => 'значение DB_PASSWORD из .env',
   ```
    - `docker compose exec php vendor/bin/phinx migrate -e development`

Опционально: засидить моковыми заказами базу таблицу orders:  
`docker compose exec php vendor/bin/phinx seed:run -e development`

X-Auth-Key лежит в .env файле, прокидывается вместе с другими переменными в контейнер php  
Документация в формате openapi доступна по адресу [http://localhost:8080/swagger/index.html](http://localhost:8080/swagger/index.html)
---

### task команды
Список всех доступных task команд: [taskfile.yml](taskfile.yml)  
Если команда принимает аргумент, к примеру, {{.c}}, то общий вид будет такой: `task php c='composer update'`

Управление контейнерами  
`task up` = `docker compose up --build -d`  
`task down` = `docker compose down`  
`task restart` = выполнение task up, затем task down

Вспомогательные команды  
`dump-autoload` = `docker compose exec php composer dump-autoload`
