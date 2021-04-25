<?php

namespace App\Twig;

use mysql_xdevapi\Exception;
use Symfony\Component\Asset\Packages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    private $assets;

    public function __construct(Packages $assetPackage)
    {
        $this->assets = $assetPackage;
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
    public function formatActive($status ) : string
    {
        $onButton = $this->assets->getUrl('back/assets/images/button-on-green.png');
        $offButton = $this->assets->getUrl('back/assets/images/button-off-red.png');
        $tagImg = '<img src="//"  alt="//" width="32"/>';

        if ($status) {
            $tagImg = str_replace('//' , $onButton, $tagImg) ;
        } else {
            $tagImg = str_replace('//' , $offButton, $tagImg) ;
        }

        return $tagImg;

    }

    public function formatRole( $role )
    {

        if(!empty($role)) {

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
}