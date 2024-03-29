<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Source_PublisherSegment extends Creativestyle_AffiliNet_Model_Api_Source_Abstract {

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = Mage::getModel('affilinet/api_model_publisherSegment')->getCollection()->setStore($this->_store)->toOptionArray();
            array_unshift($this->_options, array(
                'value' => '',
                'label' => Mage::helper('affilinet')->__('All segments')
            ));
        }
        return $this->_options;
    }

}
