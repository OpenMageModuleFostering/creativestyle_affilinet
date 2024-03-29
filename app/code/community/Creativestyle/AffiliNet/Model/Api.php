<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
final class Creativestyle_AffiliNet_Model_Api {

    private static $_clients = array();

    private $_store = null;

    private static function _getApi($area, $store = null) {
        if (!array_key_exists(strtolower($area), self::$_clients)) {
            switch (strtolower($area)) {
                case 'toolbox':
                case 'statistics':
                case 'orders':
                    $apiClientClass = 'affilinet/api_client_' . strtolower($area);
                    break;
                default:
                    Mage::throwException('Unknown affilinet webservice: \'' . $area . '\'');
            }
            self::$_clients[strtolower($area)] = Mage::getModel($apiClientClass)->setStore($store);
        }
        return self::$_clients[strtolower($area)];
    }

    private function _getConfig() {
        return Mage::getSingleton('affilinet/config');
    }

    public function setStore($store) {
        $this->_store = $store;
        return $this;
    }

    public function getChannels($channelGroup) {
        return self::_getApi('toolbox', $this->_store)->getChannels($this->_getConfig()->getProgramId($this->_store), $channelGroup);
    }

    public function getCreativesPerType($creativeType) {
        return self::_getApi('toolbox', $this->_store)->getCreativesPerType($this->_getConfig()->getProgramId($this->_store), $creativeType);
    }

    public function getPublisherSegments() {
        return self::_getApi('toolbox', $this->_store)->getPublisherSegments($this->_getConfig()->getProgramId($this->_store));
    }

    public function getRateList() {
        return self::_getApi('toolbox', $this->_store)->getRateList($this->_getConfig()->getProgramId($this->_store));
    }

    public function getStatisticsPerPublisher($startDate, $endDate, $params, $currentPage = null, $pageSize = null) {
        return self::_getApi('statistics', $this->_store)
            ->getStatisticsPerPublisher(
                $this->_getConfig()->getProgramId($this->_store),
                $startDate,
                $endDate,
                $params,
                $currentPage,
                $pageSize
            );
    }

    public function getOrders($startDate, $endDate, $params, $currentPage = null, $pageSize = null) {
        return self::_getApi('orders', $this->_store)
            ->getOrders(
                $this->_getConfig()->getProgramId($this->_store),
                $startDate,
                $endDate,
                $params,
                $currentPage,
                $pageSize
            );
    }

    public function getBasketItems($basketId) {
        return self::_getApi('orders', $this->_store)->getBasketItems($this->_getConfig()->getProgramId($this->_store), $basketId);
    }

    public function updateBasketItem($basketId, $positionNumber, $params) {
        return self::_getApi('orders', $this->_store)->updateBasketItem($this->_getConfig()->getProgramId($this->_store), $basketId, $positionNumber, $params);
    }

    public function updateBasket($basketId, $action, $params) {
        return self::_getApi('orders', $this->_store)->updateBasket($this->_getConfig()->getProgramId($this->_store), $basketId, $action, $params);
    }

    public function updateTransaction($transactionId, $action, $params) {
        return self::_getApi('orders', $this->_store)->updateTransaction($this->_getConfig()->getProgramId($this->_store), $transactionId, $action, $params);
    }

}
