<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Report_Statistics_Renderer_Sales extends Creativestyle_AffiliNet_Block_Adminhtml_Report_Statistics_Renderer_Abstract {

    protected $_indexPrefix = 'sales';

    protected function _getValue(Varien_Object $row) {
        $status = $this->_getStatus();
        switch ($this->_getSubIndex($status)) {
            case 'conversion_rate':
                if ($this->_getClicksNetCount($row) != 0) {
                    return round($this->_getSalesCount($row, $status) / $this->_getClicksNetCount($row) * 100) . '%';
                }
                break;
        }
        return Mage::helper('affilinet')->__('n/a');
    }
}
