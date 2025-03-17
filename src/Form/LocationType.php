<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TextType::class, [
                'label' => 'City',
                'attr' => [
                    'placeholder' => 'Enter city name',
                ],
                'constraints' => [
                    new NotBlank([
                        'groups' => ['new', 'edit'],
                        'message' => 'City name cannot be empty.',
                    ]),
                ],
            ])
            ->add('country', ChoiceType::class, [
                'label' => 'Country',
                'choices' => [
                    '' => null,
                    'Poland' => 'PL',
                    'Germany' => 'DE',
                    'France' => 'FR',
                    'Spain' => 'ES',
                    'Italy' => 'IT',
                    'United Kingdom' => 'UK',
                    'United States' => 'US',
                    'Portugal' => 'PT',
                ],
                'placeholder' => 'Choose a country',
                'constraints' => [
                    new NotBlank([
                        'groups' => ['new', 'edit'],
                        'message' => 'Please select a country.',
                    ]),
                ],
            ])
            ->add('latitude', NumberType::class, [
                'label' => 'Latitude',
                'html5' => true,
                'scale' => 7,
                'attr' => [
                    'step' => 0.1,
                    'min' => -90,
                    'max' => 90,
                    'placeholder' => 'Enter latitude',
                ],
                'constraints' => [
                    new NotBlank([
                        'groups' => ['new'],
                        'message' => 'Latitude is required.',
                    ]),
                    new Assert\Range([
                        'min' => -90,
                        'max' => 90,
                        'notInRangeMessage' => 'Latitude must be between -90 and 90.',
                        'groups' => ['new', 'edit'],
                    ]),
                ],
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude',
                'html5' => true,
                'scale' => 7,
                'attr' => [
                    'step' => 0.1,
                    'min' => -180,
                    'max' => 180,
                    'placeholder' => 'Enter longitude',
                ],
                'constraints' => [
                    new NotBlank([
                        'groups' => ['new'],
                        'message' => 'Longitude is required.',
                    ]),
                    new Assert\Range([
                        'min' => -180,
                        'max' => 180,
                        'notInRangeMessage' => 'Longitude must be between -180 and 180.',
                        'groups' => ['new', 'edit'],
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
            'validation_groups' => ['new', 'edit'],
        ]);
    }
}
