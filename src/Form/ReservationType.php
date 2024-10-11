<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('Reserver', SubmitType::class, [
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
