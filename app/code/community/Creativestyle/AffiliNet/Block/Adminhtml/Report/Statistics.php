<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Report_Statistics extends Creativestyle_AffiliNet_Block_Adminhtml_Report_Container_Abstract {

    protected $_controller = 'adminhtml_report_statistics';

    public function __construct() {
        parent::__construct();
        $this->_headerText = Mage::helper('affilinet')->__('Statistics');
        $this->_updateButton('show', 'label', Mage::helper('affilinet')->__('Show statistics'));
    }
}
