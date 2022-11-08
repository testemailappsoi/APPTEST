<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Nom', TypeTextType::class , [
            
            'constraints' => new NotBlank(['message' => 'saisir votre Nom.'])
        ])
        ->add('Prenom', TypeTextType::class , [
            'constraints' => new NotBlank(['message' => 'saisir votre Prenom.'])
        ])
        ->add('cin', TypeTextType::class , [
            'attr' => ['placeholder' => '12 Chiffres'],
            'constraints' => [
                new NotBlank(['message' => 'saisir votre CIN.']),
                new Length([
                'min' => 12,
                'minMessage' => 'Votre CIN doit avoir {{ limit }} chiffres',
                'max' => 12,
            ])
                ],
            'trim' => true,
        ] )
        ->add('Mail', TypeTextType::class , [
            'attr' => ['placeholder' => 'exemple@gmail.com'],
            'constraints' => new NotBlank(['message' => 'saisir votre Email.'])
        ]) 
        ->add('Contact', TelType::class , [
            'constraints' => new NotBlank(['message' => 'saisir votre Numéro.'])
        ])
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'constraints' => [
                new IsTrue([
                    'message' => 'You should agree to our terms.',
                ]),
            ],
        ])
        ->add('password', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => true,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 5,
                    'minMessage' => 'Votre mot de passe doit avoir {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
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
