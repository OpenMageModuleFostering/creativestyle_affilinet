<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Model_Channel extends Creativestyle_AffiliNet_Model_Api_Model_Abstract {

    protected function _construct() {
        $this->_init('affilinet/api_model_channel_collection', 'channel_id');
    }

}
