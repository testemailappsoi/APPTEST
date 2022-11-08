<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FaqType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Question', TextareaType::class, [
                'attr' => ['row' =>5],
                'disabled' => true,
            ] )
            ->add('FAQ', TextareaType::class, [
                'attr' => [
                    'rows' => "5" ],
                'label' => 'Reformulation',
                'disabled' => false,
                'constraints' => new NotBlank(['message' => 'Veillez soumettre une Solution.'])
            ] )
            ->add('Solution', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => "5" ],
                'label' => false,
                'constraints' => new NotBlank(['message' => 'Veillez soumettre une Solution.'] )
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
