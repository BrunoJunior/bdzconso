<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserApi;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('email', EmailType::class)
            ->add('apiActivated', ChoiceType::class, [
                'choices'  => [
                    'Yes' => true,
                    'No' => false,
                ]
            ])
        ;
        $hOptions = new ArrayCollection($options);
        if ($hOptions->get('edit') === true) {
            $builder
                ->add('password', RepeatedType::class, array(
                    'mapped' => false,
                    'type' => PasswordType::class,
                    'required' => false,
                    'first_options'  => array('label' => 'Password'),
                    'second_options' => array('label' => 'Retype password'),
                ));
        } else {
            $builder
                ->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options'  => array('label' => 'Password'),
                    'second_options' => array('label' => 'Retype password'),
                ));
        }
        if ($hOptions->get('admin') === true) {
            $builder
                ->add('roles', ChoiceType::class, [
                    'choices' => [
                        'Administrator' => 'ROLE_ADMIN',
                        'User' => 'ROLE_USER'
                    ],
                    'multiple' => true,
                    'expanded' => true
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'admin' => false,
            'edit' => false
        ]);
    }
}
