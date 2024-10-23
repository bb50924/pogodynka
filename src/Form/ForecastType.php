<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Forecast;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForecastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('temperature', NumberType::class, [
                'attr' => [
                    'min' => -20,
                    'max' => 40,
                ],
                'html5' => true,
            ])
            ->add('humidity', NumberType::class)
            ->add('pressure', NumberType::class)
            ->add('weather', ChoiceType::class, [
                'choices' => [
                    null => null,
                    'sunny' => 'sunny',
                    'windy' => 'windy',
                    'cloudy' => 'cloudy',
                    'rainy' => 'rainy',
                    'stormy' => 'stormy',
                ],
            ])
            ->add('location', EntityType::class, [
                'required' => false,
                'class' => Location::class,
                'choice_label' => 'city',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Forecast::class,
        ]);
    }
}