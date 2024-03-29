<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Cms extends Mage_Core_Block_Template {

    protected function _getConfig() {
        return Mage::getSingleton('affilinet/config');
    }

    protected function _getCurrentLanguage() {
        switch (strtolower(Mage::app()->getLocale()->getLocaleCode())) {
            case 'de_de':
                return 'de';
            case 'de_at':
                return 'at';
            case 'de_ch':
                return 'ch';
            case 'fr_fr':
                return 'fr';
            case 'nl_nl':
                return 'nl';
            case 'es_es':
                return 'es';
            default:
                return 'en';
        }
    }

    protected function _getPlatformSignupId() {
        if ($this->hasData('platform_id')) {
            return $this->getData('platform_id');
        }
        $language = $this->_getCurrentLanguage();
        $platform = ($language == 'en' ? 'uk' : $language);
        return $this->_getConfig()->getPlatformSignupId($platform);
    }

    public function isSignedUp() {
        return (bool)$this->_getConfig()->getProgramId();
    }

    public function getSignupFrameSource() {
        return sprintf('//modules.affili.net/_/advertiser/preregister.aspx?platform=%s&amp;language=%s&amp;referer=magento', $this->_getPlatformSignupId(), $this->_getCurrentLanguage());
    }

    public function getIntroductionFrameSource() {
        return sprintf('//www.affili.net/htmlcontent/%s/shopmodules/magento/1/welcome.html', $this->_getCurrentLanguage());
    }

}
