<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2015 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Model_Rate extends Creativestyle_AffiliNet_Model_Api_Model_Abstract {

    protected function _construct() {
        $this->_init('affilinet/api_model_rate_collection', array('rate_mode', 'rate_number'));
    }

    public function getRateSymbol() {
        if ($this->hasData('rate_mode')) {
            switch ($this->getData('rate_mode')) {
                case 'Sale':
                    return 'pps-' . $this->getData('rate_number');
                case 'Lead':
                    return 'ppl-' . $this->getData('rate_number');
            }
        }
        return null;
    }
}
