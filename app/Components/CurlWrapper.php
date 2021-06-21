<?php

namespace App\Components;

class CurlWrapper
{
    private $headers = [];

    private $response;

    private $status;

    public function __construct()
    {
        $this->addHeader(['Content-Type: application/json']);
    }

    public function addHeader(array $headers)
    {
        $default = $this->headers;
        $this->headers = array_merge($default, $headers);
    }

    public function setApiAuth(string $auth_key)
    {
        $this->auth_key = $auth_key;
    }

    public function sendRequest($method, $url, $params = [])
    {
        try {
            $curl_object = $this->initialiseCurl($url, $params);

            $this->optByMethod($curl_object, $method);

            $this->response = curl_exec($curl_object);
            $this->status = curl_getinfo($curl_object, CURLINFO_HTTP_CODE);
            curl_close($curl_object);
        } catch (Exception $exception) {
            $this->response = $exception->getMessage();
        }
        return $this;
    }

    public function getHttpStatus()
    {
        return $this->status;
    }

    public function getResponse()
    {
        return json_decode($this->response, true);
    }

    public function initialiseCurl($url, $params)
    {
        $curl_object = curl_init();
        curl_setopt($curl_object, CURLOPT_URL, $url);
        curl_setopt($curl_object, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl_object, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($curl_object, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl_object, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_object, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl_object, CURLOPT_HEADER, 0);
        curl_setopt($curl_object, CURLOPT_USERPWD, $this->auth_key);
        return $curl_object;
    }

    public function optByMethod($curl_object, $method): void
    {
        switch ($method) {
            case 'POST':
                curl_setopt($curl_object, CURLOPT_POST, 1);
                break;
            case 'PUT':
            case 'DELETE':
                curl_setopt($curl_object, CURLOPT_CUSTOMREQUEST, $method);
                break;
            default:
                curl_setopt($curl_object, CURLOPT_POST, 0);
                break;
        }
    }
}
