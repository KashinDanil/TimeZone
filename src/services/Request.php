<?php

namespace src\services;

class Request {
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    /**
     * @var false|resource
     */
    private $curl;
    private string $method;
    private string $url;

    public static int $max_requests_per_second = 1;
    /**
     * @var mixed
     */
    public $info;

    /**
     * @param string $method
     * @param string $url
     * @param array|null $data
     * @param bool $default_options
     */
    public function __construct(string $method, string $url, ?array $data, bool $default_options = true) {
        $this->curl = curl_init();
        $this->method = $method;
        $this->url = $url;

        if ($data) {
            $this->setData($data);
        }

        //Установка параметров для вызова по умолчанию
        if ($default_options) {
            curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYSTATUS, 0);
        }
    }

    /**
     * @param $headers
     * Установка заголовков
     */
    public function setHeaders($headers) {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
    }

    /**
     * @param $data
     * Тело запроса
     */
    public function setData($data) {
        switch($this->method) {
            case self::POST:
                curl_setopt($this->curl, CURLOPT_POST, 1);
                if ($data) {
                    $query = json_encode($data);
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $query);
                }
                break;
            case self::PUT:
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($data) {
                    $query = json_encode($data);
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $query);
                }
                break;
            case self::DELETE:
                $this->url = sprintf('%s?%s', $this->url, $data);
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            default:
                if ($data) {
                    $data = http_build_query($data);
                    $this->url = sprintf('%s?%s', $this->url, $data);
                }
                break;
        }
    }

    /**
     * @param $option
     * @param $value
     */
    public function setOption($option, $value) {
        curl_setopt($this->curl, $option, $value);
    }

    /**
     * @return bool|string
     */
    public function call() {
        curl_setopt($this->curl, CURLOPT_URL, $this->url);

        if (is_int(self::$max_requests_per_second)) {
            /**
             * У сервисов существуют ограничения по количеству запросов в секунду.
             * У сервиса timezonedb.com ограничение на 1 запрос в секунду, по этому так медленно.
             */
            usleep(1000000/self::$max_requests_per_second);
        }
        $result = curl_exec($this->curl);
        $this->info = curl_getinfo($this->curl);

        curl_close($this->curl);

        return $result;
    }
}