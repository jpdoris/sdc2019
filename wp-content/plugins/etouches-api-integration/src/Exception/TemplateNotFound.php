<?php

namespace AventriEventSync\Exception;

use AventriEventSync\Aventri;
use Exception;

class TemplateNotFound extends Exception
{
    /**
     * @param string $template_name
     */
    public function __construct($template_name)
    {
        parent::__construct(sprintf(
            '%s cannot be found in the %s plugin',
            $template_name,
            Aventri::NAME
        ));
    }
}
