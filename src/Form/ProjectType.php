<?php

namespace App\Form;

use App\Entity\Budget;
use App\Entity\Project;
use App\Entity\Prestation;
use App\Entity\Destination;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => '',
                ],
                'label' => 'Prénom'
            ])
            ->add('surname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Téléphone'
            ])
            ->add('dateEvent', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de l\'évènement'
            ])
            ->add('nbGuest', IntegerType::class, [
                'label' => 'Nombre d\'invités'
            ])
            ->add('description', TextType::class, [
                'label' => 'Commentaires'
            ])
            ->add('Destination', EntityType::class, [
                'class' => Destination::class,
                'choice_label' => 'name',
            ])
            ->add('budget', EntityType::class, [
                'class' => Budget::class,
                // 'choice_label' => 'id',
            ])
            ->add('prestations', EntityType::class, [
                'class' => Prestation::class,
                'choice_label' => 'title',
                'multiple' => true, //obligatoire car ça suit la cardinalité établie entre Projet et Prestation
                'expanded' => true //pour avoir des checkbox au lieu d'un select
            ])
            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
