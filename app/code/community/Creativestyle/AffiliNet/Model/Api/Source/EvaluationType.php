<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Source_EvaluationType extends Creativestyle_AffiliNet_Model_Api_Source_Abstract {

    const ORDERS_REGISTERED         = 'RegistrationDate';
    const ORDERS_EDITED             = 'LastStatusChangeDate';
    const STATISTICS_REGISTRATION   = 'Registration';
    const STATISTICS_CONFIRMATION   = 'Confirmation';

    protected $_webservice = 'orders';

    public function toOptionArray() {
        if (null === $this->_options) {
            switch ($this->_webservice) {
                case 'orders':
                    $this->_options = array(
                        array(
                            'value' => self::ORDERS_REGISTERED,
                            'label' => Mage::helper('affilinet')->__('Registered in this period')
                        ),
                        array(
                            'value' => self::ORDERS_EDITED,
                            'label' => Mage::helper('affilinet')->__('Edited in this period')
                        )
                    );
                    break;
                case 'statistics':
                    $this->_options = array(
                        array(
                            'value' => self::STATISTICS_REGISTRATION,
                            'label' => Mage::helper('affilinet')->__('Registered in this period')
                        ),
                        array(
                            'value' => self::STATISTICS_CONFIRMATION,
                            'label' => Mage::helper('affilinet')->__('Edited in this period')
                        )
                    );
                    break;
            }
        }
        return $this->_options;
    }

    public function setWebservice($webservice) {
        if (in_array(strtolower($webservice), array('statistics', 'orders'))) {
            $this->_webservice = strtolower($webservice);
        }
        return $this;
    }

}
