<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_System_Config_Source_Product_Id extends Creativestyle_AffiliNet_Model_System_Config_Source_Abstract {

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array('value' => 'sku', 'label' => Mage::helper('affilinet')->__('SKU')),
                array('value' => 'entity_id', 'label' => Mage::helper('affilinet')->__('Product ID'))
            );
        }
        return $this->_options;
    }
}
