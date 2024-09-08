<?php

namespace App\Form;

use App\Entity\Action;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label'=>"Nom de l'action", 'attr'=>['placeholder'=>'Action']])
            ->add('content', CKEditorType::class, ['config_name'=>'action_config', 'label'=>'Contenu', 'attr'=>['class'=>'d-body']])
            ->add('submit', SubmitType::class, ['label'=>"Valider"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Action::class,
        ]);
    }
}
