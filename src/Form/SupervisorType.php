<?php

namespace App\Form;

use App\Entity\Supervisor;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupervisorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label'=>"Nom de l'encadrant"])
            ->add('profession', TextType::class, ['label'=>"Profession de l'encadrant"])
            ->add('pictureFile', ElFinderType::class, ['label'=>'Image de profil', 'instance'=>'form','enable' => true])
            ->add('content', CKEditorType::class, ['label'=>"Description de l'encadrant"])
            ->add("submit", SubmitType::class, ['label'=>'Valider'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Supervisor::class,
        ]);
    }
}
