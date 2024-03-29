<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_UsernameToken {

    public $Username;
    public $Password;
    public $Created;

    protected function _getConfig() {
        return Mage::getSingleton('affilinet/config');
    }

    public function init($store = null) {
        $this->Username = new SoapVar(trim($this->_getConfig()->getWebserviceUser($store)), XSD_STRING, null, null, null, $this->_getConfig()->getWebserviceNamespace());
        $this->Password = new SoapVar(trim($this->_getConfig()->getWebservicePassword($store)), XSD_STRING, null, null, null, $this->_getConfig()->getWebserviceNamespace());
        $this->Created = new SoapVar(Mage::getModel('core/date')->gmtDate("Y-m-d\TH:i:s.\\0\\0\\0\\Z"), XSD_STRING, null, null, null, $this->_getConfig()->getWebserviceSecurityNamespace());
        return $this;
    }

}
