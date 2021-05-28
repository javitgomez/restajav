<?php

namespace App\Form;

use App\Entity\Promotions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HiddenInputPromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('begin', HiddenType::class)
            ->add('ending',HiddenType::class)
            ->add('dish',HiddenType::class)
            ->add('category',HiddenType::class)
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Promotions::class,
        ]);
    }
}
