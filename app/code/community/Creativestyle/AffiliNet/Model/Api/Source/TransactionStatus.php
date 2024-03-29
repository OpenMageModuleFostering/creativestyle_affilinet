<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Source_TransactionStatus extends Creativestyle_AffiliNet_Model_Api_Source_Abstract {

    const OPEN      = 'Open';
    const CONFIRMED = 'Confirmed';
    const CANCELLED = 'Cancelled';
    const ALL       = 'All';

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array(
                    'value' => self::OPEN,
                    'label' => Mage::helper('affilinet')->__('Open')
                ),
                array(
                    'value' => self::CONFIRMED,
                    'label' => Mage::helper('affilinet')->__('Confirmed')
                ),
                array(
                    'value' => self::CANCELLED,
                    'label' => Mage::helper('affilinet')->__('Cancelled')
                ),
                array(
                    'value' => self::ALL,
                    'label' => Mage::helper('affilinet')->__('All')
                )
            );
        }
        return $this->_options;
    }

}
