<?php

namespace App\Form;

use App\Entity\Professional;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfessionalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('job')
            ->add('imageFile', ElFinderType::class, ['label'=>'Image de profil', 'instance'=>'form','enable' => true])
            ->add('citation')
            ->add('description', TextareaType::class)
            ->add('pageContent', CKEditorType::class, ['label'=>'Contenu de votre page'])
            ->add("submit", SubmitType::class, ['label'=>'Valider'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Professional::class,
        ]);
    }
}
