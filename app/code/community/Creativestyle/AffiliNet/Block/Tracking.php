<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Tracking extends Creativestyle_AffiliNet_Block_Abstract {

    public function getCartTrackingString() {
        $cartTracking = '';
        if ($this->_getOrder()) {
            $cartData = array();

            $manufacturerAttribute = $this->_getManufacturerAttribute();
            $productIdentifier = $this->_getProductIdentifier();
            $trackingAttributes = $this->_getConfig()->getTrackingAttributes();

            $helper = $this->helper('affilinet');
            $taxHelper = $this->helper('tax');

            foreach ($this->_getOrder()->getAllVisibleItems() as $item) {
                $product = Mage::getModel('catalog/product')->load($item->getProductId());
                $itemData = array();
                $itemData[] = 'ArticleNb=' . rawurlencode($product->getData($productIdentifier));
                $itemData[] = 'ProductName=' . rawurlencode($item->getName());
                $itemData[] = 'Category=' . rawurlencode($helper->getProductCategory($product));
                $itemData[] = 'Quantity=' . round($item->getQtyOrdered());
                $itemData[] = 'SinglePrice=' . rawurlencode($taxHelper->getPrice($product, ($item->getBaseRowTotal() + $item->getBaseTaxAmount() + $item->getBaseHiddenTaxAmount() + $item->getBaseWeeeTaxAppliedRowAmount() - $item->getBaseDiscountAmount()) / $item->getQtyOrdered(), false, null, null, null, null, true));
                if ($product->getData($manufacturerAttribute)) {
                    $manufacturerAttributeModel = $product->getResource()->getAttribute($manufacturerAttribute);
                    if (is_callable(array($manufacturerAttributeModel, 'getFrontend'))) {
                        $itemData[] = 'Brand=' . rawurlencode($manufacturerAttributeModel->getFrontend()->getValue($product));
                    } else {
                        $itemData[] = 'Brand=' . rawurlencode($product->getData($manufacturerAttribute));
                    }
                } else {
                    $itemData[] = 'Brand=';
                }

                foreach ($trackingAttributes as $index => $attributeCode) {
                    if ($attributeCode) {
                        $propertyValue = 'Property' . $index . '=';
                        if ($product->getData($attributeCode)) {
                            $attributeModel = $product->getResource()->getAttribute($attributeCode);
                            if (is_callable(array($attributeModel, 'getFrontend'))) {
                                $propertyValue .= rawurlencode($attributeModel->getFrontend()->getValue($product));
                            } else {
                                $propertyValue .= rawurlencode($product->getData($attributeCode));
                            }
                        }
                        $itemData[] = $propertyValue;
                    }
                }
                $cartData[] = implode('&', $itemData);
                unset($product, $itemData);
            }
            $cartTracking = implode("\n", $cartData);
        }
        return $cartTracking;
    }

    public function getSubInfo($index) {
        if ($this->_getOrder()) {
            $trackingParams = $this->_getConfig()->getTrackingParameters();
            if (is_array($trackingParams) && isset($trackingParams[$index])) {
                switch ($trackingParams[$index]) {
                    case Creativestyle_AffiliNet_Model_System_Config_Source_Tracking_Parameters::PAYMENT_METHOD:
                        return rawurlencode($this->_getOrder()->getPayment()->getMethodInstance()->getTitle());
                    case Creativestyle_AffiliNet_Model_System_Config_Source_Tracking_Parameters::SHIPPING_METHOD:
                        return rawurlencode($this->_getOrder()->getShippingDescription());
                }
            }
        }
        return '';
    }

    public function getSystemSubInfo() {
        $edition = 'CE';
        if (method_exists('Mage','getEdition')) {
            switch (Mage::getEdition()) {
                case Mage::EDITION_COMMUNITY:
                    $edition = 'CE';
                    break;
                case Mage::EDITION_ENTERPRISE:
                    $edition = 'EE';
                    break;
                case Mage::EDITION_PROFESSIONAL:
                    $edition = 'PE';
                    break;
                case Mage::EDITION_GO:
                    $edition = 'Go';
                    break;
            }
        } else {
            $eeModuleConfig = (string)Mage::getConfig()->getNode('modules/Enterprise_Enterprise');
            if ($eeModuleConfig) {
                $edition = 'EE';
            }
        }
        return sprintf('Magento-%s-%s-modul%s', $edition, Mage::getVersion(), (string)Mage::getConfig()->getNode('modules/Creativestyle_AffiliNet/version'));
    }

    public function getPublisherRateInfo() {
        return $this->_getConfig()->getTrackingRate();
    }

    public function getTrackingType() {
        return $this->_getConfig()->getTrackingType();
    }

    /**
     * Render affilinet tracking scripts
     *
     * @return string
     */
    protected function _toHtml() {
        if (!$this->_getConfig()->isTrackingActive() || !$this->_getOrder()) {
            return '';
        }
        return parent::_toHtml();
    }

}
