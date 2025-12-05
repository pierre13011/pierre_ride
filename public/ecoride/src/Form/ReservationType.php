<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $maxPlaces = $options['max_places'] ?? 1;

        $builder
            ->add('places', ChoiceType::class, [
                'label' => 'Nombre de places',
                'choices' => array_combine(
                    range(1, $maxPlaces),
                    range(1, $maxPlaces)
                ),
                'placeholder' => 'Sélectionnez',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'max_places' => null, // option custom
        ]);
    }
}
