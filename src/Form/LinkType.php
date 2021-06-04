<?php

namespace App\Form;

use App\Entity\Link;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Etiqueta',
                'attr' => ['class' => 'form-control']
            ])
            ->add('href', TextType::class, [
                'label' => 'Nombre ruta',
                'attr' => ['class' => 'form-control']
            ])->add('marker', TextType::class, [
                'label' => 'Marcador (opcional)',
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'mapped' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Guardar',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Link::class,
        ]);
    }
}
