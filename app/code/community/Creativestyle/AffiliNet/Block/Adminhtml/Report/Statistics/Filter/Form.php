<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Report_Statistics_Filter_Form extends Creativestyle_AffiliNet_Block_Adminhtml_Report_Filter_Form_Abstract {

    protected function _prepareForm() {
        parent::_prepareForm();


        $fieldset = $this->getForm()->getElement('base_fieldset');
        $fieldset->addType('volume', Mage::getConfig()->getBlockClassName('affilinet/adminhtml_report_filter_form_field_publisherVolume'));
        $fieldset->setLegend($this->__('Filter criteria for statistics'));

        $fieldset->addField('evaluation_method', 'select', array(
            'name'      => 'evaluation_method',
            'required'  => true,
            'label'     => Mage::helper('affilinet')->__('Evaluation method'),
            'title'     => Mage::helper('affilinet')->__('Evaluation method'),
            'values'    => Mage::getSingleton('affilinet/api_source_evaluationType')->setWebservice('statistics')->toOptionArray()
        ), 'to');

        $fieldset->addField('creative_type', 'select', array(
            'name'      => 'creative_type',
            'label'     => Mage::helper('affilinet')->__('Advertising medium'),
            'title'     => Mage::helper('affilinet')->__('Advertising medium'),
            'values'    => Mage::getSingleton('affilinet/api_source_creativeType')->toOptionArray()
        ), 'channel2');

        $fieldset->addField('creative_text', 'select', array(
            'name'      => 'creative_text',
            'label'     => null,
            'title'     => Mage::helper('affilinet')->__('Text links'),
            'values'    => Mage::getSingleton('affilinet/api_source_creative')->setStore($this->_store)
                ->setCreativeType(Creativestyle_AffiliNet_Model_Api_Source_CreativeType::TEXT)->toOptionArray(),
            'note'      => Mage::helper('affilinet')->__('Select advertising medium')
        ), 'creative_type');

        $fieldset->addField('creative_banner', 'select', array(
            'name'      => 'creative_banner',
            'label'     => null,
            'title'     => Mage::helper('affilinet')->__('Banners'),
            'values'    => Mage::getSingleton('affilinet/api_source_creative')->setStore($this->_store)
                ->setCreativeType(Creativestyle_AffiliNet_Model_Api_Source_CreativeType::BANNER)->toOptionArray(),
            'note'      => Mage::helper('affilinet')->__('Select advertising medium')
        ), 'creative_text');

        $fieldset->addField('creative_html', 'select', array(
            'name'      => 'creative_html',
            'label'     => null,
            'title'     => Mage::helper('affilinet')->__('HTML links'),
            'values'    => Mage::getSingleton('affilinet/api_source_creative')->setStore($this->_store)
                ->setCreativeType(Creativestyle_AffiliNet_Model_Api_Source_CreativeType::HTML)->toOptionArray(),
            'note'      => Mage::helper('affilinet')->__('Select advertising medium')
        ), 'creative_banner');

        $fieldset->addField('publisher_volume', 'volume', array(
            'name'      => 'publisher_volume',
            'label'     => Mage::helper('affilinet')->__('Publisher volume'),
            'title'     => Mage::helper('affilinet')->__('Publisher volume'),
            'values'    => Mage::getSingleton('affilinet/api_source_volumeType')->toOptionArray()
        ), 'publisher_segment');

        if (!$this->getChild('form_after')) {
            $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence'));
        }

        $this->getChild('form_after')
            ->addFieldMap($this->getForm()->getHtmlIdPrefix() . 'creative_type', 'creative_type')
            ->addFieldMap($this->getForm()->getHtmlIdPrefix() . 'creative_text', 'creative_text')
            ->addFieldMap($this->getForm()->getHtmlIdPrefix() . 'creative_banner', 'creative_banner')
            ->addFieldMap($this->getForm()->getHtmlIdPrefix() . 'creative_html', 'creative_html')
            ->addFieldDependence('creative_text', 'creative_type', Creativestyle_AffiliNet_Model_Api_Source_CreativeType::TEXT)
            ->addFieldDependence('creative_banner', 'creative_type', Creativestyle_AffiliNet_Model_Api_Source_CreativeType::BANNER)
            ->addFieldDependence('creative_html', 'creative_type', Creativestyle_AffiliNet_Model_Api_Source_CreativeType::HTML);

        return $this;
    }

}
