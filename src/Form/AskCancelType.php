<?php

namespace App\Form;

use App\Entity\AskCancel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AskCancelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reason', TextareaType::class, ['attr'=>['rows'=>8, 'placeholder'=>"Veuillez détailler le motif de la demande pour que le professionel soit en mesure de l'appréhender correctement"]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AskCancel::class,
        ]);
    }
}
