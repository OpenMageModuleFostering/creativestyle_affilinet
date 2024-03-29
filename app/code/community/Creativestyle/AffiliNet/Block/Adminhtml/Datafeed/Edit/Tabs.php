<?php

class Creativestyle_AffiliNet_Block_Adminhtml_Datafeed_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('datafeed_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('affilinet')->__('Product Data Feed'));
    }

    protected function _beforeToHtml() {
        $this->addTab('general', array(
            'label' => Mage::helper('affilinet')->__('General'),
            'title' => Mage::helper('affilinet')->__('General'),
            'content' => $this->getLayout()->createBlock('affilinet/adminhtml_datafeed_edit_tab_general')->toHtml(),
        ));

        $this->addTab('mapper', array(
            'label' => Mage::helper('affilinet')->__('Field mapper'),
            'title' => Mage::helper('affilinet')->__('Field mapper'),
            'content' => $this->getLayout()->createBlock('affilinet/adminhtml_datafeed_edit_tab_mapper')->toHtml(),
        ));

        $this->addTab('filter', array(
            'label' => Mage::helper('affilinet')->__('Field filter'),
            'title' => Mage::helper('affilinet')->__('Field filter'),
            'content' => $this->getLayout()->createBlock('affilinet/adminhtml_datafeed_edit_tab_filter')->toHtml(),
        ));

        $this->addTab('cron', array(
            'label' => Mage::helper('affilinet')->__('Cron'),
            'title' => Mage::helper('affilinet')->__('Cron'),
            'content' => $this->getLayout()->createBlock('affilinet/adminhtml_datafeed_edit_tab_cron')->toHtml(),
        ));

        $this->addTab('submit', array(
            'label' => Mage::helper('affilinet')->__('Feed submission'),
            'title' => Mage::helper('affilinet')->__('Feed submission'),
            'content' => $this->getLayout()->createBlock('affilinet/adminhtml_datafeed_edit_tab_submit')->toHtml(),
        ));


        return parent::_beforeToHtml();
    }

}