<?php

namespace App\Form;

use App\Entity\Promotions;
use App\Entity\Category;
use App\Entity\Dish;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\CallbackTransformer;

class HiddenInputPromotionType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('begin', HiddenType::class)
            ->add('ending',HiddenType::class)
            ->add('dish',HiddenType::class)
            ->add('category',HiddenType::class)
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                [$this, 'onPreSetData']
            )
        ;

    }

    public function onPreSetData(FormEvent $event): void
    {
        $data = $event->getData();

        $category = $this->em->getRepository(Category::class)->find($data['category']);
        $data['category'] = $category;
        $data['dish'] = $this->em->getRepository(Dish::class)->find($data['dish']);
        $event->setData($data);

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Promotions::class,
        ]);
    }
}
