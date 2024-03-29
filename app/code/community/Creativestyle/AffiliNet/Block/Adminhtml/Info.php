<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Info extends Mage_Adminhtml_Block_System_Config_Form_Fieldset {

    public function render(Varien_Data_Form_Element_Abstract $element) {
        $this->setTemplate('creativestyle/affilinet/info.phtml');
        return $this->toHtml();
    }

    public function getExtensionVersion() {
        return (string)Mage::getConfig()->getNode('modules/Creativestyle_AffiliNet/version');
    }

}
