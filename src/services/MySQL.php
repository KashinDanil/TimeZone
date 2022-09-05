<?php

namespace src\services;

use mysqli;
use mysqli_result;
use RuntimeException;

class MySQL extends Singleton {
    protected static ?MySQL $_instance;

    public mysqli $mysqli;

    private ?string $hostname, $username, $password, $database, $port, $socket;

    protected function __construct() {
        require 'config/db.php';

        /** @var ?string $hostname Задаётся в файле config/db.php */
        $this->hostname = $hostname;
        /** @var ?string $username Задаётся в файле config/db.php */
        $this->username = $username;
        /** @var ?string $password Задаётся в файле config/db.php */
        $this->password = $password;
        /** @var ?string $database Задаётся в файле config/db.php */
        $this->database = $database;
        /** @var ?string $port Задаётся в файле config/db.php */
        $this->port = $port;
        /** @var ?string $socket Задаётся в файле config/db.php */
        $this->socket = $socket;

        $this->connectDB();
    }

    /**
     * Установка соединения с БД
     */
    private function connectDB(): void {
        $this->mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database, $this->port, $this->socket);
        if ($this->mysqli->connect_errno) {
            throw new RuntimeException('Не удалось подключиться к базе данных: '.$this->mysqli->connect_error);
        }

        $this->mysqli->set_charset('utf8');
    }

    /**
     * Разрыв соединения с БД
     */
    public function disconnectDB(): void {
        $this->mysqli->close();//Если вызывают этот метод, значит соединение 100% установленно

        unset($this->mysqli);
        self::$_instance = null;
    }

    /**
     * @param $query
     * Просто сокращение, для более удобного обращения
     * @return bool|mysqli_result
     */
    public function query($query) {
        return $this->mysqli->query($query);
    }
}