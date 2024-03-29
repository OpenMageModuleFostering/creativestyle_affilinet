<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_System_Config_Source_Tracking_Parameters extends Creativestyle_AffiliNet_Model_System_Config_Source_Abstract {

    const PAYMENT_METHOD    = 'payment_method';
    const SHIPPING_METHOD   = 'shipping_method';

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array('value' => self::PAYMENT_METHOD, 'label' => Mage::helper('affilinet')->__('Payment method')),
                array('value' => self::SHIPPING_METHOD, 'label' => Mage::helper('affilinet')->__('Shipping method'))
            );
            array_unshift($this->_options, array(
                'value' => '',
                'label' => Mage::helper('affilinet')->__('-- Please Select --'))
            );
        }
        return $this->_options;
    }

}
