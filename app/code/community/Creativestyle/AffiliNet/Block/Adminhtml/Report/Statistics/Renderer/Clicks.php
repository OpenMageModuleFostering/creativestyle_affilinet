<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Report_Statistics_Renderer_Clicks extends Creativestyle_AffiliNet_Block_Adminhtml_Report_Statistics_Renderer_Abstract {

    protected $_indexPrefix = 'clicks';

    protected function _getValue(Varien_Object $row) {
        switch ($this->_getSubIndex()) {
            case 'through':
                if ($this->_getViewsGrossCount($row) != 0) {
                    return round($this->_getClicksNetCount($row) / $this->_getViewsGrossCount($row) * 100) . '%';
                }
                break;
        }
        return Mage::helper('affilinet')->__('n/a');
    }

}
