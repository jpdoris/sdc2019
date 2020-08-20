<?php

namespace AventriEventSync\Exception;

use Exception;

class MissingArgument extends Exception
{
    /**
     * @param string $argument
     * @param string $class
     */
    public function __construct($argument, $class)
    {
        parent::__construct(sprintf(
            'Missing argument: %s for class %s',
            $argument,
            $class
        ));
    }
}
