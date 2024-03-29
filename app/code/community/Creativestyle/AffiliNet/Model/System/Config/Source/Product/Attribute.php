<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_System_Config_Source_Product_Attribute extends Creativestyle_AffiliNet_Model_System_Config_Source_Abstract {

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array();
            $attributeCollection = Mage::getResourceModel('catalog/product_attribute_collection')
                ->addFieldToFilter('frontend_input', array('nin' => array('media_image', 'hidden', 'gallery')))
                ->addVisibleFilter();
            $attributeCollection->getSelect()->order(array('frontend_label ASC', 'attribute_code ASC'));
            $attributeCollection->load();
            foreach ($attributeCollection as $_attribute) {
                $this->_options[] = array(
                    'value' => $_attribute->getAttributeCode(),
                    'label' => $_attribute->getFrontendLabel() ? $_attribute->getFrontendLabel() : $_attribute->getAttributeCode()
                );
            }
            array_unshift($this->_options, array(
                'value' => '',
                'label' => Mage::helper('affilinet')->__('-- Please Select --'))
            );
        }
        return $this->_options;
    }
}
