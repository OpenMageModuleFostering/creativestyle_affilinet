<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
abstract class Creativestyle_AffiliNet_Block_Abstract extends Mage_Core_Block_Template {

    protected $_order = null;

    protected function _formatAmount($amount) {
        return number_format($amount, 2, '.', '');
    }

    protected function _getConfig() {
        return Mage::getSingleton('affilinet/config');
    }

    protected function _getProductIdentifier() {
        return $this->_getConfig()->getProductIdentifier();
    }

    protected function _getManufacturerAttribute() {
        return $this->_getConfig()->getManufacturerAttribute();
    }

    protected function _getOrder() {
        if (null === $this->_order) {
            $orderIds = $this->getOrderIds();
            if (is_array($orderIds) && !empty($orderIds)) {
                foreach ($orderIds as $orderId) {
                    $this->_order = Mage::getModel('sales/order')->load($orderId);
                    if ($this->_order->getId()) break;
                }
            } else {
                $this->_order = false;
            }
        }
        return $this->_order;
    }

    public function getCouponCode() {
        if ($this->_getOrder()) {
            return rawurlencode($this->_getOrder()->getCouponCode());
        }
        return '';
    }

    public function getOrderId() {
        if ($this->_getOrder()) {
            return rawurlencode($this->_getOrder()->getIncrementId());
        }
        return '';
    }

    public function getOrderCurrency() {
        if ($this->_getOrder()) {
            return rawurlencode($this->_getOrder()->getBaseCurrencyCode());
        }
        return '';
    }

    public function getOrderTotal() {
        if ($this->_getOrder()) {
            return rawurlencode($this->_formatAmount($this->_getOrder()->getBaseGrandTotal()));
        }
        return '';
    }

    public function getOrderNetTotal() {
        if ($this->_getOrder()) {
            return rawurlencode($this->_formatAmount($this->_getOrder()->getBaseGrandTotal() - $this->_getOrder()->getBaseTaxAmount() - $this->_getOrder()->getBaseShippingAmount()));
        }
        return '';
    }

    public function getProgramId() {
        return $this->_getConfig()->getProgramId();
    }

    public function getTrackingDomain() {
        return $this->_getConfig()->getTrackingDomain();
    }

    public function isRetargetingActive() {
        return $this->_getConfig()->isRetargetingActive();
    }

}
