<?php

namespace Alnv\TopicsPickerProviderBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Alnv\TopicsPickerProviderBundle\AlnvTopicsPickerProviderBundle;


class Plugin implements BundlePluginInterface {


    public function getBundles( ParserInterface $parser ): array {

        return [

            BundleConfig::create( AlnvTopicsPickerProviderBundle::class )
                ->setLoadAfter( [ ContaoCoreBundle::class ] )
                ->setReplace( [ 'topics-picker-provider' ] ),
        ];
    }
}