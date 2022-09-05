<?php

namespace src\api;

use ReflectionException;
use ReflectionMethod;
use src\methods\HttpMethod;
use src\responses\ErrorResponse;

class API {
    protected object $class;

    public function setClass($class) {
        /**
         * Создаем объект класса.
         * Допускаем только вызов классов из пространства имен allowed
         */
        $className = '\\src\\allowed\\'.$class;
        if (!class_exists($className)) {
            (new ErrorResponse('Вызываемый класс не существует'))->endSession();
        }
        $this->class = new $className();
    }

    /**
     * @throws ReflectionException
     */
    public function call(string $method, HttpMethod $httpMethod) {
        /**
         * Формируем аргументы для метода
         */
        if (!method_exists($this->class, $method)) {
            (new ErrorResponse('Вызываемый метод не существует'))->endSession();
        }
        $reflection = new ReflectionMethod($this->class, $method);
        if (!$reflection->isPublic()) {
            (new ErrorResponse('Вызываемый метод не доступен'))->endSession();
        }

        $args = $httpMethod->getData($reflection);

        /**
         * Вызываем метод
         */
        return call_user_func_array([$this->class, $method], $args);
    }
}