<?php

namespace App\Form;

use App\Entity\Application;
use App\Entity\Control;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ControlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomCont',TextType::class,[
                'constraints' => new NotBlank()
            ] )
            ->add('appli', EntityType::class , [
                'class' => Application::class,
                'required' => false,
                'attr' => [
                    'class' => 'select2 form-control'
                ]
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Control::class,
        ]);
    }
}
