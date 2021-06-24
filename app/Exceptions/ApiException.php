<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    private $data = [];

    protected $code;

    public function __construct($data, $message, $code)
    {
        parent::__construct($message);
        $this->data = $data;
        $this->code = $code;
    }
    public function getData()
    {
        return $this->data;
    }
}
