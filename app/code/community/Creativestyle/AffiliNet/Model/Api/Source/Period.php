<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Source_Period extends Creativestyle_AffiliNet_Model_Api_Source_Abstract {

    protected $_periodType = Creativestyle_AffiliNet_Model_Api_Source_Period_Type::MONTH;
    protected $_dateModel = null;
    protected $_startDate = null;
    protected $_endDate = null;

    protected function _date($format, $timestamp) {
        return $this->_getDateModel()->date($format, $this->_getDateModel()->gmtTimestamp($timestamp));
    }

    protected function _getDateModel() {
        if (null === $this->_dateModel) {
            $this->_dateModel = Mage::getModel('core/date');
        }
        return $this->_dateModel;
    }

    protected function _getStartDate() {
        if (null === $this->_startDate) {
            $orderCollection = Mage::getModel('sales/order')->getCollection();
            if (null !== $this->_store) {
                $orderCollection->addAttributeToFilter('store_id', $this->_store);
            }
            $orderCollection->setPageSize(1)
                ->addAttributeToSort('created_at', 'ASC')
                ->load();
            if ($orderCollection->count()) {
                $this->_startDate = $this->_getDateModel()->timestamp($orderCollection->getFirstItem()->getCreatedAt());
            } else {
                $this->_startDate = $this->_getDateModel()->timestamp();
            }
        }
        return $this->_startDate;
    }

    protected function _getEndDate() {
        if (null === $this->_endDate) {
            $this->_endDate = $this->_getDateModel()->timestamp();
        }
        return $this->_endDate;
    }

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array();
        }
        if (!array_key_exists($this->_periodType, $this->_options)) {
            $startYear = $this->_date("Y", $this->_getStartDate());
            $endYear = $this->_date("Y", $this->_getEndDate());
            $this->_options[$this->_periodType] = array();
            switch ($this->_periodType) {
                case Creativestyle_AffiliNet_Model_Api_Source_Period_Type::QUARTER:
                    $startMonth = $this->_date('n', $this->_getStartDate());
                    $endMonth = $this->_date('n', $this->_getEndDate());
                    $startQuarter = 1 + ($startMonth - $startMonth % 3) / 3;
                    $endQuarter = 1 + ($endMonth - $endMonth % 3) / 3;
                    for ($_year = $endYear; $_year >= $startYear; $_year--) {
                        if ($_year == $startYear && $_year == $endYear) {
                            $start = $endQuarter;
                            $end = $startQuarter;
                        } elseif ($_year == $endYear) {
                            $start = $endQuarter;
                            $end = 1;
                        } elseif ($_year == $startYear) {
                            $start = 4;
                            $end = $startQuarter;
                        } else {
                            $start = 4;
                            $end = 1;
                        }
                        for ($_quarter = $start; $_quarter >= $end; $_quarter--) {
                            $this->_options[$this->_periodType][] = array(
                                'value' => $_year . 'Q' . $_quarter,
                                'label' => Mage::helper('affilinet')->__('Q') . $_quarter . ' ' . $_year
                            );
                        }
                    }
                    break;
                case Creativestyle_AffiliNet_Model_Api_Source_Period_Type::MONTH:
                    $startMonth = $this->_date('n', $this->_getStartDate());
                    $endMonth = $this->_date('n', $this->_getEndDate());
                    for ($_year = $endYear; $_year >= $startYear; $_year--) {
                        if ($_year == $startYear && $_year == $endYear) {
                            $start = $endMonth;
                            $end = $startMonth;
                        } elseif ($_year == $endYear) {
                            $start = $endMonth;
                            $end = 1;
                        } elseif ($_year == $startYear) {
                            $start = 12;
                            $end = $startMonth;
                        } else {
                            $start = 12;
                            $end = 1;
                        }
                        for ($_month = $start; $_month >= $end; $_month--) {
                            $this->_options[$this->_periodType][] = array(
                                'value' => $_year . sprintf('%02d', $_month),
                                'label' => Mage::helper('affilinet')->__($this->_date('F', $_year . '-' . sprintf('%02d', $_month) . '-01')) . ' ' . $_year
                            );
                        }
                    }
                    break;
                case Creativestyle_AffiliNet_Model_Api_Source_Period_Type::WEEK:
                default:
                    $startWeek = $this->_date('W', $this->_getStartDate());
                    $endWeek = $this->_date('W', $this->_getEndDate());
                    for ($_year = $endYear; $_year >= $startYear; $_year--) {
                        if ($_year == $startYear && $_year == $endYear) {
                            $start = $endWeek;
                            $end = $startWeek;
                        } elseif ($_year == $endYear) {
                            $start = $endWeek;
                            $end = 1;
                        } elseif ($_year == $startYear) {
                            $start = 52;
                            $end = $startWeek;
                        } else {
                            $start = 52;
                            $end = 1;
                        }
                        for ($_week = $start; $_week >= $end; $_week--) {
                            $this->_options[$this->_periodType][] = array(
                                'value' => $_year . 'W' . sprintf('%02d', $_week),
                                'label' => Mage::helper('affilinet')->__('CW') . $_week . ' ' . $_year
                            );
                        }
                    }
                    break;
            }
        }
        return $this->_options[$this->_periodType];
    }

    public function setPeriodType($periodType) {
        if (in_array($periodType, Creativestyle_AffiliNet_Model_Api_Source_Period_Type::getAllPeriodTypes())) {
            $this->_periodType = $periodType;
        }
        return $this;
    }

}
