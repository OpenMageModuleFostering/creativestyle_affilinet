<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_System_Config_Source_Platform extends Creativestyle_AffiliNet_Model_System_Config_Source_Abstract {

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array();
            foreach (Mage::getSingleton('affilinet/config')->getAllPlatformsData() as $platform => $platformData) {
                $this->_options[] = array(
                    'value' => $platform,
                    'label' => Mage::helper('affilinet')->__(array_key_exists('label', $platformData) ? $platformData['label'] : $platform)
                );
            }
        }
        return $this->_options;
    }

}
