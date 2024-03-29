<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Source_CreativeType extends Creativestyle_AffiliNet_Model_Api_Source_Abstract {

    const TEXT   = 'Text';
    const BANNER = 'Banner';
    const HTML   = 'HTML';

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array(
                    'value' => self::TEXT,
                    'label' => Mage::helper('affilinet')->__('Text link')
                ),
                array(
                    'value' => self::BANNER,
                    'label' => Mage::helper('affilinet')->__('Banner')
                ),
                array(
                    'value' => self::HTML,
                    'label' => Mage::helper('affilinet')->__('HTML link')
                )
            );
            array_unshift($this->_options, array(
                'value' => '',
                'label' => Mage::helper('affilinet')->__('All media')
            ));
        }
        return $this->_options;
    }

    public static function getAllCreativeTypes() {
        return array(self::TEXT, self::BANNER, self::HTML);
    }

}
