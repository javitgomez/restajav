<?php

namespace App\Form;

use App\Entity\CustomManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class CustomManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photoMain', FileType::class, [
                'label' => 'Foto restaurante fondo',

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
            ->add('phone', TextType::class, [
                'label' => 'Teléfono restaurante',
                'attr' => ['class' => 'form-control']
            ])
            ->add('calendar', TextType::class, [
                'label' => 'Horario restaurante',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', CKEditorType::class, [])
            ->add('photo', FileType::class, [
                'label' => 'Foto restaurante',

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
            ->add('location', TextType::class, [
                'label' => 'Localización restaurante',
                'attr' => ['class' => 'form-control']
            ])
            ->add('openHour', TextType::class, [
                'label' => 'Horarios restaurante',
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', TextType::class, [
                'label' => 'Email restaurante',
                'attr' => ['class' => 'form-control']
            ])
            ->add('callPhone', TextType::class, [
                'label' => 'CallPhone restaurante',
                'attr' => ['class' => 'form-control']
            ])
            ->add('googleMapsFrame', TextType::class, [
                'label' => 'Mapa Google',
                'attr' => ['class' => 'form-control']
            ])->add('save', SubmitType::class, [
                'label' => 'Guardar',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CustomManager::class,
        ]);
    }
}
