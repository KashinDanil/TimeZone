<?php

namespace src\services;

use Exception;
use RuntimeException;


require_once 'autoload.php';
new Worker($argv);

/**
 * Class Worker
 * @package Engine\Classes
 */
class Worker {
    /**
     * Worker constructor.
     * @param array $argv
     *
     * @throws RuntimeException
     */
    public function __construct(array $argv = []) {
        $argv = $this->arguments($argv);

        if ($argv['command'] !== 'daemon') {
            throw new RuntimeException('Попытка запуска Worker не из достоверного источника!');
        }

        $className = $argv['module'] ?? '';
        $methodName = $argv['method'] ?? '';
        $constructData = $argv['construct'] ?? '';

        if (!isset($className) || !isset($methodName)) {
            throw new RuntimeException('Не переданы аргументы в Worker!');
        }

        if (!class_exists($className)) {
            throw new RuntimeException('Не найден модуль: '.$className);
        }
        if (!empty($constructData)) {
            $module = new $className($constructData);
        } else {
            $module = new $className();
        }

        $module->$methodName();
    }

    /**
     * @param array $argv
     * @return array
     */
    private function arguments(array $argv): array {
        $_argv = [];

        foreach($argv as $arg) {
            if (preg_match('/--([^=]+)=(.*)/', $arg, $reg)) {
                $_argv[$reg[1]] = $reg[2];
            } elseif (preg_match('/-([a-zA-Z0-9])/', $arg, $reg)) {
                $_argv[$reg[1]] = 'true';
            }
        }

        $constructData = $_argv;
        unset($constructData['module'], $constructData['method'], $constructData['command']);
        $_argv['construct'] = $constructData;

        return $_argv;
    }
}