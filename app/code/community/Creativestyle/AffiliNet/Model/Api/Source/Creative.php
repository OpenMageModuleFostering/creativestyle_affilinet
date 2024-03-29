<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Source_Creative extends Creativestyle_AffiliNet_Model_Api_Source_Abstract {

    protected $_creativeType = Creativestyle_AffiliNet_Model_Api_Source_CreativeType::TEXT;

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array();
        }
        if (!array_key_exists($this->_creativeType, $this->_options)) {
            $creativeCollection = Mage::getModel('affilinet/api_model_creative')->getCollection()->addFilter('creative_type', $this->_creativeType)->setStore($this->_store);
            $this->_options[$this->_creativeType] = $creativeCollection->toOptionArray();
            switch ($this->_creativeType) {
                case Creativestyle_AffiliNet_Model_Api_Source_CreativeType::TEXT:
                    array_unshift($this->_options[$this->_creativeType], array(
                        'value' => '',
                        'label' => Mage::helper('affilinet')->__('All text links')
                    ));
                    break;
                case Creativestyle_AffiliNet_Model_Api_Source_CreativeType::BANNER:
                    array_unshift($this->_options[$this->_creativeType], array(
                        'value' => '',
                        'label' => Mage::helper('affilinet')->__('All banners')
                    ));
                    break;
                case Creativestyle_AffiliNet_Model_Api_Source_CreativeType::HTML:
                    array_unshift($this->_options[$this->_creativeType], array(
                        'value' => '',
                        'label' => Mage::helper('affilinet')->__('All HTML links')
                    ));
                    break;
            }
        }
        return $this->_options[$this->_creativeType];
    }

    public function setCreativeType($creativeType) {
        if (in_array($creativeType, Creativestyle_AffiliNet_Model_Api_Source_CreativeType::getAllCreativeTypes())) {
            $this->_creativeType = $creativeType;
        }
        return $this;
    }

}
