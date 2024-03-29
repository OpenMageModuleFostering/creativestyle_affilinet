<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Report_Statistics_Renderer_Abstract extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    protected $_indexPrefix = 'abstract';

    protected function _getStatus() {
        $indexName = $this->getColumn()->getIndex();
        $explodedIndexName = explode('_', $indexName);
        return array_pop($explodedIndexName);
    }

    protected function _getSubIndex($status = null) {
        // remove prefix from the index
        $pattern = array($this->_indexPrefix . '_');
        // ... and status if set
        if ($status) {
            $pattern[] = '_' . $status;
        }
        return str_replace($pattern, '', $this->getColumn()->getIndex());
    }

    protected function _getViewsGrossCount(Varien_Object $row) {
        return (int)$row->getViews()->getGrossCount();
    }

    protected function _getClicksNetCount(Varien_Object $row) {
        return (int)$row->getClicks()->getNetCount();
    }

    protected function _getSalesOpenCount(Varien_Object $row) {
        return (int)$row->getSales()->getTotalCount()->getOpenCount();
    }

    protected function _getSalesConfirmedCount(Varien_Object $row) {
        return (int)$row->getSales()->getTotalCount()->getConfirmedCount();
    }

    protected function _getSalesCancelledCount(Varien_Object $row) {
        return (int)$row->getSales()->getTotalCount()->getCancelledCount();
    }

    protected function _getSalesCount(Varien_Object $row, $status) {
        switch ($status) {
            case 'open':
                return $this->_getSalesOpenCount($row);
            case 'confirmed':
                return $this->_getSalesConfirmedCount($row);
            case 'cancelled':
                return $this->_getSalesCancelledCount($row);
        }
        return null;
    }

    protected function _getLeadsOpenCount(Varien_Object $row) {
        return (int)$row->getLeads()->getTotalCount()->getOpenCount();
    }

    protected function _getLeadsConfirmedCount(Varien_Object $row) {
        return (int)$row->getLeads()->getTotalCount()->getConfirmedCount();
    }

    protected function _getLeadsCancelledCount(Varien_Object $row) {
        return (int)$row->getLeads()->getTotalCount()->getCancelledCount();
    }

    protected function _getLeadsCount(Varien_Object $row, $status) {
        switch ($status) {
            case 'open':
                return $this->_getLeadsOpenCount($row);
            case 'confirmed':
                return $this->_getLeadsConfirmedCount($row);
            case 'cancelled':
                return $this->_getLeadsCancelledCount($row);
        }
        return null;
    }

    public function renderCss() {
        return parent::renderCss() . ' a-right';
    }

}