<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\DataTransformer\MoneyToLocalizedStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NumberType
 * My Number type with divisor option like MoneyType
 * @package App\Form\Type
 */
class NumberType extends \Symfony\Component\Form\Extension\Core\Type\NumberType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new MoneyToLocalizedStringTransformer(
            $options['scale'],
            $options['grouping'],
            $options['rounding_mode'],
            $options['divisor']
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            // default scale is locale specific (usually around 3)
            'scale' => null,
            'grouping' => false,
            'divisor' => 1,
            'rounding_mode' => MoneyToLocalizedStringTransformer::ROUND_HALF_UP,
            'compound' => false,
        ));

        $resolver->setAllowedValues('rounding_mode', array(
            MoneyToLocalizedStringTransformer::ROUND_FLOOR,
            MoneyToLocalizedStringTransformer::ROUND_DOWN,
            MoneyToLocalizedStringTransformer::ROUND_HALF_DOWN,
            MoneyToLocalizedStringTransformer::ROUND_HALF_EVEN,
            MoneyToLocalizedStringTransformer::ROUND_HALF_UP,
            MoneyToLocalizedStringTransformer::ROUND_UP,
            MoneyToLocalizedStringTransformer::ROUND_CEILING,
        ));

        $resolver->setAllowedTypes('scale', array('null', 'int'));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'number';
    }
}
