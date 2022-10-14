<?php

namespace App\Form;

use App\Entity\Rout;
use App\Entity\Control;
use App\Entity\Application;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomRout', TextType::class,[
                'label' => 'Nom Route',
                'constraints' => new NotBlank()
            ])

            ->add('Appli', EntityType::class, [
                'class' => Application::class,
                'mapped' => false,
                'required' => false,
                'placeholder' => 'Choisir une Apllication'
            ])
            ;
        $builder->get('Appli')->addEventListener (
               FormEvents::POST_SUBMIT,
               function(FormEvent $event) {
                   $form = $event->getForm();
                 $this->AjoutController($form->getParent(),$form->getData() );
                   
            });
        $builder->addEventListener(
               FormEvents::PRE_SET_DATA,
               function(FormEvent $event){
                   $data=$event->getData();
                   $control = $data->getControl();
                   $form = $event->getForm();
                   if ($control) {
                       $appli = $control->getAppli();
                       $this->AjoutController($form, $appli);
                       $form->get('Appli')->setData($appli);
                   } else {
                       $this->AjoutController($form, null);
                   }
                   
                   
               });
    }

    private function AjoutController(FormInterface $form, ? Application $appli ){
        
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'control',
            EntityType::class,
            null,
            [
                'class' => Control::class,
                'placeholder' => $appli ? 'Choissir ICI' : 'CLiquez d\'abord sur enregistré',
                'auto_initialize' => false,
                'choices' => $appli ? $appli->getControls() : []
            ]
            );
          $form->add($builder->getForm());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rout::class,
        ]);
    }
}
