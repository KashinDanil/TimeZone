<?php

namespace src\services;

class Lock {
    /**
     * @var false|resource
     */
    private $fp;
    private string $filename;

    public function __construct(string $filename) {
        $this->fp = fopen($filename, "w+");
        $this->filename = $filename;

    }

    /**
     * Производим эксклюзивную блокировку файла
     * @return bool
     */
    public function lock(): bool {
        if ($this->fp) {
            if (flock($this->fp, LOCK_EX)) {// выполняем эксклюзивную блокировку
                fwrite($this->fp, date("Y-m-d H:i:s"));
                return true;
            }
        }

        return false;
    }

    public function __destruct() {
        if ($this->fp) {
            flock($this->fp, LOCK_UN);// снимаем блокировку
            fclose($this->fp);
            unlink($this->filename);//Удаляем файл для избежания конфликтов юзеров
        }
    }
}