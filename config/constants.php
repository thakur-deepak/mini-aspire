<?php

return [
    'FILE_REGEX'     => '/^([a-z0-9_-]+\/)*+[a-z0-9_-]+\.[a-zA-Z]{3,4}$/',
    'NAME_REGEX'     => "/^[a-zA-Z@~`!@#$%^&*()_=+\\';:\/?>.<,-]*$/",
    'PASSWORD_REGEX' => '/^(?=.*?[A-Z])(?=.*?[0-9]).{8,}$/',
    'EMAIL_REGEX' => '/^[a-zA-Z0-9_-]+(?:\.[a-zA-Z0-9_-]+)*@[a-zA-Z0-9\.\_\-]{2,}\.[a-zA-Z0-9]{2,}$/',
    'DOUBLE_REGEX' => '/^\d+(\.\d{1,2})?$/',
    'DATE'           => 'Y-m-d',
    'DATE_TIME'      => 'Y-m-d H:i:s',
    'ERROR'          => [
        'VALIDATION' => 'Validation Error'
    ],
];
