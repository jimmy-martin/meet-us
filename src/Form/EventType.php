<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Blank;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // TODO voir contrainte de validation avec Front
        $builder
            ->add('title')
            ->add('description')
            ->add('picture', null, [
                'required' => false,
                'empty_data' => 'event_placeholder.png',
            ])
            ->add('date', null, [
                'widget' => 'single_text'
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
            ])
            ->add('maxMembers')

            ->add('isArchived', null, [
                'required' => false,
            ])
            ->add('isOnline', null, [
                'required' => false,
            ])
            ->add('createdAt', null, [
                'required' => false,
            ])
            ->add('updatedAt', null, [
                'required' => false,
            ])
            ->add('category', null, [
                'required' => false,
            ])
            ->add('author', null, [
                'required' => false,
            ])
            ->add('members', null, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
