<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2015 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_System_Config_Form_Field_PublisherRate extends Mage_Adminhtml_Block_System_Config_Form_Field {

    protected function _getConfig() {
        return Mage::getSingleton('affilinet/config');
    }

    protected function _extractErrorMessage($soapMessage) {
        $explodedSoapMessage = explode('|', $soapMessage);
        if (count($explodedSoapMessage) > 1) {
            array_shift($explodedSoapMessage);
            $soapMessage = implode('|', $explodedSoapMessage);
        }
        return $soapMessage;
    }

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        if (Mage::app()->isSingleStoreMode() || strtolower($element->getScope()) == 'stores' || strtolower($element->getScope()) == 'default') {
            $storeId = strtolower($element->getScope()) == 'stores' ? $element->getScopeId() : null;
            if (!$this->_getConfig()->getWebserviceUser($storeId) || !$this->_getConfig()->getWebservicePassword($storeId)) {
                return '<strong id="' . $element->getHtmlId() . '"">' . Mage::helper('affilinet')->__('Please provide your web service username/password in the extension settings page to ensure rate selection for publisher commission') . '</strong>';
            }
            try {
                $rateCollection = Mage::getModel('affilinet/api_model_rate')->getCollection()->setStore($storeId)->load();
            } catch (SoapFault $eSoap) {
                if ($eSoap->faultcode == 'a:InvalidSecurity') {
                    return '<strong id="' . $element->getHtmlId() . '">' . Mage::helper('affilinet')->__('Please provide your web service username/password in the extension settings page to ensure rate selection for publisher commission') . '</strong>';
                } else {
                    return '<strong id="' . $element->getHtmlId() . '">' . Mage::helper('affilinet')->__('Error while retrieving publisher rates data') . ': ' . $this->_extractErrorMessage($eSoap->getMessage()) . '</strong>';
                }
            } catch (Exception $e) {
                return '<strong id="' . $element->getHtmlId() . '">' . Mage::helper('affilinet')->__('Error while retrieving publisher rates data') . ': ' . $e->getMessage() . '</strong>';
            }
            $rates = array();
            foreach ($rateCollection as $rate) {
                $rates[] = array(
                    'value' => $rate->getRateSymbol(),
                    'label' => $rate->getRateMode() . ' ' . $rate->getRateNumber() . ' - ' . $rate->getRateName()
                );
            }
            array_unshift($rates, array(
                'value' => '',
                'label' => Mage::helper('affilinet')->__('-- Please Select --'))
            );
            $element->setValues($rates);
            return parent::_getElementHtml($element);
        }
        return '';
    }

}
