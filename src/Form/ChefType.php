<?php

namespace App\Form;

use App\Entity\Chef;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ChefType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre Chef',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('job', TextType::class, [
                'label' => 'Puesto',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('twitter', TextType::class, [
                'label' => 'Twitter',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('facebook', TextType::class, [
                'label' => 'Facebook',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('instagram', TextType::class, [
                'label' => 'Instagram',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('linkedin', TextType::class, [
                'label' => 'Instagram',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('photo', FileType::class, [
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
                 ])->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => 'Guardar',
            ]);


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Chef::class,
        ]);
    }
}
