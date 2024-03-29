<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Resource_Api_Model_PublisherSegment_Collection extends Creativestyle_AffiliNet_Model_Resource_Api_Model_Collection_Abstract {

    /**
     * Define resource model
     *
     */
    public function __construct() {
        $this->_init('affilinet/api_model_publisherSegment');
    }

    protected function _getData() {
        $data = array();
        $soapResponse = $this->_getApi()->getPublisherSegments();
        if (is_object($soapResponse)) {
            if (is_object($soapResponse->PublisherSegmentCollection) && isset($soapResponse->PublisherSegmentCollection->PublisherSegment)) {
                $data['data'] = $soapResponse->PublisherSegmentCollection->PublisherSegment;
            }
            $data['additional'] = $this->_extractAdditionalDataFromSoapResponse($soapResponse, array('PublisherSegmentCollection'));
        }
        return $data;
    }

    public function toOptionArray() {
        return parent::_toOptionArray('segment_id', 'segment_name');
    }

    public function toOptionHash() {
        return parent::_toOptionHash('segment_id', 'segment_name');
    }

}
