<?php

namespace App\Form;

use App\Entity\Fueling;
use App\Form\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FuelingType extends AbstractFuelingType
{

    /**
     * Add specific fueling fields
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    protected function completeForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('traveledDistance', NumberType::class, [
            'divisor' => 10,
            'scale' => 1
            ])
            ->add('showedConsumption', NumberType::class, [
                'divisor' => 10,
                'scale' => 1
            ]);
    }

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
