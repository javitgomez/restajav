<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Nombre de la categoria',
            ])
            ->add('description', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Descripción de la categoria',
            ]);

        $builder
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary',
                    'style' => 'display:block;margin-bottom:15px;'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
