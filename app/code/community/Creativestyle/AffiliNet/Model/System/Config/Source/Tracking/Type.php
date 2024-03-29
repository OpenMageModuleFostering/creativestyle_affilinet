<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2015 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_System_Config_Source_Tracking_Type extends Creativestyle_AffiliNet_Model_System_Config_Source_Abstract {

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array('value' => 1, 'label' => Mage::helper('affilinet')->__('Basket tracking')),
                array('value' => 2, 'label' => Mage::helper('affilinet')->__('Standard tracking')),
                array('value' => 0, 'label' => Mage::helper('affilinet')->__('No tracking'))
            );
        }
        return $this->_options;
    }

}
