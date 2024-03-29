<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Resource_Api_Model_Order_Collection extends Creativestyle_AffiliNet_Model_Resource_Api_Model_Collection_Abstract {

    /**
     * Define resource model
     *
     */
    public function __construct() {
        $this->_init('affilinet/api_model_order');
    }

    protected function _getData() {
        $data = array();
        $startDateFilter = $this->getFilter('start_date');
        $endDateFilter = $this->getFilter('end_date');
        if ($startDateFilter && $endDateFilter) {
            // prepare SOAP parameters based on filters set on collection
            $startDate = $startDateFilter->getValue();
            $endDate = $endDateFilter->getValue();
            $currentPage = $this->_curPage ? $this->_curPage : null;
            $pageSize = $this->_pageSize ? $this->_pageSize : null;
            $params = array();

            $filters = $this->getFilter(array(
                'evaluation_type',
                'transaction_status',
                'cancellation_reason',
                'order_id',
                'cart_ids',
                'transaction_ids',
                'channel1',
                'channel2',
                'publisher_ids',
                'publisher_segment'
            ));

            foreach ($filters as $filter) {
                $params[$filter->getField()] = $filter->getValue();
            }

            // SOAP call
            $soapResponse = $this->_getApi()->getOrders($startDate, $endDate, $params, $currentPage, $pageSize);

            // process SOAP response
            if (is_object($soapResponse)) {
                if (is_object($soapResponse->OrderCollection) && isset($soapResponse->OrderCollection->Order)) {
                    $data['data'] = $soapResponse->OrderCollection->Order;
                }
                $data['additional'] = $this->_extractAdditionalDataFromSoapResponse($soapResponse, array('OrderCollection'));
            }
        }
        return $data;
    }

}
