<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Report_Statistics_Grid extends Creativestyle_AffiliNet_Block_Adminhtml_Report_Abstract {

    public function __construct() {
        parent::__construct();
        $this->setId('affilinetStatisticsGrid');
    }

    protected function _addFilters() {
        $filterData = $this->getFilterData()->getData();
        if ($this->getCollection() && !(empty($filterData))) {
            foreach ($filterData as $param => $value) {
                switch (strtolower($param)) {
                    case 'creative_type':
                        $this->getCollection()->addFilter('creative_type', $value);
                        switch ($value) {
                            case Creativestyle_AffiliNet_Model_Api_Source_CreativeType::TEXT:
                                if ($this->getFilterData()->getData('creative_text')) {
                                    $this->getCollection()->addFilter('creative_number', $this->getFilterData()->getData('creative_text'));
                                }
                                break;
                            case Creativestyle_AffiliNet_Model_Api_Source_CreativeType::BANNER:
                                if ($this->getFilterData()->getData('creative_banner')) {
                                    $this->getCollection()->addFilter('creative_number', $this->getFilterData()->getData('creative_banner'));
                                }
                                break;
                            case Creativestyle_AffiliNet_Model_Api_Source_CreativeType::HTML:
                                if ($this->getFilterData()->getData('creative_html')) {
                                    $this->getCollection()->addFilter('creative_number', $this->getFilterData()->getData('creative_html'));
                                }
                                break;
                        }
                        break;
                    case 'publisher_volume':
                        if (is_array($value)) {
                            $volumeMin = array_key_exists('min', $value) ? $value['min'] : null;
                            $volumeMax = array_key_exists('max', $value) ? $value['max'] : null;
                            $volumeType = array_key_exists('type', $value) ? $value['type'] : null;

                            if (($volumeMin || $volumeMax) && $volumeType) {
                                $this->getCollection()->addFilter('volume_type', $volumeType);
                                if ($volumeMin) {
                                    $this->getCollection()->addFilter('volume_min', $volumeMin);
                                }
                                if ($volumeMax) {
                                    $this->getCollection()->addFilter('volume_max', $volumeMax);
                                }
                            }
                        }
                        break;
                }
            }
        }
        return parent::_addFilters();
    }

    protected function _getModel() {
        return Mage::getModel('affilinet/api_model_publisherStatistics');
    }

    protected function _prepareColumns() {

        $zeroAmountString = Mage::app()->getLocale()->currency($this->_getCurrencyCode())->toCurrency('0.00');

        $this->addColumn('publisher_url', array(
            'header'    => Mage::helper('affilinet')->__('URL'),
            'index'     => 'publisher_url',
            'sortable'  => false,
            'header_css_class' => 'a-center v-middle',
            'frame_callback' => array($this, 'decorateUrl')
        ));

        /**
         * Overview section
         * columns count = 4
         */
        $this->addColumn('views_gross_count', array(
            'header'    => Mage::helper('affilinet')->__('Views'),
            'index'     => 'views/gross_count',
            'type'      => 'number',
            'sortable'  => false,
            'column_css_class' => 'overview_group',
            'header_css_class' => 'overview_group a-center v-middle'
        ));

        $this->addColumn('clicks_gross_count', array(
            'header'    => Mage::helper('affilinet')->__('Clicks (Gross)'),
            'index'     => 'clicks/gross_count',
            'type'      => 'number',
            'sortable'  => false,
            'column_css_class' => 'overview_group',
            'header_css_class' => 'overview_group a-center v-middle clicks_subheader gross_subvalue'
        ));

        $this->addColumn('clicks_net_count', array(
            'header'    => Mage::helper('affilinet')->__('Clicks (Net)'),
            'index'     => 'clicks/net_count',
            'type'      => 'number',
            'sortable'  => false,
            'column_css_class' => 'overview_group',
            'header_css_class' => 'overview_group a-center v-middle clicks_subheader net_subvalue'
        ));

        $this->addColumn('clicks_through', array(
            'header'    => Mage::helper('affilinet')->__('CTR'),
            'index'     => 'clicks_through',
            'sortable'  => false,
            'renderer' => 'Creativestyle_AffiliNet_Block_Adminhtml_Report_Statistics_Renderer_Clicks',
            'column_css_class' => 'overview_group',
            'header_css_class' => 'overview_group a-center v-middle'
        ));

        /**
         * Sales section
         * columns count = 5
         */
        $this->addColumn('sales_conversion_rate_open', array(
            'header'    => Mage::helper('affilinet')->__('Sale Conversion Rate (Open)'),
            'index'     => 'sales_conversion_rate_open',
            'sortable'  => false,
            'renderer' => 'Creativestyle_AffiliNet_Block_Adminhtml_Report_Statistics_Renderer_Sales',
            'column_css_class' => 'sales_group',
            'header_css_class' => 'sales_group a-center v-middle sales_conversion_rate_subheader open_subvalue'
        ));

        $this->addColumn('sales_conversion_rate_confirmed', array(
            'header'    => Mage::helper('affilinet')->__('Sale Conversion Rate (Confirmed)'),
            'index'     => 'sales_conversion_rate_confirmed',
            'sortable'  => false,
            'renderer' => 'Creativestyle_AffiliNet_Block_Adminhtml_Report_Statistics_Renderer_Sales',
            'column_css_class' => 'sales_group',
            'header_css_class' => 'sales_group a-center v-middle sales_conversion_rate_subheader confirmed_subvalue'
        ));

        $this->addColumn('sales_count_open', array(
            'header'    => Mage::helper('affilinet')->__('# of Sales (Open)'),
            'index'     => 'sales/total_count/open_count',
            'type'      => 'number',
            'sortable'  => false,
            'column_css_class'=>'sales_group',
            'header_css_class'=>'sales_group a-center v-middle sales_count_subheader open_subvalue'
        ));

        $this->addColumn('sales_count_cancelled', array(
            'header'    => Mage::helper('affilinet')->__('# of Sales (Cancelled)'),
            'index'     => 'sales/total_count/cancelled_count',
            'type'      => 'number',
            'sortable'  => false,
            'column_css_class'=>'sales_group',
            'header_css_class'=>'sales_group a-center v-middle sales_count_subheader cancelled_subvalue'
        ));

        $this->addColumn('sales_count_confirmed', array(
            'header'    => Mage::helper('affilinet')->__('# of Sales (Confirmed)'),
            'index'     => 'sales/total_count/confirmed_count',
            'type'      => 'number',
            'sortable'  => false,
            'column_css_class'=>'sales_group',
            'header_css_class'=>'sales_group a-center v-middle sales_count_subheader confirmed_subvalue'
        ));

        /**
         * Leads section
         * columns count = 5
         */
        $this->addColumn('leads_conversion_rate_open', array(
            'header'    => Mage::helper('affilinet')->__('Lead Conversion Rate (Open)'),
            'index'     => 'leads_conversion_rate_open',
            'sortable'  => false,
            'renderer' => 'Creativestyle_AffiliNet_Block_Adminhtml_Report_Statistics_Renderer_Leads',
            'column_css_class' => 'leads_group',
            'header_css_class' => 'leads_group a-center v-middle leads_conversion_rate_subheader open_subvalue'
        ));

        $this->addColumn('leads_conversion_rate_confirmed', array(
            'header'    => Mage::helper('affilinet')->__('Lead Conversion Rate (Confirmed)'),
            'index'     => 'leads_conversion_rate_confirmed',
            'sortable'  => false,
            'renderer' => 'Creativestyle_AffiliNet_Block_Adminhtml_Report_Statistics_Renderer_Leads',
            'column_css_class' => 'leads_group',
            'header_css_class' => 'leads_group a-center v-middle leads_conversion_rate_subheader confirmed_subvalue'
        ));

        $this->addColumn('leads_count_open', array(
            'header'    => Mage::helper('affilinet')->__('# of Leads (Open)'),
            'index'     => 'leads/total_count/open_count',
            'type'      => 'number',
            'sortable'  => false,
            'column_css_class' => 'leads_group',
            'header_css_class' => 'leads_group a-center v-middle leads_count_subheader open_subvalue'
        ));

        $this->addColumn('leads_count_cancelled', array(
            'header'    => Mage::helper('affilinet')->__('# of Leads (Cancelled)'),
            'index'     => 'leads/total_count/cancelled_count',
            'type'      => 'number',
            'sortable'  => false,
            'column_css_class' => 'leads_group',
            'header_css_class' => 'leads_group a-center v-middle leads_count_subheader cancelled_subvalue'
        ));

        $this->addColumn('leads_count_confirmed', array(
            'header'    => Mage::helper('affilinet')->__('# of Leads (Confirmed)'),
            'index'     => 'leads/total_count/confirmed_count',
            'type'      => 'number',
            'sortable'  => false,
            'column_css_class' => 'leads_group',
            'header_css_class' => 'leads_group a-center v-middle leads_count_subheader confirmed_subvalue'
        ));

        /**
         * Commission section
         */
        $this->addColumn('bonus_count_confirmed', array(
            'header'    => Mage::helper('affilinet')->__('# of bonus payments'),
            'index'     => 'bonus/count/confirmed_count',
            'type'      => 'number',
            'sortable'  => false,
            'column_css_class' => 'commission_group',
            'header_css_class' => 'commission_group a-center v-middle'
        ));

        $this->addColumn('bonus_publisher_commission_confirmed', array(
            'header'    => Mage::helper('affilinet')->__('Amount of paid bonuses'),
            'index'     => 'bonus/publisher_commission/confirmed_value',
            'type'      => 'currency',
            'default'   => $zeroAmountString,
            'currency_code' => $this->_getCurrencyCode(),
            'sortable'  => false,
            'column_css_class' => 'commission_group',
            'header_css_class' => 'commission_group a-center v-middle'
        ));

        $this->addColumn('sales_total_publisher_commission_open', array(
            'header'    => Mage::helper('affilinet')->__('Sale Commission (Open)'),
            'index'     => 'sales/total_publisher_commission/open_value',
            'type'      => 'currency',
            'default'   => $zeroAmountString,
            'currency_code' => $this->_getCurrencyCode(),
            'sortable'  => false,
            'column_css_class' => 'commission_group',
            'header_css_class' => 'commission_group a-center v-middle sales_total_publisher_commission_subheader open_subvalue'
        ));

        $this->addColumn('sales_total_publisher_commission_confirmed', array(
            'header'    => Mage::helper('affilinet')->__('Sale Commission (Confirmed)'),
            'index'     => 'sales/total_publisher_commission/confirmed_value',
            'type'      => 'currency',
            'default'   => $zeroAmountString,
            'currency_code' => $this->_getCurrencyCode(),
            'sortable'  => false,
            'column_css_class' => 'commission_group',
            'header_css_class' => 'commission_group a-center v-middle sales_total_publisher_commission_subheader confirmed_subvalue'
        ));

        $this->addColumn('leads_total_publisher_commission_open', array(
            'header'    => Mage::helper('affilinet')->__('Lead Commission (Open)'),
            'index'     => 'leads/total_publisher_commission/open_value',
            'type'      => 'currency',
            'default'   => $zeroAmountString,
            'currency_code' => $this->_getCurrencyCode(),
            'sortable'  => false,
            'column_css_class' => 'commission_group',
            'header_css_class' => 'commission_group a-center v-middle leads_total_publisher_commission_subheader open_subvalue'
        ));

        $this->addColumn('leads_total_publisher_commission_confirmed', array(
            'header'    => Mage::helper('affilinet')->__('Lead Commission (Confirmed)'),
            'index'     => 'leads/total_publisher_commission/confirmed_value',
            'type'      => 'currency',
            'default'   => $zeroAmountString,
            'currency_code' => $this->_getCurrencyCode(),
            'sortable'  => false,
            'column_css_class' => 'commission_group',
            'header_css_class' => 'commission_group a-center v-middle leads_total_publisher_commission_subheader confirmed_subvalue'
        ));

        /**
         * Summary section
         */
        $this->addColumn('net_price_summary_open', array(
            'header'    => Mage::helper('affilinet')->__('Total Revenue (Open)'),
            'index'     => 'net_price_summary/open_value',
            'type'      => 'currency',
            'default'   => $zeroAmountString,
            'currency_code' => $this->_getCurrencyCode(),
            'sortable'  => false,
            'column_css_class' => 'summary_group',
            'header_css_class' => 'summary_group a-center v-middle net_price_summary_subheader open_subvalue'
        ));

        $this->addColumn('net_price_summary_confirmed', array(
            'header'    => Mage::helper('affilinet')->__('Total Revenue (Confirmed)'),
            'index'     => 'net_price_summary/confirmed_value',
            'type'      => 'currency',
            'default'   => $zeroAmountString,
            'currency_code' => $this->_getCurrencyCode(),
            'sortable'  => false,
            'column_css_class' => 'summary_group',
            'header_css_class' => 'summary_group a-center v-middle net_price_summary_subheader confirmed_subvalue'
        ));

        $this->addColumn('publisher_commission_summary_open', array(
            'header'    => Mage::helper('affilinet')->__('Sum of commissions (Open)'),
            'index'     => 'publisher_commission_summary/open_value',
            'type'      => 'currency',
            'default'   => $zeroAmountString,
            'currency_code' => $this->_getCurrencyCode(),
            'sortable'  => false,
            'column_css_class' => 'summary_group',
            'header_css_class' => 'summary_group a-center v-middle publisher_commission_summary_subheader open_subvalue'
        ));

        $this->addColumn('publisher_commission_summary_confirmed', array(
            'header'    => Mage::helper('affilinet')->__('Sum of commissions (Confirmed)'),
            'index'     => 'publisher_commission_summary/confirmed_value',
            'type'      => 'currency',
            'default'   => $zeroAmountString,
            'currency_code' => $this->_getCurrencyCode(),
            'sortable'  => false,
            'column_css_class' => 'summary_group',
            'header_css_class' => 'summary_group a-center v-middle publisher_commission_summary_subheader confirmed_subvalue'
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return null;
    }

    public function decorateUrl($value, $row, $column, $isExport) {
        if ($value) {
            return sprintf('<a target="_blank" href="%s">%s</a>', $value, $value);
        }
        return $value;
    }

}
