<?php

namespace App\Form;

use App\Entity\PartialFueling;

class PartialFuelingType extends AbstractFuelingType
{
    /**
     * The entity classname
     * @return string
     */
    protected function getEntityClass()
    {
        return PartialFueling::class;
    }
}
