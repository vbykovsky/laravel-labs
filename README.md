composer install
npm ci

Поменять почту в env файле:
MAIL_TO=

Запустить миграции с сидами:
`php artisan migrate:refresh --seed`
Запуск сервера:
`php artisan serve --port=8000`
Запустить frontend dev сервер:
`npm run dev`
Запуск jobs процесса:
`php artisan queue:work`
Запуск schedule процесса:
`php artisan schedule:run`

Юзеры для логина:
role=READER:
- user@mail.ru/123456

role=moderator:
- moderator@mail.ru/123456


API CURLs:
- signin:
    curl --location 'http://localhost:8000/api/signin' \
    --header 'Accept: application/json' \
    --form 'email="moderator@mail.ru"' \
    --form 'password="123456"'
- get articles:
    curl --location 'http://localhost:8000/api/article' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer BEARER'
- get comments:
    curl --location 'http://localhost:8000/api/comments' \
    --header 'Accept: application/json' \
    --header 'Authorization: Bearer BEARER'