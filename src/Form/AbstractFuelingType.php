<?php
/**
 * Created by PhpStorm.
 * User: bdesprez
 * Date: 10/05/18
 * Time: 00:46
 */

namespace App\Form;

use App\Entity\FuelType;
use App\Form\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractFuelingType extends AbstractType
{
    /**
     * Build form
     * @param FormBuilderInterface $builder
     * @param array $options
     */
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
                'divisor' => 1000,
                'scale' => 2
            ])
            ->add('volumePrice', MoneyType::class, [
                'divisor' => 1000,
                'scale' => 3
            ])
            ->add('amount', MoneyType::class, [
                'divisor' => 100
            ])
            ->add('fuelType', EntityType::class, [
                'class' => FuelType::class,
                'choice_label' => 'name',
                'required' => true
            ])
            ->add('additivedFuel', CheckboxType::class, [
                'required' => false
            ])
        ;
        $this->completeForm($builder, $options);
    }

    /**
     * To override
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    protected function completeForm(FormBuilderInterface $builder, array $options) {
        return;
    }
}