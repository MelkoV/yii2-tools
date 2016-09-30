<?php

namespace melkov\components\exceptions;

use yii\base\Exception;

class ApiException extends Exception
{
    protected $error;

    public function __construct($error, $code = null)
    {
        $this->code = $code;
        $this->error = $error;
    }

    public function getError()
    {
        return $this->error;
    }
}