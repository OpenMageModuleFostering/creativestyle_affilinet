<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_System_Config_Source_Retargeting_Product extends Creativestyle_AffiliNet_Model_System_Config_Source_Abstract {

    const ORIGINAL_PRICE = 1;
    const PRODUCT_RATING = 2;
    const PRICE_LABEL    = 4;
    const PRODUCT_LABEL  = 8;

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array('value' => self::ORIGINAL_PRICE, 'label' => Mage::helper('affilinet')->__('Original price of a product, if reduced'), 'tooltip' => Mage::helper('affilinet')->__('Gross item value (including tax).')),
                array('value' => self::PRODUCT_RATING, 'label' => Mage::helper('affilinet')->__('Product rating'), 'tooltip' => Mage::helper('affilinet')->__('This helps target users via products with a high rating, for instance. Numeric value: 0 (not rated) to 10 (highest rating)')),
                array('value' => self::PRICE_LABEL, 'label' => Mage::helper('affilinet')->__('Label for reduced prices'), 'tooltip' => Mage::helper('affilinet')->__('Label if a product is reduced or not. 0 = no regular price, 1 = reduced price.')),
                array('value' => self::PRODUCT_LABEL, 'label' => Mage::helper('affilinet')->__('Label for main products or accessory'), 'tooltip' => Mage::helper('affilinet')->__('Label for main products or accessory. 0 = main product, 1 = accessory'))
            );
            array_unshift($this->_options, array(
                'value' => '',
                'label' => Mage::helper('affilinet')->__('-- Please Select --'))
            );
        }
        return $this->_options;
    }
}
