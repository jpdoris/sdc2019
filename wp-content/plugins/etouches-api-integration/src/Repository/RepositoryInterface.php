<?php

namespace AventriEventSync\Repository;

use AventriEventSync\Model\ModelInterface;

interface RepositoryInterface
{
    /**
     * @return ModelInterface[]
     */
    public function find_all();
}
