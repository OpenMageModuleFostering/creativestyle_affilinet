<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Client_Statistics extends Creativestyle_AffiliNet_Model_Api_Client_Abstract {

    /**
     * Perform GetStatisticsPerPublisher SOAP call
     *
     * @param  string  $programId   affilinet program ID
     * @param  string  $startDate   Start date in YYYY-MM-DD format
     * @param  string  $endDate     End date in YYYY-MM-DD format
     * @param  array   $params      Optional call parameters
     * @param  integer $currentPage Results page number
     * @param  integer $pageSize    Number of orders returned in a single call
     * @return mixed                GetStatisticsPerPublisher SOAP response or fault object
     */
    public function getStatisticsPerPublisher($programId, $startDate, $endDate, $params, $currentPage = 1, $pageSize = 20) {
        $args = array(
            'DisplaySettings' => array(
                'CurrentPage' => $currentPage,
                'PageSize' => $pageSize
            ),
            'GetStatisticsPerPublisherQuery' => array(
                'ProgramId' => $programId,
                'StartDate' => $this->_getFormattedDate($startDate),
                'EndDate' => $this->_getFormattedDate($endDate)
            )
        );

        if (array_key_exists('evaluation_type', $params)) {
            $args['GetStatisticsPerPublisherQuery']['ValuationType'] = $params['evaluation_type'];
        } else {
            $args['GetStatisticsPerPublisherQuery']['ValuationType'] = Creativestyle_AffiliNet_Model_Api_Source_EvaluationType::STATISTICS_REGISTRATION;
        }

        if (array_key_exists('channel1', $params)) {
            $args['GetStatisticsPerPublisherQuery']['Channel1'] = $params['channel1'];
        }

        if (array_key_exists('channel2', $params)) {
            $args['GetStatisticsPerPublisherQuery']['Channel2'] = $params['channel2'];
        }

        if (array_key_exists('creative_type', $params)) {
            $args['GetStatisticsPerPublisherQuery']['CreativeType'] = $params['creative_type'];
        }

        if (array_key_exists('creative_number', $params)) {
            $args['GetStatisticsPerPublisherQuery']['CreativeNumber'] = $params['creative_number'];
        }

        if (array_key_exists('publisher_id', $params)) {
            $args['GetStatisticsPerPublisherQuery']['PublisherId'] = $params['publisher_id'];
        }

        if (array_key_exists('publisher_name', $params)) {
            $args['GetStatisticsPerPublisherQuery']['PublisherName'] = $params['publisher_name'];
        }

        if (array_key_exists('publisher_url', $params)) {
            $args['GetStatisticsPerPublisherQuery']['PublisherURL'] = $params['publisher_url'];
        }

        if (array_key_exists('publisher_segment', $params)) {
            $args['GetStatisticsPerPublisherQuery']['PublisherSegment'] = $params['publisher_segment'];
        }

        if (array_key_exists('volume_min', $params)) {
            $args['GetStatisticsPerPublisherQuery']['VolumeMin'] = $params['volume_min'];
        }

        if (array_key_exists('volume_max', $params)) {
            $args['GetStatisticsPerPublisherQuery']['VolumeMax'] = $params['volume_max'];
        }

        if (array_key_exists('volume_type', $params)) {
            $args['GetStatisticsPerPublisherQuery']['VolumeType'] = $params['volume_type'];
        }

        return $this->_getApi()->GetStatisticsPerPublisher($args);
    }
}
