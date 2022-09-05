<?php

namespace src\services;

use Exception;

class Singleton {
    private static array $instances = [];

    protected function __construct() { }

    /**
     * Клонирование и десериализация не разрешены для одиночек.
     */
    protected function __clone() { }

    /**
     * @throws Exception
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }

    public static function getInstance() {
        $subclass = static::class;
        if (!isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static();
        }
        return self::$instances[$subclass];
    }
}