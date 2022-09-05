<?php

namespace src\responses;

abstract class Response {
    const SUCCESS_RESPONSE_CODE = 200;

    /**
     * @var int Код ответа сервера
     */
    protected int $response_code;
    /**
     * @var string Комментарий к ответу
     */
    protected string $message;
    /**
     * @var array Ответ сервера
     */
    protected array $result = [];

    public function endSession() {
        $answer['success'] = $this->response_code === self::SUCCESS_RESPONSE_CODE;
        $answer['message'] = $this->message;
        if (!empty($this->result)) {
            $answer['result'] = $this->result;
        }

        die(json_encode($answer, JSON_UNESCAPED_UNICODE));
    }
}