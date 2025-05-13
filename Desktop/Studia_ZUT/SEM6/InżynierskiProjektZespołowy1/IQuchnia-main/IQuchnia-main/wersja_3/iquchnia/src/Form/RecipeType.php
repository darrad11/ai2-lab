<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('instructions')
            ->add('cuisine', ChoiceType::class, [
                'choices' => [
                    'Amerykańska' => 'Amerykańska',
                    'Bliskowschodnia' => 'Bliskowschodnia',
                    'Brytyjska' => 'Brytyjska',
                    'Chińska' => 'Chińska',
                    'Francuska' => 'Francuska',
                    'Grecka' => 'Grecka',
                    'Hiszpańska' => 'Hiszpańska',
                    'Indyjska' => 'Indyjska',
                    'Japońska' => 'Japońska',
                    'Niemiecka' => 'Niemiecka',
                    'Śródziemnomorska' => 'Śródziemnomorska',
                    'Tajska' => 'Tajska',
                    'Włoska' => 'Włoska',
                ],
                'placeholder' => 'Wybierz kuchnię', // Opcja placeholder
            ])
            ->add('ingredients', TextType::class, [
                'label' => 'Składniki (oddzielone przecinkami)',
                'required' => true,
            ])
            ->add('difficulty', ChoiceType::class, [
                'choices' => [
                    'Początkujący' => 'beginner',
                    'Średniozaawansowany' => 'intermediate',
                    'Zaawansowany' => 'advanced',
                    'Masterchef' => 'masterchef',
                ],
                'placeholder' => 'Wybierz poziom trudności', // Opcja placeholder
            ])
            ->add('isVegetarian', ChoiceType::class, [
                'choices' => [
                    'Tak' => true,
                    'Nie' => false,
                ],
                'placeholder' => 'Wybierz rodzaj dania', // Opcja placeholder
                'label' => 'Czy wegetariański?',
            ])
            ->add('image', FileType::class, [
                'label' => 'Zdjęcie przepisu',
                'required' => true,
                'mapped' => false,
                'attr' => ['accept' => 'image/*'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}