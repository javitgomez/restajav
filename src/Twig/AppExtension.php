<?php

namespace App\Twig;

use App\Entity\Dish;
use App\Repository\ConfigRepository;
use App\Repository\DishRepository;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Symfony\Component\Asset\Packages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $assets;
    private $config;
    private $em;

    public function __construct(Packages $assetPackage, ConfigRepository $configRepository, EntityManagerInterface  $em)
    {
        $this->assets = $assetPackage;
        $this->config = $configRepository;
        $this->em = $em;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('active', [$this, 'formatActive']),
            new TwigFilter('role', [$this, 'formatRole']),
            new TwigFilter('dish', [$this, 'getDish']),
            new TwigFilter('state', [$this, 'formatStateOrder']),
        ];
    }

    /**
     * @param $status
     * @return string
     */
    public function formatActive($status) : string
    {
        $onButton = $this->assets->getUrl('back/assets/images/button-on-green.png');
        $offButton = $this->assets->getUrl('back/assets/images/button-off-red.png');
        $tagImg = '<img src="//"  alt="//" width="16"/>';

        if ($status) {
            $tagImg = str_replace('//', $onButton, $tagImg) ;
        } else {
            $tagImg = str_replace('//', $offButton, $tagImg) ;
        }

        return $tagImg;
    }

    /**
     * @param $status
     * @return string
     */
    public function formatStateOrder($status) : string
    {
        $receivedButton = $this->assets->getUrl('back/assets/images/button-blue.png');
        $canceledButton = $this->assets->getUrl('back/assets/images/button-off-red.png');
        $finishedButton = $this->assets->getUrl('back/assets/images/button-on-green.png');
        $preparedButton = $this->assets->getUrl('back/assets/images/button-yellow.png');

        $tagImg = '<img src="//"  alt="//" width="16" title="'.$status.'" />';

        switch ($status) {
            case 'received': $tagImg = str_replace('//', $receivedButton, $tagImg) ; break;
            case 'canceled': $tagImg = str_replace('//', $canceledButton, $tagImg) ; break;
            case 'finished': $tagImg = str_replace('//', $finishedButton, $tagImg) ; break;
            case 'prepared': $tagImg = str_replace('//', $preparedButton, $tagImg) ; break;
        }

        return $tagImg;
    }

    public function formatRole($role): string
    {
        if (!empty($role)) {
            $firstRole = array_shift($role);

            switch ($firstRole) {
                case 'ROLE_ADMIN':
                    return 'Administrador';
                    break;
                case 'ROLE_USER':
                    return 'Usuario';
                    break;
                case 'ROLE_PARTNER':
                    return 'Comandante';
            }
        } else {
            throw new Exception("Simon says:this array not can be empty!");
        }
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('show_photo_gallery', [$this, 'showPhotoGallery'])];
    }

    public function showPhotoGallery($images): array
    {
        $config = $this->config->find(1);
        if ($config && $config->getNumberPhotoGallery() != null) {
            return array_slice($images, 0, $config->getNumberPhotoGallery());
        }
        return array_slice($images, 0, 8);
    }

    public function getDish($dishId): Dish
    {
        $dishRepository = $this->em->getRepository(Dish::class);
        /** @var \App\Entity\Dish $dish */
        $dish = $dishRepository->findOneBy(['id' => $dishId]);

        if (!$dish) {
            throw new Exception("Simon says: this dish not exist!");
        }

        return $dish;
    }
}
