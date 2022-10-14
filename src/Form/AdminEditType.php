<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TextType::class, [
                'disabled' => true,
            ] )
            ->add('Prenom', TextType::class, [
                'disabled' => true,
            ] )
            ->add('Cin', TextType::class ,[
                'disabled' => true
            ])
            ->add('Contact', TextType::class, [
                'disabled' => true,
            ] )
            ->add('roles', ChoiceType::class, [
                'choices'=> [
                    'Utilisateur' => 'ROLE_USER' ,
                    'Responsable' => 'ROLE_REP' ,
                    'Administrateur' => 'ROLE_ADMIN' ,
                    'Autre' => 'ROLE_UAPP'   
                ],
                'expanded' => true ,
                'multiple' => true ,
                'label' => 'Rôles'

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
