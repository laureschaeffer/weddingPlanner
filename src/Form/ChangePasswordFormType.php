<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'nouveau-mot-de-passe',
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Entrez un mot de passe s\'il vous plait',
                        ]),
                        new Length([
                            'min' => 12,
                            'minMessage' => 'Le mot de passe devrait contenir au moins 12 caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                        new Regex([
                            'pattern' => ('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{5,}$/'),
                            'message' => 'Le mot de passe devrait contenir au moins une lettre majuscule, un chiffre et un caractère spécial'
                        ]),
                        // new PasswordStrength(),
                        // notcompromised: valide que le mot de passe donné n'a pas été compromis, en vérifiant qu'il n'est inclus dans aucune des violations de données publiques suivies par haveibeenpwned.com
                        new NotCompromisedPassword([
                            'message' => "Ce mot de passe a été divulgué lors d'une violation de données, il ne doit pas être utilisé. Veuillez utiliser un autre mot de passe"
                        ]),
                    ],
                    'label' => 'Nouveau mot de passe',
                ],
                'second_options' => [
                    'label' => 'Vérification du mot de passe',
                ],
                'invalid_message' => 'Les mots de passes ne correspondent pas.',
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
