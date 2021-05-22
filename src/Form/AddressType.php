<?php

namespace App\Form;

use App\Entity\Chef;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use App\Entity\Address;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address',TextType::class , ['attr' => ['class'=>'form-control'],'label'=>'Dirección Postal'])
            ->add('number',NumberType::class , ['attr' => ['class'=>'form-control'],'label'=>'Número o puerta'])
            ->add('phone',TextType::class , ['attr' => ['class'=>'form-control'],'label'=>'Teléfono de contacto'])
            ->add('cp',TextType::class , ['attr' => ['class'=>'form-control'],'label'=>'Código Postal'])

            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary', 'style' => 'margin-top:15px;width:270px'],
                'label' => 'Hacer pedido'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
