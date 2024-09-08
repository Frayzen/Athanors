<?php

namespace App\Form;

use App\Entity\Temoignage;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemoignageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label'=>'Nom du témoignage', 'attr'=>['placeholder'=>'Témoignage 1']])
            ->add('author', TextType::class, ['label'=>'Auteur du témoignage', 'attr'=>['placeholder'=>'Anonyme']])
            ->add('imageFilename', ElFinderType::class, ['instance'=>'image', 'enable'=>true, 'label'=>'Image de couverture', 'attr'=>['placeholder'=>'Clicker pour définir']])
            ->add('publication', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker']
            ])->add('content', CKEditorType::class, ['config_name'=>'temoignage_config', 'label'=>'Contenu'])
            ->add('audioFilename', ElFinderType::class, ['instance'=>'audio', 'enable'=>true, 'required'=>false, 'label'=>'Fichier audio', 'attr'=>['placeholder'=>'Clicker pour définir']])
            ->add('submit', SubmitType::class, ['label'=>"Valider"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Temoignage::class,
        ]);
    }
}
