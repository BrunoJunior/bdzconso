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
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFuelingType extends AbstractType
{
    const OPTION_CONSUMPTION_SHOWED = 'consumption_showed';

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
                'scale' => 2,
                'html5' => false,
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
            ->add('additivedFuel', ChoiceType::class, [
                'choices'  => array(
                    'No' => false,
                    'Yes' => true,
                ),
            ])
            ->add('traveledDistance', NumberType::class, [
                'divisor' => 10,
                'scale' => 1,
                'html5' => false,
            ]);
        $hOptions = new ArrayCollection($options);
        if ($hOptions->get(static::OPTION_CONSUMPTION_SHOWED) === true) {
            $builder->add('showedConsumption', NumberType::class, [
                'divisor' => 10,
                'scale' => 1
            ]);
        }
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->getEntityClass(),
            static::OPTION_CONSUMPTION_SHOWED => true
        ]);
    }

    /**
     * The entity classname
     * @return string
     */
    abstract protected function getEntityClass();
}
