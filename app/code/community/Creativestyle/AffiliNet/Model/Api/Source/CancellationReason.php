<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Source_CancellationReason extends Creativestyle_AffiliNet_Model_Api_Source_Abstract {

    const NOT_CANCELLED         = 'NotCancelled';
    const CREDIT_CHECK_FAILED   = 'FailedCreditCheck';
    const CANCELLED_BY_CUSTOMER = 'SaleCancelledByCustomer';
    const ITEM_RETURNED         = 'ItemReturned';
    const OUT_OF_STOCK          = 'ItemOutOfStock';
    const DUPLICATED_ORDER      = 'DuplicatedOrder';
    const BREACH                = 'BreachOfCampaignTermsAndConditions';
    const OTHER                 = 'Other';

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array(
                    'value' => self::NOT_CANCELLED,
                    'label' => Mage::helper('affilinet')->__('Not cancelled')
                ),
                array(
                    'value' => self::CREDIT_CHECK_FAILED,
                    'label' => Mage::helper('affilinet')->__('Credit check failed')
                ),
                array(
                    'value' => self::CANCELLED_BY_CUSTOMER,
                    'label' => Mage::helper('affilinet')->__('Sale cancelled by customer')
                ),
                array(
                    'value' => self::ITEM_RETURNED,
                    'label' => Mage::helper('affilinet')->__('Item returned')
                ),
                array(
                    'value' => self::OUT_OF_STOCK,
                    'label' => Mage::helper('affilinet')->__('Item out of stock')
                ),
                array(
                    'value' => self::DUPLICATED_ORDER,
                    'label' => Mage::helper('affilinet')->__('Duplicated order')
                ),
                array(
                    'value' => self::BREACH,
                    'label' => Mage::helper('affilinet')->__('Breach of terms and conditions')
                ),
                array(
                    'value' => self::OTHER,
                    'label' => Mage::helper('affilinet')->__('Other')
                )
            );
        }
        return $this->_options;
    }

}
