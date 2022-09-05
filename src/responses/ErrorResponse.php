<?php

namespace src\responses;

class ErrorResponse extends Response {
    public function __construct(string $message) {
        header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error',
            true, 500);
        $this->response_code = 500;
        $this->message = $message;
    }
}