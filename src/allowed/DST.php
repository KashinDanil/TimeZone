<?php

namespace src\allowed;

use src\services\UpdateDst;

class DST {
    public function updateAll(): array {
        /**
         * Запускаем параллельно worker и направляем вывод в никуда что бы не ждать завершения
         */
        $query = "php {$_SERVER['DOCUMENT_ROOT']}/src/services/Worker.php --command=daemon --module=src\\\\services\\\\UpdateDst --method=all >/dev/null &";
        exec($query);

        return ["info" => "Задача поставлена"];
    }
}