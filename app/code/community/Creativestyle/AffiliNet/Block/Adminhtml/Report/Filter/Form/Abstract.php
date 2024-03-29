<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
abstract class Creativestyle_AffiliNet_Block_Adminhtml_Report_Filter_Form_Abstract extends Mage_Adminhtml_Block_Widget_Form {

    protected $_store = null;

    protected function _prepareForm() {
        $form = new Varien_Data_Form(array(
            'id' => 'filter_form',
            'action' => $this->getUrl('*/*/'),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $form->setUseContainer(true);
        $form->setHtmlIdPrefix('affilinet_filter_');
        $this->setForm($form);
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('affilinet')->__('Filter criteria')));

        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $fieldset->addField('period', 'select', array(
            'name'      => 'period',
            'required'  => true,
            'label'     => Mage::helper('affilinet')->__('Period'),
            'title'     => Mage::helper('affilinet')->__('Period'),
            'values'    => Mage::getSingleton('affilinet/api_source_period_type')->toOptionArray()
        ));

        $fieldset->addField('quarter', 'select', array(
            'name'      => 'quarter',
            'required'  => true,
            'label'     => Mage::helper('affilinet')->__('Quarter'),
            'title'     => Mage::helper('affilinet')->__('Quarter'),
            'values'    => Mage::getSingleton('affilinet/api_source_period')->setPeriodType(Creativestyle_AffiliNet_Model_Api_Source_Period_Type::QUARTER)->toOptionArray(),
            'note'      => Mage::helper('affilinet')->__('Select reporting period')
        ));

        $fieldset->addField('month', 'select', array(
            'name'      => 'month',
            'required'  => true,
            'label'     => Mage::helper('affilinet')->__('Month'),
            'title'     => Mage::helper('affilinet')->__('Month'),
            'values'    => Mage::getSingleton('affilinet/api_source_period')->setPeriodType(Creativestyle_AffiliNet_Model_Api_Source_Period_Type::MONTH)->toOptionArray(),
            'note'      => Mage::helper('affilinet')->__('Select reporting period')
        ));

        $fieldset->addField('week', 'select', array(
            'name'      => 'week',
            'required'  => true,
            'label'     => Mage::helper('affilinet')->__('Calendar week'),
            'title'     => Mage::helper('affilinet')->__('Calendar week'),
            'values'    => Mage::getSingleton('affilinet/api_source_period')->setPeriodType(Creativestyle_AffiliNet_Model_Api_Source_Period_Type::WEEK)->toOptionArray(),
            'note'      => Mage::helper('affilinet')->__('Select reporting period')
        ));

        $fieldset->addField('from', 'date', array(
            'name'      => 'from',
            'required'  => true,
            'format'    => $dateFormatIso,
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'label'     => Mage::helper('affilinet')->__('From'),
            'title'     => Mage::helper('affilinet')->__('From')
        ));

        $fieldset->addField('to', 'date', array(
            'name'      => 'to',
            'format'    => $dateFormatIso,
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'label'     => Mage::helper('affilinet')->__('To'),
            'title'     => Mage::helper('affilinet')->__('To'),
            'note'      => Mage::helper('affilinet')->__('Time span between dates cannot exceed 3 months')
        ));

        $fieldset->addField('channel1', 'select', array(
            'name'      => 'channel1',
            'label'     => Mage::helper('affilinet')->__('Channel'),
            'title'     => Mage::helper('affilinet')->__('Channel'),
            'values'    => Mage::getSingleton('affilinet/api_source_channel')->setStore($this->_store)
                ->setChannelGroup(Creativestyle_AffiliNet_Model_Api_Source_Channel::GROUP1)->toOptionArray()
        ));

        $fieldset->addField('channel2', 'select', array(
            'name'      => 'channel2',
            'label'     => null,
            'title'     => Mage::helper('affilinet')->__('Channel'),
            'values'    => Mage::getSingleton('affilinet/api_source_channel')->setStore($this->_store)
                ->setChannelGroup(Creativestyle_AffiliNet_Model_Api_Source_Channel::GROUP2)->toOptionArray()
        ));

        $fieldset->addField('publisher', 'select', array(
            'name'      => 'publisher',
            'label'     => Mage::helper('affilinet')->__('Publisher'),
            'title'     => Mage::helper('affilinet')->__('Publisher'),
            'values'    => Mage::getSingleton('affilinet/api_source_publisherFilter')->toOptionArray()
        ));

        $fieldset->addField('publisher_filter', 'text', array(
            'name'      => 'publisher_filter',
            'label'     => null,
            'title'     => Mage::helper('affilinet')->__('Publisher filter'),
            'note'      => Mage::helper('affilinet')->__('Enter publisher filter string')
        ));

        $fieldset->addField('publisher_segment', 'select', array(
            'name'      => 'publisher_segment',
            'label'     => Mage::helper('affilinet')->__('Publisher segment'),
            'title'     => Mage::helper('affilinet')->__('Publisher segment'),
            'values'    => Mage::getSingleton('affilinet/api_source_publisherSegment')->setStore($this->_store)->toOptionArray()
        ));

        $block = $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($form->getHtmlIdPrefix() . 'period', 'period')
            ->addFieldMap($form->getHtmlIdPrefix() . 'quarter', 'quarter')
            ->addFieldMap($form->getHtmlIdPrefix() . 'month', 'month')
            ->addFieldMap($form->getHtmlIdPrefix() . 'week', 'week')
            ->addFieldMap($form->getHtmlIdPrefix() . 'from', 'from')
            ->addFieldMap($form->getHtmlIdPrefix() . 'to', 'to')
            ->addFieldMap($form->getHtmlIdPrefix() . 'publisher', 'publisher')
            ->addFieldMap($form->getHtmlIdPrefix() . 'publisher_filter', 'publisher_filter')
            ->addFieldDependence('quarter', 'period', Creativestyle_AffiliNet_Model_Api_Source_Period_Type::QUARTER)
            ->addFieldDependence('month', 'period', Creativestyle_AffiliNet_Model_Api_Source_Period_Type::MONTH)
            ->addFieldDependence('week', 'period', Creativestyle_AffiliNet_Model_Api_Source_Period_Type::WEEK)
            ->addFieldDependence('from', 'period', Creativestyle_AffiliNet_Model_Api_Source_Period_Type::TIME_SPAN)
            ->addFieldDependence('to', 'period', Creativestyle_AffiliNet_Model_Api_Source_Period_Type::TIME_SPAN);
        if(version_compare(Mage::getVersion(), '1.6.0.0.', '<')===true){
            $block->addFieldDependence('publisher_filter', 'publisher', Creativestyle_AffiliNet_Model_Api_Source_PublisherFilter::getAllPublisherFilters());
        }

        $this->setChild('form_after', $block);

        return parent::_prepareForm();
    }

    /**
     * Initialize form fields values
     *
     * @return Creativestyle_AffiliNet_Block_Adminhtml_Report_Filter_Form_Abstract
     */
    protected function _initFormValues()
    {
        $data = $this->getFilterData()->getData();
        $this->getForm()->addValues($data);
        return parent::_initFormValues();
    }

    public function setStore($store) {
        $this->_store = $store;
        return $this;
    }

}
