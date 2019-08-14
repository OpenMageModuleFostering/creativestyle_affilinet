<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Adminhtml_Affilinet_Report_OrderController extends Creativestyle_AffiliNet_Controller_Adminhtml_Report_Abstract {

    protected function _initLayout() {
        parent::_initLayout();

        $this->_addBreadcrumb($this->__('Orders'), $this->__('Orders'))
            ->_title($this->__('Orders'))
            ->_setActiveMenu('affilinet/order');

        $gridBlock = $this->getLayout()->getBlock('adminhtml_report_order.grid');
        $filterFormBlock = $this->getLayout()->getBlock('affilinet.order.filter.form');

        $this->_initBlocks(array(
            $gridBlock,
            $filterFormBlock
        ));

        return $this;
    }

    protected function _updateTransactions($transactions, $trackingType = 'basket', $store = null) {
        $result = array('success' => 1);
        if (!empty($transactions)) {
            foreach ($transactions as $trasactionId => $transactionData) {
                if (is_array($transactionData) && array_key_exists('status', $transactionData)) {
                    if (isset($transactionData['status']['current']) && isset($transactionData['status']['new']) && $transactionData['status']['current'] != $transactionData['status']['new']) {
                        $action = null;
                        $params = array();
                        switch ($transactionData['status']['new']) {
                            case Creativestyle_AffiliNet_Model_Api_Source_TransactionStatus::OPEN:
                                $action = 'SetOpen';
                                break;
                            case Creativestyle_AffiliNet_Model_Api_Source_TransactionStatus::CONFIRMED:
                                $action = 'SetConfirmed';
                                break;
                            case Creativestyle_AffiliNet_Model_Api_Source_TransactionStatus::CANCELLED:
                                $action = 'SetCancelled';
                                $params['cancellation_reason'] = (array_key_exists('cancellation_reason', $transactionData) ? $transactionData['cancellation_reason'] : '');
                                break;
                        }
                        if (null != $action) {
                            try {
                                if ('basket' == $trackingType) {
                                    $apiResponse = Mage::getSingleton('affilinet/api')->setStore($store)->updateBasket($trasactionId, $action, $params);
                                } else {
                                    $apiResponse = Mage::getSingleton('affilinet/api')->setStore($store)->updateTransaction($trasactionId, $action, $params);
                                }
                                if (is_object($apiResponse) && $apiResponse->Successful) {
                                    $result = array(
                                        'success' => 1,
                                        'success_messages' => $this->__('New transaction data have been submitted to affilinet. It may take up to few minutes until the changes become visible.')
                                    );
                                } else {
                                    $result = array(
                                        'error' => 1,
                                        'error_messages' => $this->__('Unknown error')
                                    );
                                }
                            } catch (Exception $e) {
                                $result = array(
                                    'error' => 1,
                                    'error_messages' => $this->_extractErrorMessage($e->getMessage())
                                );
                            }
                        }
                    }
                }
                if (!(array_key_exists('error', $result) && $result['error']) && is_array($transactionData) && array_key_exists('net_price', $transactionData)) {
                    if (isset($transactionData['net_price']['current']) && isset($transactionData['net_price']['new']) && $transactionData['net_price']['current'] != $transactionData['net_price']['new']) {
                        $params = array(
                            'new_net_price' => $transactionData['net_price']['new'],
                            'cancellation_reason' => (array_key_exists('cancellation_reason', $transactionData) ? $transactionData['cancellation_reason'] : '')
                        );
                        try {
                            $apiResponse = Mage::getSingleton('affilinet/api')->setStore($store)->updateTransaction($trasactionId, 'SetNewNetPrice', $params);
                            if (is_object($apiResponse) && $apiResponse->Successful) {
                                $result = array(
                                    'success' => 1,
                                    'success_messages' => $this->__('New transaction data have been submitted to affilinet. It may take up to few minutes until the changes become visible.')
                                );
                            } else {
                                $result = array(
                                    'error' => 1,
                                    'error_messages' => $this->__('Unknown error')
                                );
                            }
                        } catch (Exception $e) {
                            $result = array(
                                'error' => 1,
                                'error_messages' => $this->_extractErrorMessage($e->getMessage())
                            );
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function getBasketInfoAction() {
        $basketId = $this->getRequest()->getPost('id', null);
        if (null !== $basketId) {
            $store = $this->getRequest()->getPost('store', null);
            $collection = Mage::getModel('affilinet/api_model_basketItem')->getCollection()->setStore($store)->addFilter('basket_id', $basketId);
            Mage::register('affilinet_basket_info', $collection);
            Mage::register('affilinet_basket_id', $basketId);
            $this->loadLayout();
            $this->renderLayout();
        } else {
            $this->_forward('noRoute');
        }
    }

    public function saveBasketItemsAction() {
        $basketId = $this->getRequest()->getPost('id', null);
        if (null !== $basketId) {
            $store = $this->getRequest()->getPost('store', null);
            $result = array('success' => 1);
            $baskets = $this->getRequest()->getPost('baskets', array());
            if (array_key_exists($basketId, $baskets)) {
                $basketData = $baskets[$basketId];
                if (is_array($basketData) && array_key_exists('items', $basketData)) {
                    foreach ($basketData['items'] as $positionNumber => $itemData) {
                        $itemUpdateParams = array();
                        if (is_array($itemData) && array_key_exists('qty', $itemData)) {
                            if (isset($itemData['qty']['current']) && isset($itemData['qty']['new']) && $itemData['qty']['current'] != $itemData['qty']['new']) {
                                $itemUpdateParams['quantity'] = $itemData['qty']['new'];
                            }
                        }
                        if (is_array($itemData) && array_key_exists('status', $itemData)) {
                            if (isset($itemData['status']['current']) && isset($itemData['status']['new']) && $itemData['status']['current'] != $itemData['status']['new']) {
                                $itemUpdateParams['cancellation_reason'] = $itemData['status']['new'];
                            }
                        }
                        if (!empty($itemUpdateParams)) {
                            try {
                                $apiResponse = Mage::getSingleton('affilinet/api')->setStore($store)->updateBasketItem($basketId, $positionNumber, $itemUpdateParams);
                                if (is_object($apiResponse) && $apiResponse->Successful) {
                                    $result = array(
                                        'success' => 1,
                                        'success_messages' => $this->__('New basket data have been submitted to affilinet. It may take up to few minutes until the changes become visible.')
                                    );
                                } else {
                                    $result = array(
                                        'error' => 1,
                                        'error_messages' => $this->__('Unknown error')
                                    );
                                }
                            } catch (Exception $e) {
                                $result = array(
                                    'error' => 1,
                                    'error_messages' => $this->_extractErrorMessage($e->getMessage())
                                );
                            }
                        }
                    }
                }
            }
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
            return;
        } else {
            $this->_forward('noRoute');
        }
    }

    public function saveBasketsAction() {
        $store = $this->getRequest()->getPost('store', null);
        $baskets = $this->getRequest()->getPost('basket', array());
        $result = $this->_updateTransactions($baskets, 'basket', $store);
        if (!(array_key_exists('error', $result) && $result['error'])) {
            $transactions = $this->getRequest()->getPost('standard', array());
            $result = $this->_updateTransactions($transactions, 'standard', $store);
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('admin/affilinet/order');
    }

}
