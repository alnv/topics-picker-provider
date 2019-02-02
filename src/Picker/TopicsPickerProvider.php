<?php

namespace Alnv\TopicsPickerProviderBundle\Picker;

use Contao\CoreBundle\Framework\FrameworkAwareInterface;
use Contao\CoreBundle\Framework\FrameworkAwareTrait;
use Contao\CoreBundle\Picker\AbstractPickerProvider;
use Contao\CoreBundle\Picker\DcaPickerProviderInterface;
use Contao\CoreBundle\Picker\PickerConfig;


class TopicsPickerProvider extends AbstractPickerProvider implements DcaPickerProviderInterface, FrameworkAwareInterface {


    use FrameworkAwareTrait;


    public function getName(): string {

        return 'topicsPicker';
    }


    public function supportsContext( $context ): bool {

        return 'link' === $context;
    }


    public function supportsValue( PickerConfig $config ): bool {

        return false !== strpos( $config->getValue(), '{{CTLG_ENTITY_URL::');
    }


    public function getDcaTable(): string {

        return 'ctlg_topics';
    }


    public function getDcaAttributes(PickerConfig $config): array {

        $attributes = ['fieldType' => 'radio'];

        if ($source = $config->getExtra('source')) {

            $attributes['preserveRecord'] = $source;
        }

        if ($this->supportsValue($config)) {

            $strTag = str_replace('{{', '', $config->getValue() );
            $strTag = str_replace('}}', '', $strTag );
            $arrValues = explode( '::', $strTag );
            $strValue = '';

            if ( is_array( $arrValues ) && isset( $arrValues[2] ) ) {

                $strValue = $arrValues[2];
            }

            $attributes['value'] = $strValue;
        }

        return $attributes;
    }


    public function convertDcaValue( PickerConfig $config, $value ): string {

        $strModuleId = 'noID';
        $objDatabase = \Database::getInstance();
        $objEntity = $objDatabase->prepare( 'SELECT * FROM ctlg_topics WHERE id = ?' )->limit(1)->execute( $value );

        $arrModulesByCategory = [

            'energiepolitik' => 13,
            'energiewirtschaft' => 23,
            'gesichter-der-energiewende' => 28,
            'klimawandel-umwelt' => 25,
            'offshore-wind' => 24,
            'trends-technik' => 26,
            'koepfe-der-energiewende' => 28
        ];

        if ( $objEntity->category ) {

            $strModuleId = $arrModulesByCategory[ $objEntity->category ];
        }

        return '{{CTLG_ENTITY_URL::'.$strModuleId.'::'.$value.'}}';
    }


    protected function getRouteParameters(PickerConfig $config = null): array {

        $arrParams = [ 'do' => 'topics' ];

        return $arrParams;
    }
}
