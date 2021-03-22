<?php

namespace App\Exceptions;


class ZetaException extends \Exception
{
    protected $code;
    protected $message;

    /**
     * ZetaException constructor.
     * @param $code
     * @param $message
     */
    public function __construct($code, $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

}