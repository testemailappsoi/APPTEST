<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

class UserType extends AbstractType
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
                'trim' => true,
                'constraints' => [
                    new NotBlank(['message' => 'saisir votre CIN.']),
                    new Length([
                    'min' => 12,
                    'minMessage' => 'Votre CIN doit avoir {{ limit }} chiffres',
                    'max' => 12
                    ]),  
                ],
                
            ] )
            ->add('Email', TypeTextType::class , [
                'constraints' => new NotBlank(['message' => 'saisir votre Email.'])
            ]) 
            ->add('Contact', TelType::class , [
                'constraints' => new NotBlank(['message' => 'saisir votre Nom.']),
                'trim' => true
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
