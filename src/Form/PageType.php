<?php

namespace App\Form;

use App\Entity\Page;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', TextType::class, ['label'=>'Mot clé du chemin d\'accés (pas d\'espace !)'])
            ->add('name', TextType::class, ['label'=>'Nom de menu de la page'])
            ->add('content', CKEditorType::class, ['config_name'=>'action_config', 'label'=>'Contenu', 'attr'=>['class'=>'d-body']])
            ->add('submit', SubmitType::class, ['label'=>'Valider'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
