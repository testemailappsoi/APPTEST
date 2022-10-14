<?php

namespace App\Form;

use App\Entity\Fonc;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class QuestionType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('Question', TextareaType::class, [
            'attr' => ['row' =>5],
            'label' => 'Votre Question',
            'constraints' => new NotBlank(['message' => 'Veillez soumettre votre question.'] )
        ] )
        ->add('Fonc', EntityType::class , [
            'class' => Fonc::class,
            'required' => false,
            'label' => 'Fonctionnalité',
            'placeholder' => 'Voir la liste des Fonctionnalités pour voir les noms',
            'attr' => [
                'class' => 'select2 form-control'
            ]
        ])
        ->add('imageFile', VichImageType::class,[
            'label' => 'Pièce jointe',
            'required' => false,
            'label_attr' => [
                'class' => 'form-label mt-4',
                'width' => '50px'
            ]
        ])
        ;

    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }

              
}
