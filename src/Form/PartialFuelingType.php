<?php

namespace App\Form;

use App\Entity\PartialFueling;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartialFuelingType extends AbstractFuelingType
{
    /**
     * For partial fueling entity
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PartialFueling::class,
        ]);
    }
}
