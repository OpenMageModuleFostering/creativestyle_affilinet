<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_System_Config_Source_Retargeting_Checkout extends Creativestyle_AffiliNet_Model_System_Config_Source_Abstract {

    const INDIVIDUAL_PRICE  = 1;
    const PRODUCTS_QUANTITY = 2;

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array('value' => self::INDIVIDUAL_PRICE, 'label' => Mage::helper('affilinet')->__('Individual prices of ordered products'), 'tooltip' => Mage::helper('affilinet')->__('Individual prices of ordered products separated by pipes |. Gross item value (w/o shipping costs)')),
                array('value' => self::PRODUCTS_QUANTITY, 'label' => Mage::helper('affilinet')->__('Quantities of ordered products'), 'tooltip' => Mage::helper('affilinet')->__('Number of ordered products separated by pipes |.'))
            );
            array_unshift($this->_options, array(
                'value' => '',
                'label' => Mage::helper('affilinet')->__('-- Please Select --'))
            );
        }
        return $this->_options;
    }
}

