<?php

namespace App\Form;

use App\Entity\Dish;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class DishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Nombre del plato',
            ])
            ->add('shortDescription', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Una pequeña descripción',
            ])
            ->add('prize', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Precio (total bruto) sin descuento en euros',
            ])
            ->add('allergen', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'label' => 'Incluir alérgenos',
                'choices'  => [
                    'Ninguno' => 'ninguno',
                    'Altramuz' => 'altramuz',
                    'Apio' => 'apio',
                    'Cacahuetes' => 'cacahuetes',
                    'Crustaceos' => 'crustaceos',
                    'Frutos-secos' => 'frutos-secos',
                    'Gluten' => 'gluten',
                    'Huevos' => 'lacteos',
                    'Moluscos' => 'moluscos',
                    'Mostaza' => 'mostaza',
                    'Pescado' => 'pescado',
                    'Sésamo' => 'sesamo',
                    'Soja' => 'soja',
                    'Sulfitos' => 'sulfitos'
                ],
                'attr' => ['class' => 'form-control']
            ])->add('photo', FileType::class, [
                'label' => 'Foto',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image image/png , image/jpg',
                    ])
                ],
            ])
        ;

        $builder
            ->add('save', SubmitType::class, [
                'label' => 'Guardar cambios',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'style' => 'display:block;margin-top:15px;',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dish::class,
        ]);
    }
}
