<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Report_Order_Renderer_NewStatus extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $currentTransaction = $row->getTransactionStatus();
        $isLocked = ($currentTransaction == Creativestyle_AffiliNet_Model_Api_Source_TransactionStatus::CONFIRMED) ? true : false;
        $trackingType = null;
        $transactionId = null;
        if (is_object($row->getBasketInfo()) && $row->getBasketInfo()->hasBasketId()) {
            $trackingType = 'basket';
            $transactionId = $row->getBasketInfo()->getBasketId();
        } else if ($row->hasTransactionId()) {
            $trackingType = 'standard';
            $transactionId = $row->getTransactionId();
        }

        $html = '';
        if (null !== $trackingType && null !== $transactionId) {
            $transactionStatuses = Mage::getModel('affilinet/api_source_transactionStatus')->toOptionArray();
            $html .= '<div class="nowrap">';
            $html .= '<input' . ($isLocked ? ' disabled="disabled"' : '') . ' type="hidden" id="' . $trackingType . '_' . $transactionId . '_status_current" name="' . $trackingType . '[' . $transactionId . '][status][current]" value="' . $currentTransaction . '"/>';
            foreach ($transactionStatuses as $transactionStatus) {
                if ($transactionStatus['value'] == Creativestyle_AffiliNet_Model_Api_Source_TransactionStatus::ALL) continue;
                $html .= '<input' . ($isLocked ? ' disabled="disabled"' : '') . ' type="radio" id="' . $trackingType . '_' . $transactionId . '_status_new" name="' . $trackingType . '[' . $transactionId . '][status][new]" value="' . $transactionStatus['value'] . '"' . ($transactionStatus['value'] == $currentTransaction ? ' checked="checked"' : '') . '/> ' . $transactionStatus['label'];
                if ($transactionStatus['value'] == Creativestyle_AffiliNet_Model_Api_Source_TransactionStatus::CANCELLED) {
                    $html .= '<br/><input class="input-text"' . ($isLocked ? ' disabled="disabled"' : '') . ' title="' . Mage::helper('affilinet')->__('Cancellation reason') . '" type="text" id="' . $trackingType . '_' . $transactionId . '_cancellation_reason" name="' . $trackingType . '[' . $transactionId . '][cancellation_reason]" value="' . $row->getCancellationReason() . '"/><br/>';
                } else {
                    $html .= '<br/>';
                }
            }
            $html .= '</div>';
        }
        return $html;
    }

}
