<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Report_Order_Renderer_NetPrice extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Currency {

    public function render(Varien_Object $row) {
        if ($row->hasTransactionId()) {
            $transactionId = $row->getTransactionId();
            $isLocked = ($row->getTransactionStatus() == Creativestyle_AffiliNet_Model_Api_Source_TransactionStatus::CONFIRMED) ? true : false;
            $netPrice = Mage::app()->getLocale()->currency($this->_getCurrencyCode($row))->toCurrency(
                $row->getNetPrice(),
                array('display' => Zend_Currency::NO_SYMBOL)
            );
            $currencySymbol = Mage::app()->getLocale()->currency($this->_getCurrencyCode($row))->getSymbol();
            $html = '<div class="nowrap">';
            $html .= '<input' . ($isLocked ? ' disabled="disabled"' : '') . ' type="hidden" id="standard_' . $transactionId . '_net_price_current" name="standard[' . $transactionId . '][net_price][current]" value="' . $netPrice . '"/>';
            $html .= '<input' . ($isLocked ? ' disabled="disabled"' : '') . ' title="' . Mage::helper('affilinet')->__('Net price') . '" type="text" id="standard_' . $transactionId . '_net_price_new" name="standard[' . $transactionId . '][net_price][new]" value="' . $netPrice . '" class="new-net-price-input input-text validate-zero-or-greater a-right"/> ' . $currencySymbol;
            $html .= '</div>';
            return $html;
        }
        return parent::render($row);
    }

}
