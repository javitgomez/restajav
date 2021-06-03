<?php

namespace App\Form;

use App\Entity\Dish;
use App\Entity\Category;
use App\Entity\Promotion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\CallbackTransformer;

class PromotionType extends AbstractType
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
            ->add('dish_ghost', TextType::class,[
                'label' => 'Plato',
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'mapped'=> false
            ])
            ->add('dish', HiddenType::class)
            ->add('category', ChoiceType::class,[
                'choices' => array_merge(['Sin categoria'=>-1],$categories),
                'label' => 'Selector de categorias',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('code',TextType::class, [
                'label' => 'Código de descuento',
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'text-transform: uppercase;font-weight:bold',
                ]
            ])
            ->add('dto',TextType::class,[
                'label' => 'Descuento aplicado',
                'attr' => [
                    'class' => 'form-control',
                    'onkeypress' => "return filterFloat(event,this);"
                ]
            ])
            ->add('begin',TextType::class,[
                'label' => 'Inicio de promoción',
                'attr' => ['class' => 'form-control']
            ])
            ->add('ending',TextType::class,[
                'label' => 'Fin promoción',
                'attr' => ['class' => 'form-control']
            ])  
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary', 'style' => 'margin-top:15px;width:270px'],
                'label' => 'Guardar promoción'
            ])
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                [$this, 'onPreSetData']
            );


            $builder->get('category')
            ->addModelTransformer(new CallbackTransformer(
            function ($categoryAsEntity) {
                if(null !== $categoryAsEntity){
                    return $categoryAsEntity->getId();
                }
            },
            function ($categoryAsString) {
                if(null !== $categoryAsString) {
                    return $this->em
                        ->getRepository(Category::class)
                        ->find(intval($categoryAsString));
                }

            }
            ))
            ;

            $builder->get('dish')
            ->addModelTransformer(new CallbackTransformer(
                function ($dishAsEntity) {
                    if(null !== $dishAsEntity){
                        return $dishAsEntity->getId();
                    }
                },
                function ($dishAsString) {
                    if(null !== $dishAsString) {
                        return $this->em
                            ->getRepository(Dish::class)
                            ->find(intval($dishAsString));
                    }

                }
            ))
            ;
    }

    /**
     * @throws \Exception
     */
    public function onPreSetData(FormEvent $event): void
    {
        $data = $event->getData();

        $data['begin'] = new \DateTime($data['begin'] ?? date('d/m/YYYY'));
        $data['ending'] = new \DateTime($data['ending'] ?? date('d/m/YYYY'));

        $event->setData($data);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
        ]);
    }
}
