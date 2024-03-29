<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2015 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Resource_Api_Model_Rate_Collection extends Creativestyle_AffiliNet_Model_Resource_Api_Model_Collection_Abstract {

    /**
     * Define resource model
     *
     */
    public function __construct() {
        $this->_init('affilinet/api_model_rate');
    }

    protected function _getData() {
        $data = array();
        $soapResponse = $this->_getApi()->getRateList();
        if (is_object($soapResponse)) {
            if (is_object($soapResponse->RateCollection) && isset($soapResponse->RateCollection->RateCollection)) {
                $data['data'] = $soapResponse->RateCollection->RateCollection;
            }
            $data['additional'] = $this->_extractAdditionalDataFromSoapResponse($soapResponse, array('RateCollection'));
        }
        return $data;
    }

}
