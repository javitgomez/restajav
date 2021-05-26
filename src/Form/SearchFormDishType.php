<?php

namespace App\Form;

use App\Entity\Dish;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormDishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('criteria', TextType::class,[
                'mapped' => false,
                'attr' => ['class' => 'form-control','placeholder' => 'Buscar por plato'],
                'label' => 'Selector de platos'
            ]);
        ;

        $builder
            ->add('save', SubmitType::class, [
                'label' => 'Buscar',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'style' => 'display:block;margin-top:15px;',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
