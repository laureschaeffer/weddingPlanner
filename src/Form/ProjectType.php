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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'row_attr' => [
                    'class' => 'form-col'
                ],
                'label' => 'Prénom',
                'data' => 'Jean'
            ])
            ->add('surname', TextType::class, [
                'row_attr' => [
                    'class' => 'form-col'
                ],
                'label' => 'Nom',
                'data' => 'Muller'
            ])
            ->add('email', EmailType::class, [
                'row_attr' => [
                    'class' => 'form-col'
                ],
                'label' => 'Email',
                'required' => false
            ])
            ->add('telephone', TelType::class, [
                'row_attr' => [
                    'class' => 'form-col'
                ],
                'label' => 'Téléphone',
                'data' => '0611223344'
            ])
            ->add('dateEvent', DateTimeType::class, [
                'row_attr' => [
                    'class' => 'form-col'
                ],
                'widget' => 'single_text',
                'label' => 'Date de l\'évènement'
            ])
            ->add('nbGuest', IntegerType::class, [
                'row_attr' => [
                    'class' => 'form-col'
                ],
                'label' => 'Nombre d\'invités',
                'data' => '50'
            ])
            ->add('description', TextareaType::class, [
                'row_attr' => [
                    'class' => 'form-col'
                ],
                'label' => 'Commentaires',
                'data' => "J'aimerai vous parler de mon projet"
            ])
            ->add('Destination', EntityType::class, [
                'class' => Destination::class,
                'choice_label' => 'name',
                'row_attr' => [
                    'class' => 'form-col'
                ],
            ])
            ->add('budget', EntityType::class, [
                'class' => Budget::class,
                'row_attr' => [
                    'class' => 'form-col'
                ],
                // 'choice_label' => 'id',
            ])
            ->add('prestations', EntityType::class, [
                'row_attr' => [
                    'class' => 'form-col'
                ],
                'class' => Prestation::class,
                'choice_label' => 'title',
                'multiple' => true, //obligatoire car ça suit la cardinalité établie entre Projet et Prestation
                'expanded' => true //pour avoir des checkbox au lieu d'un select
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
            'data_class' => Project::class,
        ]);
    }
}
