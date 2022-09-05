<?php
/**
 * Не показывать варнинги и нотисы на проде
 */
ini_set('display_errors', 'Off');

require_once 'autoload.php';

use src\responses\ErrorResponse;
use src\responses\SuccessResponse;
use src\api\API;

header('Content-type: application/json');


$api = new API();

$httpMethodClass = "\\src\\methods\\".$_SERVER["REQUEST_METHOD"];
if (!class_exists($httpMethodClass)) {
    (new ErrorResponse('Метод запроса \''.$_SERVER["REQUEST_METHOD"].'\' не поддерживается'))->endSession();
}

/**
 * Определяем входящие данные
 */
$data = explode('/', $_SERVER['REQUEST_URI']);
if (count($data) < 2) {
    (new ErrorResponse('Не указан класс обращения'))->endSession();
}
$class = $data[1];
if (count($data) < 3) {
    (new ErrorResponse('Не указан метод обращения'))->endSession();
}
$method = $data[2];

$api->setClass($class);

$result = [];
try {
    $result = $api->call($method, new $httpMethodClass);
} catch(ReflectionException $e) {
    (new ErrorResponse($e->getMessage()))->endSession();
}

(new SuccessResponse($result))->endSession();