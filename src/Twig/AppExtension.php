<?php

namespace App\Twig;

use App\Repository\ConfigRepository;
use mysql_xdevapi\Exception;
use Symfony\Component\Asset\Packages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $assets;
    private $config;



    public function __construct(Packages $assetPackage, ConfigRepository $configRepository)
    {
        $this->assets = $assetPackage;
        $this->config = $configRepository;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('active', [$this, 'formatActive']),
            new TwigFilter('role', [$this, 'formatRole']),
        ];
    }

    /**
     * @param Packages $assetPackage
     * @param $status
     * @return string
     */
    public function formatActive($status) : string
    {
        $onButton = $this->assets->getUrl('back/assets/images/button-on-green.png');
        $offButton = $this->assets->getUrl('back/assets/images/button-off-red.png');
        $tagImg = '<img src="//"  alt="//" width="32"/>';

        if ($status) {
            $tagImg = str_replace('//', $onButton, $tagImg) ;
        } else {
            $tagImg = str_replace('//', $offButton, $tagImg) ;
        }

        return $tagImg;
    }

    public function formatRole($role)
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
    public function getFunctions()
    {
        return [
            new TwigFunction('show_photo_gallery', [$this, 'showPhotoGallery'])];
    }
    public function showPhotoGallery($images)
    {
        $config = $this->config->find(1);
        if ($config && $config->getNumberPhotoGallery() != null) {
            return array_slice($images, 0, $config->getNumberPhotoGallery());
        }
        return array_slice($images, 0, 8);
    }
}
