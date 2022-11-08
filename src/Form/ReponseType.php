<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Question', TextareaType::class, [
                'disabled' => true,
            ] )
            ->add('Reponse', TextareaType::class, [
                'label'=> false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Votre Réponse','row' => "5"],
                'constraints' => new NotBlank(['message' => 'Veillez soumettre votre Réponse.'] )
            ])
            ->add('User', TextType::class, [
                'label'=> 'À :  ',
                'disabled' => true,
            ])
            ->add('imageRep', VichImageType::class,[
                'label' => 'Pièce jointe',
                'required' => false,
                'label_attr' => [
                    'class' => 'form-label mt-4',
                    'width' => '50px'
                ]
            ])
            ->add('Finished', CheckboxType::class, array(
                'label'=> 'Terminé',
                'required' => false,
                'value' => 1,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
