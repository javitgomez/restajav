<?php

namespace App\Form;

use App\Entity\Promotions;
use App\Entity\Category;
use App\Entity\Dish;
use DateTime;
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
            ->add('code',HiddenType::class)
            ->add('dto',HiddenType::class)
            ->add('dish',HiddenType::class)
            ->add('category',HiddenType::class)
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                [$this, 'onPreSetData']
            )
        ;

    }

    /**
     * @throws \Exception
     */
    public function onPreSetData(FormEvent $event): void
    {
        $data = $event->getData();

        $category = $this->em->getRepository(Category::class)->find($data['category']);
        $data['category'] = $category;
        $data['dish'] = $this->em->getRepository(Dish::class)->find($data['dish']);

        $data['code'] = strtoupper($data['code']);
        $data['begin'] = new DateTime(str_replace('/','-',$data['begin']));
        $data['ending'] = new DateTime(str_replace('/','-',$data['ending']));

        $event->setData($data);

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Promotions::class,
        ]);
    }
}
