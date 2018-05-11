<?php

namespace App\Form;

use App\Entity\Fueling;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FuelingType extends AbstractFuelingType
{

    /**
     * For fueling class
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fueling::class,
        ]);
    }
}
