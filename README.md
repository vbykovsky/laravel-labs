Доделать:
контроллер Comments
посмотреть как работает approve comments

composer install
npm ci

Создать копию .env.example и переименовать в .env

Создать учетку дял отправки почты и заполнить в .env:
MAIL_HOST=__FILL__
MAIL_USERNAME=__FILL__
MAIL_PASSWORD=__FILL__
MAIL_FROM_ADDRESS="__FILL__"
MAIL_TO=__FILL__

Создать приложения для пушера и заполнить:
PUSHER_APP_ID=__FILL__
PUSHER_APP_KEY=__FILL__
PUSHER_APP_SECRET=__FILL__

Запустить миграции с сидами: php artisan migrate:refresh --seed
Запустить frontend dev сервер: npm run dev
Запуск сервера: php artisan serve --port=8000
Запуск jobs процесса: php artisan queue:work