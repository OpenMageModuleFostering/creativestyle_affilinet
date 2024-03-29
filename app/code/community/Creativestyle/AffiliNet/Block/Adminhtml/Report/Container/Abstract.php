<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
abstract class Creativestyle_AffiliNet_Block_Adminhtml_Report_Container_Abstract extends Mage_Adminhtml_Block_Widget_Grid_Container {

    protected $_blockGroup = 'affilinet';
    protected $_controller = 'adminhtml_report_abstract';

    public function __construct() {
        parent::__construct();
        $this->_headerText = Mage::helper('affilinet')->__('affilinet');
        $this->_removeButton('add');
        $this->_addButton('reset', array(
            'label'     => Mage::helper('adminhtml')->__('Reset'),
            'onclick'   => 'setLocation(\'' . $this->getUrl('*/*/*', array('_current' => false, 'store' => null)) . '\')',
        ), -1);
        $this->_addButton('show', array(
            'label'     => Mage::helper('affilinet')->__('Show'),
            'onclick'   => 'filterFormSubmit();',
            'class'     => 'save',
        ), 1);
    }

}
