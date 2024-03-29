<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_System_Config_Form_Field_Currency extends Mage_Adminhtml_Block_System_Config_Form_Field {

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        switch (strtolower($element->getScope())) {
            case 'stores':
                return sprintf('<strong>%s</strong>', Mage::getModel('core/store')->load($element->getScopeId())->getBaseCurrencyCode());
            case 'websites':
                return sprintf('<strong>%s</strong>', Mage::getModel('core/website')->load($element->getScopeId())->getBaseCurrencyCode());
            default:
                return sprintf('<strong>%s</strong>', Mage::app()->getBaseCurrencyCode());
        }
    }

    public function render(Varien_Data_Form_Element_Abstract $element) {
        $id = $element->getHtmlId();

        $html = '<td class="label"><label for="'.$id.'">'.$element->getLabel().'</label></td>';

        if ($element->getTooltip()) {
            $html .= '<td class="value with-tooltip">';
            $html .= $this->_getElementHtml($element);
            $html .= '<div class="field-tooltip"><div>' . $element->getTooltip() . '</div></div>';
        } else {
            $html .= '<td class="value">';
            $html .= $this->_getElementHtml($element);
        };
        if ($element->getComment()) {
            $html .= '<p class="note"><span>'.$element->getComment().'</span></p>';
        }
        $html .= '</td>';

        if ($element->getCanUseWebsiteValue() || $element->getCanUseDefaultValue()) {
            $html .= '<td class="use-default"></td>';
        }

        $html .= '<td class="scope-label"></td>';

        $html .= '<td class="">';
        if ($element->getHint()) {
            $html .= '<div class="hint" >';
            $html .= '<div style="display: none;">' . $element->getHint() . '</div>';
            $html .= '</div>';
        }
        $html .= '</td>';

        return $this->_decorateRowHtml($element, $html);
    }

    protected function _decorateRowHtml($element, $html) {
        return '<tr id="row_' . $element->getHtmlId() . '">' . $html . '</tr>';
    }

}
