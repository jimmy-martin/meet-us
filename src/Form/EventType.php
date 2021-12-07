<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // TODO voir contrainte de validation avec Front
        $builder
            ->add('title', null, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 10,
                        'max' => 150,
                    ])
                ],
            ])

            ->add('description', null, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 50,
                        'max' => 3000,
                    ])
                ],
            ])

            ->add('picture', null, [
                'required' => false,
                'empty_data' => 'event_placeholder.png',
            ])

            ->add('date', null, [
                'widget' => 'single_text',
                'constraints' => [
                    new GreaterThan("+ 5 hours")
                ],
            ])

            ->add('address', null, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])

            ->add('zipcode', null, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])

            ->add('city', null, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])

            ->add('country', null, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])

            ->add('latitude', null, [
                'required' => false,
            ])

            ->add('longitude', null, [
                'required' => false,
            ])

            ->add('maxMembers', null, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual(2),
                ],
            ])

            ->add('isOnline', null, [
                'required' => false,
                'empty_data' => false,
            ])

            ->add('isArchived', ChoiceType::class, [
                'required' => false,
                'empty_data' => false,
                'choices' => [
                    'archived' => true,
                    'not archived' => false,
                ],
                'expanded' => true,
                'multiple' => false,
            ])

            ->add('category', null, [
                'constraints' => [
                    new NotBlank(),
                ],
            ])

            ->add('author', null, [
                'constraints' => [
                    new NotBlank(),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
