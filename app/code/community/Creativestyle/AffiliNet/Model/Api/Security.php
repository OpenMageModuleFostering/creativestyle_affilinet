<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Security {

    public $UsernameToken;

    protected function _getConfig() {
        return Mage::getSingleton('affilinet/config');
    }

    public function init($store = null) {
        $this->UsernameToken = new SoapVar(Mage::getModel('affilinet/api_usernameToken')->init($store), SOAP_ENC_OBJECT, null, null, null, $this->_getConfig()->getWebserviceNamespace());
        return $this;
    }

}
