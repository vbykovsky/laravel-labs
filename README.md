composer install
npm ci

Поиенять почту в env файле:
MAIL_TO=

Запустить миграции с сидами: php artisan migrate:refresh --seed
Запуск сервера: php artisan serve --port=8000
Запустить frontend dev сервер: npm run dev
Запуск jobs процесса: php artisan queue:work

Юзеры для логина:
role=READER:
- user/123456

role=moderator:
- moderator/123456