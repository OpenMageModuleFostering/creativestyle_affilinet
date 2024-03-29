<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Report_Order_Filter_Form extends Creativestyle_AffiliNet_Block_Adminhtml_Report_Filter_Form_Abstract {

    protected function _prepareForm() {
        parent::_prepareForm();

        $fieldset = $this->getForm()->getElement('base_fieldset');
        $fieldset->setLegend($this->__('Filter criteria for orders'));

        $fieldset->addField('evaluation_method', 'select', array(
            'name'      => 'evaluation_method',
            'required'  => true,
            'label'     => Mage::helper('affilinet')->__('Evaluation method'),
            'title'     => Mage::helper('affilinet')->__('Evaluation method'),
            'values'    => Mage::getSingleton('affilinet/api_source_evaluationType')->setWebservice('orders')->toOptionArray()
        ), 'to');

        $fieldset->addField('transaction_status', 'select', array(
            'name'      => 'transaction_status',
            'required'  => true,
            'label'     => Mage::helper('affilinet')->__('Transaction status'),
            'title'     => Mage::helper('affilinet')->__('Transaction status'),
            'values'    => Mage::getSingleton('affilinet/api_source_transactionStatus')->toOptionArray()
        ), 'evaluation_method');

        $fieldset->addField('cancellation_reason', 'text', array(
            'name'      => 'cancellation_reason',
            'label'     => Mage::helper('affilinet')->__('Cancellation reason'),
            'title'     => Mage::helper('affilinet')->__('Cancellation reason')
        ), 'transaction_status');

        $fieldset->addField('order_id', 'text', array(
            'name'      => 'order_id',
            'label'     => Mage::helper('affilinet')->__('Order ID'),
            'title'     => Mage::helper('affilinet')->__('Order ID')
        ), 'cancellation_reason');

        return $this;
    }

}
