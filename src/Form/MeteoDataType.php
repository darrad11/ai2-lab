<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\MeteoData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeteoDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'city',
            ])
            ->add('celsius_temperature')
            ->add('flcelsius_temperature', null, [
                'label' => 'Feels like temperature',
            ])
            ->add('pressure')
            ->add('humidity')
            ->add('windSpeed')
            ->add('wind_direction')
            ->add('cloudiness')
            ->add('icon', ChoiceType::class, [
                'choices' => [
                    'sun' => 'bi-sun',
                    'cloud' => 'bi-cloud',
                    'cloud-sun' => 'bi-cloud-sun',
                    'cloud-rain' => 'bi-cloud-rain',
                    'cloud-snow' => 'bi-cloud-snow',
                    'snow' => 'bi-snow',
                    'cloud-lightning' => 'bi-cloud-lightning',
                    'wind' => 'bi-wind',
                    'cloud-fog' => 'bi-cloud-fog',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MeteoData::class,
        ]);
    }
}
