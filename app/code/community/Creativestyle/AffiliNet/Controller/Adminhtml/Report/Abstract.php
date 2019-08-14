<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
abstract class Creativestyle_AffiliNet_Controller_Adminhtml_Report_Abstract extends Mage_Adminhtml_Controller_Action {


    /**
     * Add affilinet breadcrumbs and title
     *
     * @return Creativestyle_AffiliNet_Controller_Report_Abstract
     */
    protected function _initLayout() {
        $this->loadLayout()
            ->_addBreadcrumb($this->__('affilinet'), $this->__('affilinet'))
            ->_title($this->__('affilinet'));
        return $this;
    }

    /**
     * Init layout blocks
     *
     * @param array|Varien_Object $blocks
     * @return Creativestyle_AffiliNet_Controller_Report_Abstract
     */
    protected function _initBlocks($blocks) {
        if (!is_array($blocks)) {
            $blocks = array($blocks);
        }

        $storeId = $this->getRequest()->getParam('store', Mage::helper('affilinet')->getStoreSwitcherFirstId());

        $requestData = Mage::helper('adminhtml')->prepareFilterString($this->getRequest()->getParam('filter'));
        $requestData = $this->_filterDates($requestData, array('from', 'to'));
        $params = new Varien_Object($requestData);

        foreach ($blocks as $block) {
            if ($block) {
                $block->setFilterData($params);
                if (null !== $storeId) {
                    $block->setStore($storeId);
                }
            }
        }

        return $this;
    }

    protected function _extractErrorMessage($soapMessage) {
        $explodedSoapMessage = explode('|', $soapMessage);
        if (count($explodedSoapMessage) > 1) {
            array_shift($explodedSoapMessage);
            $soapMessage = implode('|', $explodedSoapMessage);
        }
        return $soapMessage;
    }

    public function indexAction() {
        try {
            $this->_initLayout();
            $this->renderLayout();
        } catch (SoapFault $eSoap) {
            if ($eSoap->faultcode == 'a:InvalidSecurity') {
                $this->_redirect('*/*/auth');
                return;
            } else {
                Mage::getSingleton('adminhtml/session')->addError($this->_extractErrorMessage($eSoap->getMessage()));
                $this->_redirect('*/*/');
                return;
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/');
            return;
        }
    }

    public function authAction() {
        $this->_initLayout();
        $this->renderLayout();
    }

}
