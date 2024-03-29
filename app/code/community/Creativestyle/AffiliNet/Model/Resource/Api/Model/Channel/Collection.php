<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Resource_Api_Model_Channel_Collection extends Creativestyle_AffiliNet_Model_Resource_Api_Model_Collection_Abstract {

    /**
     * Define resource model
     *
     */
    public function __construct() {
        $this->_init('affilinet/api_model_channel');
    }

    protected function _getData() {
        $data = array();
        if ($channelGroupFilter = $this->getFilter('channel_group')) {
            $channelGroup = $channelGroupFilter->getValue();
            $soapResponse = $this->_getApi()->getChannels($channelGroup);
            if (is_object($soapResponse)) {
                if (is_object($soapResponse->ChannelCollection) && isset($soapResponse->ChannelCollection->ChannelCollection)) {
                    $data['data'] = $soapResponse->ChannelCollection->ChannelCollection;
                }
                $data['additional'] = $this->_extractAdditionalDataFromSoapResponse($soapResponse, array('ChannelCollection'));
            }
        }
        return $data;
    }

    public function toOptionArray() {
        return parent::_toOptionArray('channel_id', 'channel_name');
    }

    public function toOptionHash() {
        return parent::_toOptionHash('channel_id', 'channel_name');
    }

}
