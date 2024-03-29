<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 * @author     Grzegorz Boguszy / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Report_Order_Grid extends Creativestyle_AffiliNet_Block_Adminhtml_Report_Abstract {

    public function __construct() {
        parent::__construct();
        $this->setId('affilinetOrderGrid');
    }

    protected function _getModel() {
        return Mage::getModel('affilinet/api_model_order');
    }

    protected function _prepareColumns() {

        $zeroAmountString = Mage::app()->getLocale()->currency($this->_getCurrencyCode())->toCurrency('0.00');

        $this->addColumn('basket_info', array(
            'header'    => Mage::helper('affilinet')->__('Basket'),
            'align'     => 'center',
            'sortable'  => false,
            'renderer' => 'Creativestyle_AffiliNet_Block_Adminhtml_Report_Order_Renderer_BasketInfo'
        ));

        $this->addColumn('order_id', array(
            'header'    => Mage::helper('affilinet')->__('Order ID'),
            'index'     => 'order_id',
            'sortable'  => false
        ));

        $this->addColumn('registration_date', array(
            'header'    => Mage::helper('affilinet')->__('Received on'),
            'index'     => 'registration_date',
            'type'      => 'date',
            'sortable'  => false
        ));

        $this->addColumn('flag', array(
            'header'    => Mage::helper('affilinet')->__('Flag'),
            'index'     => 'flag',
            'sortable'  => false,
            'renderer' => 'Creativestyle_AffiliNet_Block_Adminhtml_Report_Order_Renderer_Flag'
        ));

        $this->addColumn('publisher_id', array(
            'header'    => Mage::helper('affilinet')->__('Publisher ID'),
            'index'     => 'publisher_id',
            'sortable'  => false
        ));

        $this->addColumn('transaction_status', array(
            'header'    => Mage::helper('affilinet')->__('Transaction status'),
            'index'     => 'transaction_status',
            'sortable'  => false
        ));

        $this->addColumn('net_price', array(
            'header'    => Mage::helper('affilinet')->__('Net price'),
            'index'     => 'net_price',
            'type'      => 'currency',
            'default'   => $zeroAmountString,
            'currency_code' => $this->_getCurrencyCode(),
            'sortable'  => false,
            'renderer' => 'Creativestyle_AffiliNet_Block_Adminhtml_Report_Order_Renderer_NetPrice'
        ));

        $this->addColumn('publisher_commission', array(
            'header'    => Mage::helper('affilinet')->__('Publisher commission'),
            'index'     => 'publisher_commission',
            'type'      => 'currency',
            'default'   => $zeroAmountString,
            'currency_code' => $this->_getCurrencyCode(),
            'sortable'  => false
        ));

        $this->addColumn('network_fee', array(
            'header'    => Mage::helper('affilinet')->__('Affilinet fee'),
            'index'     => 'network_fee',
            'type'      => 'currency',
            'default'   => $zeroAmountString,
            'currency_code' => $this->_getCurrencyCode(),
            'sortable'  => false
        ));

        $this->addColumn('auto_order_management_action', array(
            'header'    => Mage::helper('affilinet')->__('Auto action'),
            'index'     => 'auto_order_management_action',
            'sortable'  => false
        ));

        $this->addColumn('action_in_days', array(
            'header'    => Mage::helper('affilinet')->__('Auto action in days'),
            'index'     => 'action_in_days',
            'sortable'  => false,
            'renderer' => 'Creativestyle_AffiliNet_Block_Adminhtml_Report_Order_Renderer_ActionInDays'
        ));

        $this->addColumn('creative_type', array(
            'header'    => Mage::helper('affilinet')->__('Publicity'),
            'index'     => 'creative_info/creative_type',
            'frame_callback' => array($this, 'extractCreativeType'),
            'sortable'  => false
        ));

        $this->addColumn('click_date', array(
            'header'    => Mage::helper('affilinet')->__('Click date'),
            'index'     => 'click_date',
            'type'      => 'date',
            'sortable'  => false
        ));

        $this->addColumn('last_status_change_date', array(
            'header'    => Mage::helper('affilinet')->__('Edit date'),
            'index'     => 'last_status_change_date',
            'type'      => 'date',
            'sortable'  => false
        ));

        $this->addColumn('new_status', array(
            'header'    => Mage::helper('affilinet')->__('New status'),
            'sortable'  => false,
            'renderer' => 'Creativestyle_AffiliNet_Block_Adminhtml_Report_Order_Renderer_NewStatus'
        ));

        return parent::_prepareColumns();
    }

    protected function _addFilters() {
        $filterData = $this->getFilterData()->getData();
        if ($this->getCollection() && !(empty($filterData))) {
            foreach ($filterData as $param => $value) {
                switch (strtolower($param)) {
                    case 'transaction_status':
                        $this->getCollection()->addFilter('transaction_status', $value);
                        break;
                    case 'cancellation_reason':
                        $this->getCollection()->addFilter('cancellation_reason', $value);
                        break;
                    case 'order_id':
                        $this->getCollection()->addFilter('order_id', $value);
                        break;
                    case 'cart_ids':
                        $this->getCollection()->addFilter('cart_ids', explode(',', trim($value)));
                        break;
                    case 'transactions_ids':
                        $this->getCollection()->addFilter('transactions_ids', explode(',', trim($value)));
                        break;
                }
            }
        }
        return parent::_addFilters();
    }

    public function extractCreativeType($value, $row, $column, $isExport) {
        if ($value) {
            $creativeTypeSource = Mage::getSingleton('affilinet/api_source_creativeType')->getOptions();
            if (is_array($creativeTypeSource) && array_key_exists($value, $creativeTypeSource)) {
                return $creativeTypeSource[$value];
            }
            return $value;
        }
        return null;
    }

    public function extractCreative($value, $row, $column, $isExport) {
        $creativeNumber = $value->getCreativeNumber();
        if ($creativeNumber) {
            $creativeSource = Mage::getSingleton('affilinet/api_source_creative')->setStore($this->_store)->getOptions();
            if (is_array($creativeSource) && array_key_exists($creativeNumber, $creativeSource)) {
                return $creativeSource[$creativeNumber];
            }
        }
        return null;
    }

    public function getRowUrl($row) {
        return null;
    }

}
