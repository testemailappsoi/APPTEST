<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mots', SearchType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control d-sm-flex align-items-center justify-content-between mb-4',
                    'placeholder' => 'Mot clé...',
                    'aria-label' => 'Search'
                ],
                'required' => false
            ])
            
            ->add('Rechercher', SubmitType::class, [
                'attr' => [
                    'class' => 'd-none d-sm-inline-block btn btn-sm btn-theme shadow-sm',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
