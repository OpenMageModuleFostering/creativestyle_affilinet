<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_System_Config_Source_Retargeting_Category extends Creativestyle_AffiliNet_Model_System_Config_Source_Abstract {

    const CATEGORY_IMAGE_URL = 1;

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array('value' => self::CATEGORY_IMAGE_URL, 'label' => Mage::helper('affilinet')->__('URL of category image'), 'tooltip' => Mage::helper('affilinet')->__('URL of category image (URL encoded)')),
            );
            array_unshift($this->_options, array(
                'value' => '',
                'label' => Mage::helper('affilinet')->__('-- Please Select --'))
            );
        }
        return $this->_options;
    }
}
