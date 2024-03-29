<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Client_Toolbox extends Creativestyle_AffiliNet_Model_Api_Client_Abstract {

    public function getChannels($programId, $channelGroup) {
        return $this->_getApi()->GetChannels(array(
            'ChannelGroup' => $channelGroup,
            'ProgramId' => $programId
        ));
    }

    public function getCreativesPerType($programId, $creativeType) {
        return $this->_getApi()->GetCreativesPerType(array(
            'CreativeType' => $creativeType,
            'ProgramId' => $programId
        ));
    }

    public function getPublisherSegments($programId) {
        return $this->_getApi()->GetPublisherSegments(array(
            'ProgramId' => $programId
        ));
    }

    public function getRateList($programId) {
        return $this->_getApi()->GetRateList(array(
            'ProgramId' => $programId
        ));
    }

}
