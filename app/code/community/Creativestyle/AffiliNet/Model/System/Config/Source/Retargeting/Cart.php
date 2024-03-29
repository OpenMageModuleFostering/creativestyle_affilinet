<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_System_Config_Source_Retargeting_Cart extends Creativestyle_AffiliNet_Model_System_Config_Source_Abstract {

    const ORIGINAL_PRICE    = 1;
    const PRODUCTS_QUANTITY = 2;

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array('value' => self::ORIGINAL_PRICE, 'label' => Mage::helper('affilinet')->__('Original price of a product, if reduced'), 'tooltip' => Mage::helper('affilinet')->__('Gross item value (including tax).')),
                array('value' => self::PRODUCTS_QUANTITY, 'label' => Mage::helper('affilinet')->__('Quantity of products added to cart'), 'tooltip' => Mage::helper('affilinet')->__('Number of products the user has placed in the shopping cart. Alternatively, several quantities can be listed here separated by pipes |, depending on how the add-to-cart function has been implemented.')),
            );
            array_unshift($this->_options, array(
                'value' => '',
                'label' => Mage::helper('affilinet')->__('-- Please Select --'))
            );
        }
        return $this->_options;
    }
}
