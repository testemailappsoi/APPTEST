<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;


class MailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Question', TextareaType::class, [
                'attr' => ['row' =>5],
                'required' => false,
                'disabled' => false,
                'label' => 'Question',
                'constraints' => new NotBlank(['message' => 'Veillez soumettre votre question.'] )
            ] )
            ->add('De', TypeTextType::class, [
                'label' => 'De (email)',
                'constraints' => new NotBlank(['message' => 'Votre Email.'] )
            ] )
            ->add('Email', TypeTextType::class, [
                'label' => 'à (email)',
                'constraints' => new NotBlank(['message' => 'Votre Email.'] )
            ] )
                ;
        
    }

    // public function configureOptions(OptionsResolver $resolver): void
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Question::class,
    //     ]);
    // }
}
