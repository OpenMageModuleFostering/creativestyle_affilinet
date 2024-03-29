<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
abstract class Creativestyle_AffiliNet_Model_Api_Client_Abstract {

    protected static $_securityHeader = null;

    protected $_client = null;

    protected $_store = null;

    protected static function _getConfig() {
        return Mage::getSingleton('affilinet/config');
    }

    protected static function _getSecurityHeader($store = null) {
        if (null === self::$_securityHeader) {
            self::$_securityHeader = new SoapHeader(self::_getConfig()->getWebserviceNamespace(), 'Security', Mage::getModel('affilinet/api_security')->init($store), true);
        }
        return self::$_securityHeader;
    }

    protected function _getApi() {
        if (null === $this->_client) {
            $this->_client = new SoapClient($this->_getWsdlUrl());
            $this->_client->__setSoapHeaders(self::_getSecurityHeader($this->_store));
        }
        return $this->_client;
    }

    protected function _getWsdlUrl() {
        $area = preg_replace('/Creativestyle_AffiliNet_Model_Api_Client_/', '', get_class($this));
        return self::_getConfig()->getWsdlUrl($area);
    }

    protected function _getFormattedDate($date = null) {
        $dateModel = Mage::getModel('core/date');
        return $dateModel->date("Y-m-d\TH:i:s.\\0\\0\\0\\Z", $dateModel->gmtTimestamp($date));
    }

    public function setStore($store = null) {
        $this->_store = $store;
        return $this;
    }

}
