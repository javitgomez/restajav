<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['data'])) {

            $builder
                ->add('password-change', TextType::class, [
                    'mapped' => false
                ])
                ->add('repeat-password-change', TextType::class, [
                    'mapped' => false
                ])
                ->add('save', SubmitType::class, [
                    'attr' => ['class' => 'btn btn-secundary'],
                    'label' => 'Cambiar'
                ]);

        } else {

            $builder
            ->add('email', EmailType::class)
            ->add('save', SubmitType::class, [
                    'attr' => ['class' => 'btn btn-secundary'],
                    'label' => 'Enviar enlace'
            ]);
        }

  
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
