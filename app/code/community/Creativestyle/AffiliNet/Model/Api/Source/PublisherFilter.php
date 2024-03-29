<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Source_PublisherFilter extends Creativestyle_AffiliNet_Model_Api_Source_Abstract {

    const ID   = 'id';
    const NAME = 'name';
    const URL  = 'url';

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array(
                array(
                    'value' => self::ID,
                    'label' => Mage::helper('affilinet')->__('By publisher ID'),
                ),
                array(
                    'value' => self::NAME,
                    'label' => Mage::helper('affilinet')->__('By name'),
                ),
                array(
                    'value' => self::URL,
                    'label' => Mage::helper('affilinet')->__('By URL'),
                )
            );
            array_unshift($this->_options, array(
                'value' => '',
                'label' => Mage::helper('affilinet')->__('All publishers')
            ));
        }
        return $this->_options;
    }

    public static function getAllPublisherFilters() {
        return array(self::ID, self::NAME, self::URL);
    }

}
