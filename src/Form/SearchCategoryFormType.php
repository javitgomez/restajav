<?php

namespace App\Form;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchCategoryFormType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = $this->em
            ->getRepository(Category::class)
            ->getAllCategories();

        foreach ($categories as $k => $category){
            $categories[$category['name']] = $category['id'];
            unset($categories[$k]);
        }

        $builder
            ->add('categories', ChoiceType::class,[
                'choices' => array_merge(['Sin categoria'=>-1],$categories),
                'label' => 'Selector de categorias',
                'attr' => ['class' => 'form-control'],
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
