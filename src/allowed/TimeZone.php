<?php

namespace src\allowed;

use DateInterval;
use DateTime;
use Exception;
use src\responses\ErrorResponse;
use src\services\MySQL;

class TimeZone {
    const DST_DIFF = 1;

    private function getDst(string $cityId): ?array {
        $mysql = MySQL::getInstance();
        $queryString = "SELECT * FROM `dst` WHERE `city_id` like '".
            $mysql->mysqli->real_escape_string($cityId)."';";
        $res = $mysql->query($queryString);
        if (!$res || $res->num_rows === 0) {
            (new ErrorResponse('Указанный город не найден'))->endSession();
        }
        return $res->fetch_assoc();
    }

    /**
     * @throws Exception
     */
    private function getModifiedTime(int $current, int $dst, string $start, string $end): DateTime {
        $currentTime = new DateTime();
        $currentTime->setTimestamp($current);//получаем локальное время
        if ($dst) {//и проверяем используется ли летнее время
            $dstStart = new DateTime($start);
            $dstEnd = new DateTime($end);

            //Если сейчас летнее время, то добавляем/убавляем self::DST_DIFF час
            if ($currentTime > $dstStart && $currentTime < $dstEnd) {
                $oneHour = new DateInterval('PT'.self::DST_DIFF.'H');
                $currentTime->add($oneHour);
            }
        }

        return $currentTime;
    }

    /**
     * @throws Exception
     */
    public function getLocalTime(string $cityId, $greenwichTimestamp): array {
        if (!is_numeric($greenwichTimestamp)) {
            (new ErrorResponse("Параметр 'greenwichTimestamp' должен быть числом"))->endSession();
        }
        $greenwichTimestamp = (int)$greenwichTimestamp;

        $data = $this->getDst($cityId);

        //Добавляем часовой пояс
        $greenwichTimestamp += $data["gmtOffset"];

        $currentTime = $this->getModifiedTime($greenwichTimestamp, (int)$data["dst"], $data["zoneStart"],
            $data["zoneEnd"]);


        return ["localTimestamp" => $currentTime->getTimestamp()];
    }

    /**
     * @throws Exception
     */
    public function getGreenwichTime(string $cityId, $localTimestamp): array {
        if (!is_numeric($localTimestamp)) {
            (new ErrorResponse("Параметр 'localTimestamp' должен быть числом"))->endSession();
        }
        $localTimestamp = (int)$localTimestamp;
        $data = $this->getDst($cityId);

        //Вычитаем часовой пояс
        $localTimestamp -= $data["gmtOffset"];

        $currentTime = $this->getModifiedTime($localTimestamp, (int)$data["dst"], $data["zoneStart"],
            $data["zoneEnd"]);


        return ["greenwichTimestamp" => $currentTime->getTimestamp()];
    }
}