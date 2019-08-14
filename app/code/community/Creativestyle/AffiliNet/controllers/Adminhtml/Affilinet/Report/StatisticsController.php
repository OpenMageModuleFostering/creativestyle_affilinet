<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Adminhtml_Affilinet_Report_StatisticsController extends Creativestyle_AffiliNet_Controller_Adminhtml_Report_Abstract {

    protected function _initLayout() {
        parent::_initLayout();

        $this->_addBreadcrumb($this->__('Statistics'), $this->__('Statistics'))
            ->_title($this->__('Statistics'))
            ->_setActiveMenu('affilinet/statistics');

        $gridBlock = $this->getLayout()->getBlock('adminhtml_report_statistics.grid');
        $filterFormBlock = $this->getLayout()->getBlock('affilinet.statistics.filter.form');

        $this->_initBlocks(array(
            $gridBlock,
            $filterFormBlock
        ));

        return $this;
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('admin/affilinet/statistics');
    }

}
