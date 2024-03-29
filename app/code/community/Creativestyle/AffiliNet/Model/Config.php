<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Model_Config {

    const XML_PATH_GENERAL_PLATFORM            = 'affilinet/general/platform';
    const XML_PATH_GENERAL_PROGRAM_ID          = 'affilinet/general/program_id';
    const XML_PATH_GENERAL_CURRENCY            = 'affilinet/general/currency';
    const XML_PATH_GENERAL_PRODUCT_ID          = 'affilinet/general/product_id';
    const XML_PATH_GENERAL_MANUFACTURER        = 'affilinet/general/manufacturer_attribute';
    const XML_PATH_GENERAL_WEBSERVICE_USER     = 'affilinet/general/webservice_user';
    const XML_PATH_GENERAL_WEBSERVICE_PASSWORD = 'affilinet/general/webservice_password';
    const XML_PATH_GENERAL_COMPANY_LOGO        = 'affilinet/general/company_logo';

    const XML_PATH_TRACKING_ACTIVE             = 'affilinet/tracking/active';
    const XML_PATH_TRACKING_RATE               = 'affilinet/tracking/rate';
    const XML_PATH_TRACKING_PARAMETER1         = 'affilinet/tracking/parameter1';
    const XML_PATH_TRACKING_PARAMETER2         = 'affilinet/tracking/parameter2';
    const XML_PATH_TRACKING_ATTRIBUTE1         = 'affilinet/tracking/attribute1';
    const XML_PATH_TRACKING_ATTRIBUTE2         = 'affilinet/tracking/attribute2';
    const XML_PATH_TRACKING_ATTRIBUTE3         = 'affilinet/tracking/attribute3';
    const XML_PATH_TRACKING_ATTRIBUTE4         = 'affilinet/tracking/attribute4';
    const XML_PATH_TRACKING_ATTRIBUTE5         = 'affilinet/tracking/attribute5';

    const XML_PATH_RETARGETING_ACTIVE          = 'affilinet/retargeting/active';
    const XML_PATH_RETARGETING_PRODUCT         = 'affilinet/retargeting/product_options';
    const XML_PATH_RETARGETING_CATEGORY        = 'affilinet/retargeting/category_options';
    const XML_PATH_RETARGETING_CART            = 'affilinet/retargeting/cart_options';
    const XML_PATH_RETARGETING_CHECKOUT        = 'affilinet/retargeting/checkout_options';

    public function getAllPlatformsData() {
        return Mage::getConfig()->getNode('global/creativestyle/affilinet/platforms')->asArray();
    }

    public function getPlatformData($platform) {
        $platforms = $this->getAllPlatformsData();
        if (array_key_exists($platform, $platforms)) {
            return $platforms[$platform];
        }
        return false;
    }

    public function getPlatformSignupId($platform = 'uk') {
        $platformData = $this->getPlatformData($platform);
        if (is_array($platformData) && array_key_exists('signup', $platformData)) {
            return $platformData['signup'];
        }
        return null;
    }

    public function getPlatform($store = null) {
        return Mage::getStoreConfig(self::XML_PATH_GENERAL_PLATFORM, $store);
    }

    public function getPlatformCurrency($store = null) {
        switch (strtolower($this->getPlatform($store))) {
            case 'ch':
                return 'CHF';
            case 'uk':
                return 'GBP';
            default:
                return 'EUR';
        }
    }

    public function getProgramId($store = null) {
        return trim(Mage::getStoreConfig(self::XML_PATH_GENERAL_PROGRAM_ID, $store));
    }

    public function getWebserviceUser($store = null) {
        return Mage::getStoreConfig(self::XML_PATH_GENERAL_WEBSERVICE_USER, $store);
    }

    public function getWebservicePassword($store = null) {
        return Mage::getStoreConfig(self::XML_PATH_GENERAL_WEBSERVICE_PASSWORD, $store);
    }

    public function isTrackingActive($store = null) {
        return (bool)$this->getProgramId($store) && Mage::getStoreConfigFlag(self::XML_PATH_TRACKING_ACTIVE, $store);
    }

    public function getTrackingParameters($store = null) {
        return array(
            1 => Mage::getStoreConfig(self::XML_PATH_TRACKING_PARAMETER1, $store),
            2 => Mage::getStoreConfig(self::XML_PATH_TRACKING_PARAMETER2, $store)
        );
    }

    public function getTrackingAttributes($store = null) {
        return array(
            1 => Mage::getStoreConfig(self::XML_PATH_TRACKING_ATTRIBUTE1, $store),
            2 => Mage::getStoreConfig(self::XML_PATH_TRACKING_ATTRIBUTE2, $store),
            3 => Mage::getStoreConfig(self::XML_PATH_TRACKING_ATTRIBUTE3, $store),
            4 => Mage::getStoreConfig(self::XML_PATH_TRACKING_ATTRIBUTE4, $store),
            5 => Mage::getStoreConfig(self::XML_PATH_TRACKING_ATTRIBUTE5, $store)
        );
    }

    public function getTrackingRate($store = null) {
        $rate = explode('-', Mage::getStoreConfig(self::XML_PATH_TRACKING_RATE, $store));
        if (count($rate) > 1) {
            return new Varien_Object(array(
                'mode' => $rate[0],
                'ltype' => $rate[1]
            ));
        }
        return null;
    }

    public function getTrackingType($store = null) {
        return Mage::getStoreConfig(self::XML_PATH_TRACKING_ACTIVE, $store);
    }

    public function isRetargetingActive($store = null) {
        return (bool)$this->getProgramId($store) && Mage::getStoreConfigFlag(self::XML_PATH_RETARGETING_ACTIVE, $store);
    }

    public function getProductIdentifier($store = null) {
        return Mage::getStoreConfig(self::XML_PATH_GENERAL_PRODUCT_ID, $store);
    }

    public function getManufacturerAttribute($store = null) {
        return Mage::getStoreConfig(self::XML_PATH_GENERAL_MANUFACTURER, $store);
    }

    public function getCompanyLogo($store = null) {
        return 'affilinet' . DS . 'logo' . DS . Mage::getStoreConfig(self::XML_PATH_GENERAL_COMPANY_LOGO, $store);
    }

    public function getTrackingDomain($store = null) {
        $platformData = $this->getPlatformData($this->getPlatform($store));
        if (is_array($platformData) && array_key_exists('tracking_domain', $platformData)) {
            return trim($platformData['tracking_domain']);
        }
        return null;
    }

    public function getProductRetargetingOptions($store = null) {
        return unserialize(Mage::getStoreConfig(self::XML_PATH_RETARGETING_PRODUCT, $store));
    }

    public function getCategoryRetargetingOptions($store = null) {
        return unserialize(Mage::getStoreConfig(self::XML_PATH_RETARGETING_CATEGORY, $store));
    }

    public function getCartRetargetingOptions($store = null) {
        return unserialize(Mage::getStoreConfig(self::XML_PATH_RETARGETING_CART, $store));
    }

    public function getCheckoutRetargetingOptions($store = null) {
        return unserialize(Mage::getStoreConfig(self::XML_PATH_RETARGETING_CHECKOUT, $store));
    }

    public function getWsdlUrl($area) {
        return (string)Mage::getConfig()->getNode('global/creativestyle/affilinet/webservice/' . strtolower($area) . '/wsdl');
    }

    public function getWebserviceNamespace() {
        return (string)Mage::getConfig()->getNode('global/creativestyle/affilinet/webservice/ns');
    }

    public function getWebserviceSecurityNamespace() {
        return (string)Mage::getConfig()->getNode('global/creativestyle/affilinet/webservice/security_ns');
    }

}
