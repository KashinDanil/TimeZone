<?php

namespace src\services;

class UpdateDst {
    /**
     * @var string Уникальный ключ для API timezonedb.com.
     * Выдается в ЛК, пункт API Key.
     */
    private string $key;
    /**
     * @var mixed|MySQL
     */
    private $mysql;

    public function __construct() {
        require 'config/timezonedb.com.php';
        /** @var string $key Задаётся в файле config/timezonedb.com.php
         */
        $this->key = $key;
        $this->mysql = MySQL::getInstance();
    }

    public function all($logFilename="/tmp/UpdateAllCities.lock") {
        $lock = new Lock($logFilename);

        if ($lock->lock()) {
            $queryString = 'SELECT `id`, `latitude`, `longitude` FROM `city`;';
            $res = $this->mysql->query($queryString);
            if ($res && $res->num_rows > 0) {
                while($row = $res->fetch_assoc()) {
                    $this->one($row["id"], $row["latitude"], $row["longitude"]);
                }
            }
        }
    }

    /**
     * @param string $city_id
     * @param float $latitude
     * @param float $longitude
     *
     * В результате узнаем удалось ли обновить значение
     * @return bool
     */
    public function one(string $city_id, float $latitude, float $longitude): bool {
        $request = new Request(Request::GET,
            'http://api.timezonedb.com/v2.1/get-time-zone',
            [
                'key' => $this->key,
                'by' => 'position',
                'format' => 'json',
                'lat' => $latitude,
                'lng' => $longitude,
            ]
        );
        $result = $request->call();

        $result = json_decode($result, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;//Получили поломанный json, не можем работать дальше
        }

        $gmtOffset = (int)$result["gmtOffset"];
        $dst = (int)$result["dst"];
        $zoneStart = "null";
        if (is_int($result["zoneStart"]) && $dst == 1) {
            $zoneStart = date("Y-m-d H:i:s", $result["zoneStart"]);
        }
        $zoneEnd = "null";
        if (is_int($result["zoneEnd"]) && $dst == 1) {
            $zoneEnd = date("Y-m-d H:i:s", $result["zoneEnd"]);
        }

        $query = "INSERT INTO `dst` (`city_id`, `gmtOffset`, `dst`, `zoneStart`, `zoneEnd`) 
        VALUES ('$city_id', $gmtOffset, $dst, '$zoneStart', '$zoneEnd') 
        ON DUPLICATE KEY UPDATE gmtOffset=$gmtOffset, dst=$dst, zoneStart='$zoneStart', zoneEnd='$zoneEnd';";

        return $this->mysql->query($query);
    }
}