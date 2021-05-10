<?php

namespace App\Hydrators;

use App\Entity\Dish;
use App\Repository\CategoryRepository;

class DishHydrator
{
    private $dish ;

    public function __construct(CategoryRepository $categoryRepository, array  $arr)
    {
        $this->dish = new Dish();

        foreach ($arr as $k => $a) {
            if ($k == '_token') {
                continue;
            }
            if ($k == 'category') {
                $this->dish->setCategory($categoryRepository-> find($a));
            } else {
                $methodName = 'set' . ucfirst($k);
                $this->dish->{$methodName}($a);
            }
        }
    }

    public function getObject(): Dish
    {
        return $this->dish;
    }
}
