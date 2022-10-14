<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class QuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Question', TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
                'constraints' => new NotBlank(['message' => 'Veillez soumettre votre question.'] )
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Insérer une image si besoin (image file .jpeg .png .jpg)',
                'required' => false,
                'label_attr' => [
                    'class' => 'form-label mt-1',
                    'width' => '50px'
                    ]
            ] );
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}


