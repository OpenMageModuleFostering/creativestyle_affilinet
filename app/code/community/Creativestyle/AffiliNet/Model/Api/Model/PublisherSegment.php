<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Model_PublisherSegment extends Creativestyle_AffiliNet_Model_Api_Model_Abstract {

    protected function _construct() {
        $this->_init('affilinet/api_model_publisherSegment_collection', 'segment_id');
    }

}
