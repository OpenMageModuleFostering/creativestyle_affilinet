<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Api_Client_Orders extends Creativestyle_AffiliNet_Model_Api_Client_Abstract {

    /**
     * Perform GetOrders SOAP call
     *
     * @param  string  $programId   affilinet program ID
     * @param  string  $startDate   Start date in YYYY-MM-DD format
     * @param  string  $endDate     End date in YYYY-MM-DD format
     * @param  array   $params      Optional call parameters
     * @param  integer $currentPage Results page number
     * @param  integer $pageSize    Number of orders returned in a single call
     * @return mixed                GetOrders SOAP response or fault object
     */
    public function getOrders($programId, $startDate, $endDate, $params = array(), $currentPage = 1, $pageSize = 20) {

        $args = array(
            'ProgramId' => $programId,
            'StartDate' => $this->_getFormattedDate($startDate),
            'EndDate' => $this->_getFormattedDate($endDate),
            'Page' => $currentPage,
            'PageSize' => $pageSize
        );

        if (array_key_exists('transaction_status', $params)) {
            $args['TransactionStatus'] = $params['transaction_status'];
        } else {
            $args['TransactionStatus'] = Creativestyle_AffiliNet_Model_Api_Source_TransactionStatus::ALL;
        }

        if (array_key_exists('evaluation_type', $params)) {
            $args['ValuationType'] = $params['evaluation_type'];
        } else {
            $args['ValuationType'] = Creativestyle_AffiliNet_Model_Api_Source_EvaluationType::ORDERS_REGISTERED;
        }

        if (array_key_exists('cancellation_reason', $params)) {
            $args['CancellationReason'] = $params['cancellation_reason'];
        }

        if (array_key_exists('order_id', $params)) {
            $args['OrderId'] = $params['order_id'];
        }

        if (array_key_exists('cart_ids', $params)) {
            $args['BasketIds'] = $params['cart_ids'];
        }

        if (array_key_exists('transaction_ids', $params)) {
            $args['TransactionIds'] = $params['transaction_ids'];
        }

        if (array_key_exists('channel1', $params)) {
            $args['Channel1'] = $params['channel1'];
        }

        if (array_key_exists('channel2', $params)) {
            $args['Channel2'] = $params['channel2'];
        }

        if (array_key_exists('publisher_ids', $params)) {
            $args['PublisherIds'] = $params['publisher_ids'];
        }

        if (array_key_exists('publisher_segment', $params)) {
            $args['PublisherSegment'] = $params['publisher_segment'];
        }

        return $this->_getApi()->getOrders($args);
    }

    public function getBasketItems($programId, $basketId = null, $orderId = null, $currentPage = 1, $pageSize = 20) {
        return $this->_getApi()->getBasketItems(array(
            'BasketItemsQuery' => array(
                'ProgramId' => $programId,
                'BasketId' => $basketId,
                'OrderId' => $orderId
            ),
            'DisplaySettings' => array(
                'CurrentPage' => $currentPage,
                'PageSize' => $pageSize
            )
        ));
    }

    public function updateBasketItem($programId, $basketId, $positionNumber, $params = array()) {
        $args = array(
            'ProgramId' => $programId,
            'BasketId' => $basketId,
            'PositionNumber' => $positionNumber
        );

        if (array_key_exists('quantity', $params)) {
            $args['Quantity'] = $params['quantity'];
        }

        if (array_key_exists('cancellation_reason', $params)) {
            $args['CancellationReason'] = $params['cancellation_reason'];
        }

        return $this->_getApi()->updateBasketItem($args);
    }

    public function updateBasket($programId, $basketId, $action, $params = array()) {
        $args = array(
            'ProgramId' => $programId,
            'BasketId' => $basketId,
            'Action' => $action
        );

        if (array_key_exists('cancellation_reason', $params)) {
            $args['CancellationReason'] = $params['cancellation_reason'];
        }

        return $this->_getApi()->updateBasket($args);
    }

    public function updateTransaction($programId, $transactionId, $action, $params = array()) {
        $args = array(
            'ProgramId' => $programId,
            'TransactionId' => $transactionId,
            'Action' => $action
        );

        if (array_key_exists('cancellation_reason', $params)) {
            $args['CancellationReason'] = $params['cancellation_reason'];
        }

        if (array_key_exists('new_net_price', $params)) {
            $args['NewNetPrice'] = $params['new_net_price'];
        }

        return $this->_getApi()->updateTransaction($args);
    }

}
