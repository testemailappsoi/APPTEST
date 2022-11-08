<?php

namespace App\Form;

use App\Entity\Fonc;
use App\Entity\Rout;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FoncType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomFonc',TextType::class,[
                'constraints' => new NotBlank(['message' => 'Veillez soumettre votre question.'])
            ] )
            ->add('Autre', TextareaType::class, [
                'required' => false,
                'constraints' => new NotBlank,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => "5" ],
                'label' => false,
            ])
            ->add('rout', EntityType::class , [
                'class' => Rout::class,
                'required' => false,
                'placeholder' => 'Choissir ICI',
                'attr' => [
                    'class' => 'select2 form-control'
                ]
            ] );
        }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fonc::class,
        ]);
    }
}
