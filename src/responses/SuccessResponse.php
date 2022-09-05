<?php

namespace src\responses;

class SuccessResponse extends Response {
    public function __construct(array $result) {
        $this->response_code = 200;
        $this->message = "Запрос выполнен успешно";
        $this->result = $result;
    }
}