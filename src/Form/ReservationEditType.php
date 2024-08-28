<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ReservationEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datePicking', DateType::class, [
                'row_attr' => [
                    'class' => 'form-col'
                ],
                'widget' => 'single_text',
                'label' => 'Date pour récupérer la commande'
            ])
            ->add('firstname', TextType::class, [
                'row_attr' => [
                    'class' => 'form-col'
                ],
                'label' => 'Prénom'
            ])
            ->add('surname', TextType::class, [
                'row_attr' => [
                    'class' => 'form-col'
                ],
                'label' => 'Nom'
            ])
            ->add('telephone', TelType::class, [
                'row_attr' => [
                    'class' => 'form-col'
                ],
                'label' => 'Téléphone'
            ])
            ->add('bookings', CollectionType::class, [
                'entry_type' => BookingType::class,
                'entry_options' => [
                    'attr' => ['class' => 'reservation-booking'],
                ],
                'label' => false,
                'by_reference' => false //pour éviter un mapping false, obligatoire car Reservation n'a pas de SetBooking, mais Booking a setReservation
                // Reservation est propriétaire de la relation
            ])
            ->add('Envoyer', SubmitType::class, [
                'attr' => [
                    'class' => 'contact-btn'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
