<?php

namespace App\Form;

use App\Entity\Fueling;
use App\Entity\FuelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FuelingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class)
            ->add('volume', NumberType::class, [
                'scale' => 2
            ])
            ->add('volumePrice', MoneyType::class, [
                'divisor' => 1000
            ])
            ->add('amount', MoneyType::class, [
                'divisor' => 100
            ])
            ->add('traveledDistance', NumberType::class, [
                'scale' => 1
            ])
            ->add('showedConsumption', NumberType::class, [
                'scale' => 1
            ])
            ->add('fuelType', EntityType::class, [
                'class' => FuelType::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fueling::class,
        ]);
    }
}
