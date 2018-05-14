<?php

namespace App\Form;

use App\Entity\Fueling;

class FuelingType extends AbstractFuelingType
{
    /**
     * The entity classname
     * @return string
     */
    protected function getEntityClass()
    {
        return Fueling::class;
    }
}
