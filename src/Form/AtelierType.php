<?php

namespace App\Form;

use App\Entity\Atelier;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AtelierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'=>"Nom de l'atelier"
            ])
            ->add('start', DateTimeType::class, [
                'widget'=>'single_text',
                'label'=>"Début de l'atelier",
                'attr'=>['class'=>'datepicker']])
            ->add('end', DateTimeType::class, [
                'widget'=>'single_text',
                'label'=>"Fin de l'atelier",
                'attr'=>['class'=>'datepicker']])
            ->add('description', TextareaType::class, [
                'label'=>"Brève description de l'atelier",
                "attr"=>['rows'=>5]
            ])
            ->add('price_per_session', NumberType::class, ['html5'=>true, 'attr'=>['step'=>0.01],'label'=>'Prix par séance'])
            ->add('canJoinAfterStart', ChoiceType::class, ['label'=>"Permission de rejoindre l'atelier après qu'il a commencé", 'choices' => [
                "Autorisé" => true,
                'Interdit' => false,
            ]])
            ->add('sessionsMandatory', ChoiceType::class, ['label'=>"Obligation de participation", 'choices' => [
                "Les utilisateurs qui s'inscrivent doivent participer aux sessions" => true,
                "Les utilisateurs qui s'inscrivent n'ont pas d'obligation de participation aux sessions" => false,
            ]])
            ->add('max_user', IntegerType::class, ['label'=>'Nombre maximum de membre'])
            ->add('content', CKEditorType::class)
            ->add('picture', ElFinderType::class, ['label'=>'Image de couverture', 'instance'=>'form','enable' => true])
            ->add('submit', SubmitType::class, ['label'=>'Valider'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Atelier::class,
        ]);
    }
}
