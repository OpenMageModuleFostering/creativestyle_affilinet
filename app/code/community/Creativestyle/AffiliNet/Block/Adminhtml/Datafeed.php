<?php
class Creativestyle_AffiliNet_Block_Adminhtml_Datafeed extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'affilinet';
        $this->_controller = 'adminhtml_datafeed';
        $this->_headerText = Mage::helper('affilinet')->__('Product Data Feed');
        parent::__construct();
    }
}