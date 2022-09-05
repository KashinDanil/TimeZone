# TimeZone
API для преобразования временной метки по часовому поясу городов.

###Установка:
1. Добавление всех зависимостей указанных в файле ```composer.json```;
2. Создание каталога config в корне проекта и добавление в него двух файлов:
- db.php с указанием данных для подключения к БД.
```php
<?php
$hostname = "...";
$username = "...";
$password = "...";
$database = "...";
$port = "...";
$socket "= ...";
```
- timezonedb.com.php с указанием ключа для обращения к API timezonedb.com (Ключ можно получить на странице профиля). Это необходимо для сохранения актуальности данных.
```php
<?php
$key = "...";
```
3. Выполнить SQL из файлов в каталоге ```migrations```.
4. Направить все обращения к сервису на файл ```index.php```
5. Для корректной работы тестов, после поднятия работающего сервера нужно заменить в файле ```tests/tests.php``` значение переменной ```$host``` на актуальный домен.

###Методы:
1. Получения локального времени в городе по переданному идентификатору города и метке времени по UTC+0.
>Обращение: http://localhost/TimeZone/getLocalTime/

| Параметр | Тип | Обязательный | Значение |
|----------|-----|--------------|----------|
| cityId | String | Да | Идентификатор города списка городов (см. ```migrations/city.sql``` ) |
| greenwichTimestamp | Int |	Да | Временная метка по Гринвичу UTC+0 |

2. Обратное преобразование из локального времени и идентификатора города в метку времени по UTC+0.
>Обращение: http://localhost/TimeZone/getGreenwichTime/

| Параметр | Тип | Обязательный | Значение |
|----------|-----|--------------|----------|
| cityId | String | Да | Идентификатор города списка городов (см. ```migrations/city.sql``` ) |
| localTimestamp | Int |	Да | Временная метка по часовому поясу города с идентификатором cityId |

3. Внешний запуск процесса обновления данных.

> Обращение: http://localhost/DST/updateAll/

Для периодического запуска процесса обновления данных рекомендуется поставить какой-либо демон выполняющий следующую команду:
```php src/services/Worker.php --command=daemon --module=src\\services\\UpdateDst --method=all >/dev/null &```

###Тесты:
Тестирование выполняются по средствам обращения к самим API и ожидания от них соответствующего ответа.