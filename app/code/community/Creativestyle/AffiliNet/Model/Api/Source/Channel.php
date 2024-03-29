<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Source_Channel extends Creativestyle_AffiliNet_Model_Api_Source_Abstract {

    const GROUP1 = 'ChannelGroup1';
    const GROUP2 = 'ChannelGroup2';

    protected $_channelGroup = self::GROUP1;

    public function toOptionArray() {
        if (null === $this->_options) {
            $this->_options = array();
        }
        if (!array_key_exists($this->_channelGroup, $this->_options)) {
            $channelCollection = Mage::getModel('affilinet/api_model_channel')->getCollection()->addFilter('channel_group', $this->_channelGroup)->setStore($this->_store);
            $this->_options[$this->_channelGroup] = $channelCollection->toOptionArray();
            array_unshift($this->_options[$this->_channelGroup], array(
                'value' => '',
                'label' => Mage::helper('affilinet')->__('All channels') . ($channelCollection->hasAdditionalData('ChannelGroupName') ? ' (' . $channelCollection->getAdditionalData('ChannelGroupName') .')' : '')
            ));
        }
        return $this->_options[$this->_channelGroup];
    }

    public function setChannelGroup($channelGroup) {
        if (in_array($channelGroup, self::getAllChannelGroups())) {
            $this->_channelGroup = $channelGroup;
        }
        return $this;
    }

    public static function getAllChannelGroups() {
        return array(self::GROUP1, self::GROUP2);
    }

}
