<?php

require_once 'autoload.php';

use src\services\Request;

$host = "http://timezone.localhost";

/**
 * @throws Exception
 */
function customAssert($cond) {
    if (!$cond) {
        throw new Exception();
    }
}

/**
 * Нормальная работа API
 */
customAssert((new Request(Request::GET,
        $host.'/TimeZone/getGreenwichTime/',
        [
            'cityId' => 'eb56dea3-4cbe-44e7-acd1-0bc26dd8ab5b',
            'localTimestamp' => '1662346878'
        ]
    ))->call() == '{"success":true,"message":"Запрос выполнен успешно","result":{"greenwichTimestamp":1662336078}}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getLocalTime/',
        [
            'cityId' => 'eb56dea3-4cbe-44e7-acd1-0bc26dd8ab5b',
            'greenwichTimestamp' => '1662336078'
        ]
    ))->call() == '{"success":true,"message":"Запрос выполнен успешно","result":{"localTimestamp":1662346878}}');

customAssert((new Request(Request::GET,
        $host.'/DST/updateAll/', [
        ]))->call() == '{"success":true,"message":"Запрос выполнен успешно","result":{"info":"Задача поставлена"}}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getLocalTime/',
        [
            'cityId' => '16e88198-bcb5-4b09-8b59-22b76bd9d981',
            'greenwichTimestamp' => '1647169201'
        ]
    ))->call() == '{"success":true,"message":"Запрос выполнен успешно","result":{"localTimestamp":1647158401}}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getLocalTime/',
        [
            'cityId' => '16e88198-bcb5-4b09-8b59-22b76bd9d981',
            'greenwichTimestamp' => '1647165600'
        ]
    ))->call() == '{"success":true,"message":"Запрос выполнен успешно","result":{"localTimestamp":1647151200}}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getLocalTime/',
        [
            'cityId' => '16e88198-bcb5-4b09-8b59-22b76bd9d981',
            'greenwichTimestamp' => '1667725200'
        ]
    ))->call() == '{"success":true,"message":"Запрос выполнен успешно","result":{"localTimestamp":1667714400}}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getLocalTime/',
        [
            'cityId' => '16e88198-bcb5-4b09-8b59-22b76bd9d981',
            'greenwichTimestamp' => '1667728800'
        ]
    ))->call() == '{"success":true,"message":"Запрос выполнен успешно","result":{"localTimestamp":1667714400}}');


/**
 * Передаем пустые параметры
 */
customAssert((new Request(Request::GET,
        $host.'/TimeZone/getLocalTime/',
        [
        ]
    ))->call() == '{"success":false,"message":"Необходимо указать параметр \'cityId\'"}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getGreenwichTime/',
        [
        ]
    ))->call() == '{"success":false,"message":"Необходимо указать параметр \'cityId\'"}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getLocalTime/',
        [
            'localTimestamp' => '1662336078'
        ]
    ))->call() == '{"success":false,"message":"Необходимо указать параметр \'cityId\'"}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getGreenwichTime/',
        [
            'localTimestamp' => '1662336078'
        ]
    ))->call() == '{"success":false,"message":"Необходимо указать параметр \'cityId\'"}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getLocalTime/',
        [
            'cityId' => 'eb56dea3-4cbe-44e7-acd1-0bc26dd8ab5b',
        ]
    ))->call() == '{"success":false,"message":"Необходимо указать параметр \'greenwichTimestamp\'"}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getGreenwichTime/',
        [
            'cityId' => 'eb56dea3-4cbe-44e7-acd1-0bc26dd8ab5b',
        ]
    ))->call() == '{"success":false,"message":"Необходимо указать параметр \'localTimestamp\'"}');


/**
 * Ломаем параметры существующих методов
 */
customAssert((new Request(Request::GET,
        $host.'/TimeZone/getLocalTime/',
        [
            'cityId' => 'eb56dea3-4cbe-44e7-acd1-0bc26dd8ab5b',
            'greenwichTimestamp' => '1662336078aa'
        ]
    ))->call() == '{"success":false,"message":"Параметр \'greenwichTimestamp\' должен быть числом"}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getGreenwichTime/',
        [
            'cityId' => 'eb56dea3-4cbe-44e7-acd1-0bc26dd8ab5b',
            'localTimestamp' => '1a662346878'
        ]
    ))->call() == '{"success":false,"message":"Параметр \'localTimestamp\' должен быть числом"}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getLocalTime/',
        [
            'cityId' => 'eb56dea3-4cbe-44e7-acd1-0bc26dd8ab5baaaaaaaaaaaaaa',
            'greenwichTimestamp' => '1662336078aa'
        ]
    ))->call() == '{"success":false,"message":"Параметр \'greenwichTimestamp\' должен быть числом"}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getGreenwichTime/',
        [
            'cityId' => 'eb56dea3-4cbe-44e7-acd1-0bc26dd8ab5baaaaaaaaaaaaaa',
            'localTimestamp' => '1a662346878'
        ]
    ))->call() == '{"success":false,"message":"Параметр \'localTimestamp\' должен быть числом"}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getLocalTime/',
        [
            'cityId' => 'eb56dea3-4cbe-44e7-acd1-0bc26dd8ab5baaaaaaaaaaaaaa',
            'greenwichTimestamp' => '1662336078'
        ]
    ))->call() == '{"success":false,"message":"Указанный город не найден"}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getGreenwichTime/',
        [
            'cityId' => 'eb56dea3-4cbe-44e7-acd1-0bc26dd8ab5baaaaaaaaaaaaaa',
            'localTimestamp' => '1662346878'
        ]
    ))->call() == '{"success":false,"message":"Указанный город не найден"}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getLocalTime/',
        [
            'cityId' => 'eb56dea3-4cbe-44e7-acd1-0bc26dd8ab5b',
            'greenwichTimestamp' => '[]]'
        ]
    ))->call() == '{"success":false,"message":"Параметр \'greenwichTimestamp\' должен быть числом"}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/getGreenwichTime/',
        [
            'cityId' => 'eb56dea3-4cbe-44e7-acd1-0bc26dd8ab5b',
            'localTimestamp' => '[]]'
        ]
    ))->call() == '{"success":false,"message":"Параметр \'localTimestamp\' должен быть числом"}');


/**
 * Левые пути
 */
customAssert((new Request(Request::GET,
        $host.'/a/',
        []))->call() == '{"success":false,"message":"Вызываемый класс не существует"}');

customAssert((new Request(Request::GET,
        $host.'/TimeZone/asfd',
        []))->call() == '{"success":false,"message":"Вызываемый метод не существует"}');

customAssert((new Request(Request::GET,
        $host,
        []))->call() == '{"success":false,"message":"Не указан метод обращения"}');


/**
 * Недоступные методы
 */
customAssert((new Request(Request::GET,
        $host.'/TimeZone/getModifiedTime/',
        []))->call() == '{"success":false,"message":"Вызываемый метод не доступен"}');


