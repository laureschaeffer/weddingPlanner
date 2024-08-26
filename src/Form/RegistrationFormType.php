<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'row_attr' => [
                    'class' => 'login-row'
                ]
            ])
            ->add('pseudo', TextType::class, [
                'row_attr' => [
                    'class' => 'login-row'
                ]
            ])
            //RepeatedType: affiche deux fois mais 'fusionne' les deux champs lorsqu'ils sont identiques pour en enregistrer un dans la bdd
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false, //pas enregistré dans la bdd
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes ne correspondent pas.',
                'options' => ['row_attr' => ['class' => 'login-row']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Vérification du mot de passe'],
                //contrainte pour la sécurité
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe',
                    ]),
                    new Regex([
                        'pattern' => ('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{5,}$/'),
                        'message' => 'Le mot de passe devrait contenir au moins une lettre majuscule, un chiffre et un caractère spécial'
                    ]),  
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Le mot de passe devrait contenir au moins 12 caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'row_attr' => [
                    'class' => 'agree-terms-row'
                ],
                'mapped' => false,
                'label' => 'Conditions d\'utilisation',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez accepter nos conditions d\'utilisation'
                    ]),
                ],
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'contact-btn'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
