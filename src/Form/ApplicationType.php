<?php

namespace App\Form;

use App\Entity\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomApp', TextType::class , [
                'constraints' => new NotBlank(['message' => 'Veillez remplir ce champ'])
            ])
            ->add('Version',TextType::class , [
                'constraints' => new NotBlank(['message' => 'Veillez remplir ce champ'])
            ])
            ->add('More', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => "5" ],
                'label' => 'Détails',
                'constraints' => new NotBlank(['message' => 'A remplir svp.'] )
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
