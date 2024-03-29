<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
abstract class Creativestyle_AffiliNet_Block_Adminhtml_Report_Abstract extends Mage_Adminhtml_Block_Widget_Grid {

    protected $_store = null;

    protected $_currencyCode = null;

    public function __construct() {
        parent::__construct();
        $this->setFilterVisibility(false);
        $this->setSaveParametersInSession(true);
        $this->setEmptyCellLabel(Mage::helper('affilinet')->__('No records found for these criteria.'));
    }

    protected function _getCurrencyCode() {
        if (null === $this->_currencyCode) {
            $this->_currencyCode = Mage::getSingleton('affilinet/config')->getPlatformCurrency($this->_store);
        }
        return $this->_currencyCode;
    }

    protected function _prepareCollection() {
        $filterData = $this->getFilterData()->getData();
        if (empty($filterData)) {
            $this->setCollection(new Varien_Data_Collection());
            return $this;
        }
        $collection = $this->_getModel()->getCollection()->setStore($this->_store);
        $this->setCollection($collection);
        $this->_addFilters()->_preparePage();
        return $this;
    }

    protected function _addFilters() {
        $filterData = $this->getFilterData()->getData();
        if ($this->getCollection() && !(empty($filterData))) {
            foreach ($filterData as $param => $value) {
                switch (strtolower($param)) {
                    case 'period':
                        switch ($value) {
                            case Creativestyle_AffiliNet_Model_Api_Source_Period_Type::QUARTER:
                                if ($this->getFilterData()->getData('quarter')) {
                                    $dates = $this->helper('affilinet')->convertPeriodToDateRange($this->getFilterData()->getData('quarter'));
                                    $this->getCollection()->addFilter('start_date', $dates->getStartDate());
                                    $this->getCollection()->addFilter('end_date', $dates->getEndDate());
                                }
                                break;
                            case Creativestyle_AffiliNet_Model_Api_Source_Period_Type::MONTH:
                                if ($this->getFilterData()->getData('month')) {
                                    $dates = $this->helper('affilinet')->convertPeriodToDateRange($this->getFilterData()->getData('month'));
                                    $this->getCollection()->addFilter('start_date', $dates->getStartDate());
                                    $this->getCollection()->addFilter('end_date', $dates->getEndDate());
                                }
                                break;
                            case Creativestyle_AffiliNet_Model_Api_Source_Period_Type::WEEK:
                                if ($this->getFilterData()->getData('week')) {
                                    $dates = $this->helper('affilinet')->convertPeriodToDateRange($this->getFilterData()->getData('week'));
                                    $this->getCollection()->addFilter('start_date', $dates->getStartDate());
                                    $this->getCollection()->addFilter('end_date', $dates->getEndDate());
                                }
                                break;
                            case Creativestyle_AffiliNet_Model_Api_Source_Period_Type::TIME_SPAN:
                                if ($this->getFilterData()->getData('from')) {
                                    $this->getCollection()->addFilter('start_date', $this->getFilterData()->getData('from'));
                                    if ($this->getFilterData()->getData('to')) {
                                        $this->getCollection()->addFilter('end_date', $this->getFilterData()->getData('to'));
                                    }
                                }
                                break;
                        }
                        break;
                    case 'evaluation_method':
                        $this->getCollection()->addFilter('evaluation_type', $value);
                        break;
                    case 'channel1':
                        $this->getCollection()->addFilter('channel1', $value);
                        break;
                    case 'channel2':
                        $this->getCollection()->addFilter('channel2', $value);
                        break;
                    case 'publisher':
                        if ($this->getFilterData()->getData('publisher_filter')) {
                            switch ($value) {
                                case Creativestyle_AffiliNet_Model_Api_Source_PublisherFilter::ID:
                                    $this->getCollection()->addFilter('publisher_id', $this->getFilterData()->getData('publisher_filter'));
                                    $this->getCollection()->addFilter('publisher_ids',
                                        explode(',', trim($this->getFilterData()->getData('publisher_filter')))
                                    );
                                    break;
                                case Creativestyle_AffiliNet_Model_Api_Source_PublisherFilter::NAME:
                                    $this->getCollection()->addFilter('publisher_name', $this->getFilterData()->getData('publisher_filter'));
                                    break;
                                case Creativestyle_AffiliNet_Model_Api_Source_PublisherFilter::URL:
                                    $this->getCollection()->addFilter('publisher_url', $this->getFilterData()->getData('publisher_filter'));
                                    break;
                            }
                        }
                        break;
                    case 'publisher_segment':
                        $this->getCollection()->addFilter('publisher_segment', $value);
                        break;
                }
            }
        }
        return $this;
    }

    public function setStore($store) {
        $this->_store = $store;
        return $this;
    }

    public function getStore() {
        return $this->_store;
    }

    abstract protected function _getModel();

}
