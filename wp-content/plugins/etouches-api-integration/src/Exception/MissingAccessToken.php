<?php

namespace AventriEventSync\Exception;

use Exception;

class MissingAccessToken extends Exception
{
    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
