<?php

namespace AventriEventSync\Exception;

use Exception;

class RequestMethodNotImplemented extends Exception
{
    /**
     * @param string $request_method
     */
    public function __construct($request_method)
    {
        parent::__construct(sprintf(
            'Request method %s is not implemented yet',
            $request_method
        ));
    }
}
