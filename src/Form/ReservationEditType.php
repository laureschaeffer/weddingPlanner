<?php

namespace App\Form;


use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReservationEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('totalPrice', NumberType::class)
            ->add('datePicking', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date pour récupérer la commande'
            ])
            ->add('bookings', CollectionType::class, [
                'entry_type' => BookingType::class,
                'label' => '',
                'prototype' => true,
                'by_reference' => false //pour éviter un mapping false, obligatoire car Reservation n'a pas de SetBooking, mais Booking a setReservation
                // Reservation est propriétaire de la relation
            ])
            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
