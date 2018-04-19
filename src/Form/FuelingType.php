<?php

namespace App\Form;

use App\Entity\Fueling;
use App\Entity\FuelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
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
            ->add('date', DateType::class, [
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker']
            ])
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
                'choice_label' => 'name',
                'required' => false
            ])
        ;
        $builder->get('volume')->addModelTransformer(static::getCallbackTransformerRatio(1000));
        $builder->get('traveledDistance')->addModelTransformer(static::getCallbackTransformerRatio(10));
        $builder->get('showedConsumption')->addModelTransformer(static::getCallbackTransformerRatio(10));
    }

    /**
     * Ratio transformer
     * @param $ratio
     * @return CallbackTransformer
     */
    private static function getCallbackTransformerRatio(int $ratio): CallbackTransformer {
        return new CallbackTransformer(
            function ($value) use($ratio) {
                // divide the value by the ratio
                return $value / $ratio;
            },
            function ($value) use($ratio) {
                // multiply the value by the ratio
                return (int) ($value * $ratio);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fueling::class,
        ]);
    }
}
