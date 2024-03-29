<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Datafeed_Form_Field_Image extends Varien_Data_Form_Element_Image {

    /**
     * Return image field HTML code
     *
     * @return string
     */
    public function getElementHtml() {
        $html = '';

        $this->setClass('input-file');

        if ((string)$this->getValue()) {
            $url = Mage::getBaseUrl('media') . str_replace(DS, '/', $this->getValue());

            // image preview
            $html .= '<img src="' . $url . '" id="' . $this->getHtmlId() . '_image" title="' . $this->getValue() . '"'
                . ' alt="' . $this->getValue() . '" class="small-image-preview v-middle" /><br />';
        }

        // file input
        $html .= '<input style="padding:2px;margin:1px;" id="' . $this->getHtmlId() . '" name="' . $this->getName()
            . '" value="' . $this->getEscapedValue() . '" '. $this->serialize($this->getHtmlAttributes()) . '/>' . "\n";

        if($this->getTooltip()){
            $html .= '<div class="field-tooltip"><div>' . $this->getTooltip() . '</div></div>';
        }

        $html .= $this->getAfterElementHtml();

        if ((string)$this->getValue()) {
            // delete checkbox
            $label = Mage::helper('core')->__('Delete Image');
            $html .= '<br /><input type="checkbox"'
                . ' name="' . $this->getName() . '[delete]" value="1" class="checkbox"'
                . ' id="' . $this->getHtmlId() . '_delete"' . ($this->getDisabled() ? ' disabled="disabled"': '')
                . '/>';
            $html .= '<label for="' . $this->getHtmlId() . '_delete"'
                . ($this->getDisabled() ? ' class="disabled"' : '') . '> ' . $label . '</label>';

            // hidden input
            $html .= '<input type="hidden" name="' . $this->getName() . '[value]" value="' . $this->getValue() . '" />';
        }

        return $html;
    }

}
