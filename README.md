# "Личная библеотека книг"

### Для запуска приложения выполните следующие команды:
 - `make build` (для построения приложения)
 - `make up` (для запуска приложения)
 - `make user` (для создания пользователя)

***
### Используйте команды "make" для удобства:
 - make up | для поднятия окружения, миграции так же запускаются
 - make down | для удаления окружения
 - make stop | для остановки
 - make restart | для рестарта
 - make build | для билда
 - make rm | для удаления
 - make php c="{command}" | для команд php-fpm
 - make console c="{command}" | для консольных команд фреймворка из-под контейнера php-fpm
 - make user | для создания пользователя (регистрация через консоль)
 - make testDB | для подготовки ткстовой БД
 - make testing | для запуска тестов
 - make fixtures | для ручной загрузки фикстур
***

Если появились проблемы с правами на запись директорию кеша и логов, то выполните команду - 
```make php c="chown www-data:www-data -R ./var/cache/ ./var/log/"```
