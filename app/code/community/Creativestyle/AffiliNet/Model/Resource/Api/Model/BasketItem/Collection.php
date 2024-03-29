<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Resource_Api_Model_BasketItem_Collection extends Creativestyle_AffiliNet_Model_Resource_Api_Model_Collection_Abstract {

    /**
     * Define resource model
     *
     */
    public function __construct() {
        $this->_init('affilinet/api_model_basketItem');
    }

    protected function _getData() {
        $data = array();
        $basketId = $this->getFilter('basket_id')->getValue();

        $soapResponse = $this->_getApi()->getBasketItems($basketId);
        if (is_object($soapResponse)) {
            if (is_object($soapResponse->BasketItemCollection) && isset($soapResponse->BasketItemCollection->BasketItem)) {
                $data['data'] = $soapResponse->BasketItemCollection->BasketItem;
            }
            $data['additional'] = $this->_extractAdditionalDataFromSoapResponse($soapResponse, array('BasketItemCollection'));


        }
        return $data;
    }

}
