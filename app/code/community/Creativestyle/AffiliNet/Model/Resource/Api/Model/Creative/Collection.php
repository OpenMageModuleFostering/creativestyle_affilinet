<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Resource_Api_Model_Creative_Collection extends Creativestyle_AffiliNet_Model_Resource_Api_Model_Collection_Abstract {

    /**
     * Define resource model
     *
     */
    public function __construct() {
        $this->_init('affilinet/api_model_creative');
    }

    protected function _getData() {
        $data = array();
        if ($creativeType = $this->getFilter('creative_type')) {
            $soapResponse = $this->_getApi()->getCreativesPerType($creativeType->getValue());
            if (is_object($soapResponse)) {
                if (is_object($soapResponse->CreativeCollection) && isset($soapResponse->CreativeCollection->CreativeCollection)) {
                    $data['data'] = $soapResponse->CreativeCollection->CreativeCollection;
                }
                $data['additional'] = $this->_extractAdditionalDataFromSoapResponse($soapResponse, array('CreativeCollection'));
            }
        }
        return $data;
    }

    public function toOptionArray() {
        return parent::_toOptionArray('creative_number', 'creative_name');
    }

    public function toOptionHash() {
        return parent::_toOptionHash('creative_number', 'creative_name');
    }

}
