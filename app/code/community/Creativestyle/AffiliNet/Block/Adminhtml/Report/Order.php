<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Report_Order extends Creativestyle_AffiliNet_Block_Adminhtml_Report_Container_Abstract {

    protected $_controller = 'adminhtml_report_order';

    public function __construct() {
        parent::__construct();
        $this->_headerText = Mage::helper('affilinet')->__('Orders');
        $this->_updateButton('show', 'label', Mage::helper('affilinet')->__('Show orders'));
    }

    public function getBasketInfoUrl() {
        return $this->getUrl('*/*/getBasketInfo');
    }

    public function getSaveBasketItemsUrl() {
        return $this->getUrl('*/*/saveBasketItems');
    }

    public function getSaveBasketsUrl() {
        return $this->getUrl('*/*/saveBaskets');
    }

    public function getStoreId() {
        $grid = $this->getChild('grid');
        if ($grid) {
            return $grid->getStore();
        }
        return null;
    }

}
