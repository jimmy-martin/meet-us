<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // minimum datas to create a user
        $builder
            ->add('email', null, [
                'constraints' => [
                    new Email([
                        'mode' => 'html5',
                    ]),
                    new NotBlank(),
                ]
            ])
            ->add('roles', null, [
                'empty_data' => []
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
            ])
            ->add('firstname', null, [
                'constraints' => [
                    new NotBlank(), // TODO: se mettre d'accord sur les contraintes de validation des nom et prénom
                ]
            ])
            ->add('lastname', null, [
                'constraints' => [
                    new NotBlank(), // TODO: se mettre d'accord sur les contraintes de validation des nom et prénom
                ]
            ])

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
