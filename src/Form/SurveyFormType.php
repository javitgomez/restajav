<?php

namespace App\Form;

use App\Entity\Survey;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SurveyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('valoration', ChoiceType::class, array(
                'choices' => array(
                    'Muy mala' => 1,
                    'Mala' => 2,
                    'Regular' => 3,
                    'Buena' => 4,
                    'Muy buena' => 5
                ),
                'label' => 'Valoración del servicio:',
                'expanded' => true,
            ))
            ->add('satisfaction', HiddenType::class,['attr'=>['value'=>1]])
            ->add('comment', TextareaType::class,[
                'label' => 'Algunos comentarios (aparecerán publicados en portada):'
            ])
        ;

        $builder
            ->add('save', SubmitType::class, [
                'label' => 'Enviar encuesta',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'style' => 'display:block;margin-top:15px;',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Survey::class,
        ]);
    }
}
