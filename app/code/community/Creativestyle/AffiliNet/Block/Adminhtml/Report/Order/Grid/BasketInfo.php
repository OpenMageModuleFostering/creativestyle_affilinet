<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Report_Order_Grid_BasketInfo extends Mage_Adminhtml_Block_Template {

    protected $_store = null;

    protected $_currencyCode = null;

    public function __construct() {
        parent::__construct();
        $this->setTemplate('creativestyle/affilinet/order/grid/basket.phtml');
    }

    protected function _getCurrencyCode() {
        if (null === $this->_currencyCode) {
            if (null === $this->_store) {
                $this->_store = is_object($this->getItems()) ? $this->getItems()->getStore() : null;
            }
            $this->_currencyCode = Mage::getSingleton('affilinet/config')->getPlatformCurrency($this->_store);
        }
        return $this->_currencyCode;
    }

    public function getItems() {
        return Mage::registry('affilinet_basket_info');
    }

    public function getBasketId() {
        return Mage::registry('affilinet_basket_id');
    }

    public function formatCurrency($amount) {
        return Mage::app()->getLocale()->currency($this->_getCurrencyCode())->toCurrency($amount);
    }

    public function getItemQuantityForm($basketItem) {
        if ($basketItem->getIsLocked()) {
            return $basketItem->getQuantity() . ' / ' . $basketItem->getOriginalQuantity();
        }

        $html = '<input type="hidden" id="baskets_' . $basketItem->getBasketId() . '_items_' . $basketItem->getPositionNumber() . '_qty_current" name="baskets[' . $basketItem->getBasketId() . '][items][' . $basketItem->getPositionNumber() . '][qty][current]" value="' . $basketItem->getQuantity() . '"/>';
        $html .= '<input style="width:40px;" type="text" id="qty_' . $basketItem->getBasketId() . '_' . $basketItem->getPositionNumber() . '_new" name="baskets[' . $basketItem->getBasketId() . '][items][' . $basketItem->getPositionNumber() . '][qty][new]" value="' . $basketItem->getQuantity() . '"/> / ' . $basketItem->getOriginalQuantity();
        return $html;
    }

    public function getItemStatusForm($basketItem) {
        if ($basketItem->getIsLocked()) {
            $cancellationReasons = Mage::getModel('affilinet/api_source_cancellationReason')->getOptions();
            return isset($cancellationReasons[$basketItem->getCancellationReason()]) ? $cancellationReasons[$basketItem->getCancellationReason()] : $basketItem->getCancellationReason();
        }

        $html = '<input type="hidden" id="baskets_' . $basketItem->getBasketId() . '_items_' . $basketItem->getPositionNumber() . '_status_current" name="baskets[' . $basketItem->getBasketId() . '][items][' . $basketItem->getPositionNumber() . '][status][current]" value="' . $basketItem->getCancellationReason() . '"/>';
        $cancellationReasons = Mage::getModel('affilinet/api_source_cancellationReason')->toOptionArray();
        $html .= '<select id="baskets_' . $basketItem->getBasketId() . '_items_' . $basketItem->getPositionNumber() . '_status_new" name="baskets[' . $basketItem->getBasketId() . '][items][' . $basketItem->getPositionNumber() . '][status][new]">';
        foreach ($cancellationReasons as $cancellationReason) {
            $html .= '<option value="' . $cancellationReason['value'] . '"' . ($cancellationReason['value'] == $basketItem->getCancellationReason() ? ' selected="selected"' : '') . '>' . $cancellationReason['label'] . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

}
