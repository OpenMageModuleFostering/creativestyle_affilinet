<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Resource_Api_Model_PublisherStatistics_Collection extends Creativestyle_AffiliNet_Model_Resource_Api_Model_Collection_Abstract {

    /**
     * Define resource model
     *
     */
    public function __construct() {
        $this->_init('affilinet/api_model_publisherStatistics');
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
                'channel1',
                'channel2',
                'creative_type',
                'creative_number',
                'publisher_id',
                'publisher_name',
                'publisher_url',
                'publisher_segment',
                'volume_min',
                'volume_max',
                'volume_type'
            ));

            foreach ($filters as $filter) {
                $params[$filter->getField()] = $filter->getValue();
            }

            // SOAP call
            $soapResponse = $this->_getApi()->getStatisticsPerPublisher($startDate, $endDate, $params, $currentPage, $pageSize);

            // process SOAP response
            if (is_object($soapResponse)) {
                if (is_object($soapResponse->PublisherStatisticsRecordCollection) && isset($soapResponse->PublisherStatisticsRecordCollection->PublisherStatisticsRecord)) {
                    $data['data'] = $soapResponse->PublisherStatisticsRecordCollection->PublisherStatisticsRecord;
                }
                $data['additional'] = $this->_extractAdditionalDataFromSoapResponse($soapResponse, array('PublisherStatisticsRecordCollection'));
            }

        }
        return $data;
    }

}
