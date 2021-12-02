<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // minimum datas to create a user
        $builder
            ->add('email')
            ->add('roles', null, [
                'empty_data' => []
            ])
            ->add('password')
            ->add('firstname')
            ->add('lastname')

            // add some fields when editing a user
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $user = $event->getData();

                if ($user->getId() != null) {
                    $form
                        ->add('phoneNumber', null, [
                            'required' => false,
                        ])
                        ->add('avatar', null, [
                            'required' => false,
                        ])
                        ->add('address', null, [
                            'required' => false,
                        ])
                        ->add('zipcode', null, [
                            'required' => false,
                        ])
                        ->add('city', null, [
                            'required' => false,
                        ])
                        ->add('country', null, [
                            'required' => false,
                        ])
                        ->add('latitude', null, [
                            'required' => false,
                        ])
                        ->add('longitude', null, [
                            'required' => false,
                        ]);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
