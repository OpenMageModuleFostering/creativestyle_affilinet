<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_System_Config_Form_Field_Image extends Mage_Adminhtml_Block_System_Config_Form_Field {

    /**
     * Get image preview url
     *
     * @return string
     */
    protected function _getUrl(Varien_Data_Form_Element_Abstract $element) {
        $url = $element->getValue();

        $config = $element->getFieldConfig();
        /* @var $config Varien_Simplexml_Element */
        if (!empty($config->base_url)) {
            $el = $config->descend('base_url');
            $urlType = empty($el['type']) ? 'link' : (string)$el['type'];
            $url = Mage::getBaseUrl($urlType) . (string)$config->base_url . '/' . $url;
        }

        return $url;
    }

    /**
     * Return image field HTML code
     *
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $html = '';

        if ((string)$element->getValue()) {
            $url = $this->_getUrl($element);

            if (!preg_match("/^http\:\/\/|https\:\/\//", $url)) {
                $url = Mage::getBaseUrl('media') . $url;
            }

            // image preview
            $html .= '<img src="' . $url . '" id="' . $element->getHtmlId() . '_image" title="' . $element->getValue() . '"'
                . ' alt="' . $element->getValue() . '" class="small-image-preview v-middle" /><br />';
        }

        // file input
        $html .= '<input style="width:274px; padding:2px; margin:1px;" id="' . $element->getHtmlId() . '" name="' . $element->getName()
            . '" value="' . $element->getEscapedValue() . '" '. $element->serialize($element->getHtmlAttributes()) . '/>' . "\n";

        if($element->getTooltip()){
            $html .= '<div class="field-tooltip"><div>' . $element->getTooltip() . '</div></div>';
        }

        $html .= $element->getAfterElementHtml();

        if ((string)$element->getValue()) {
            // delete checkbox
            $label = Mage::helper('core')->__('Delete Image');
            $html .= '<br /><input type="checkbox"'
                . ' name="' . $element->getName() . '[delete]" value="1" class="checkbox"'
                . ' id="' . $element->getHtmlId() . '_delete"' . ($element->getDisabled() ? ' disabled="disabled"': '')
                . '/>';
            $html .= '<label for="' . $element->getHtmlId() . '_delete"'
                . ($element->getDisabled() ? ' class="disabled"' : '') . '> ' . $label . '</label>';

            // hidden input
            $html .= '<input type="hidden" name="' . $element->getName() . '[value]" value="' . $element->getValue() . '" />';
        }

        $element->setClass('input-file');

        return $html;
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $id = $element->getHtmlId();

        $html = '<td class="label"><label for="'.$id.'">'.$element->getLabel().'</label></td>';

        //$isDefault = !$this->getRequest()->getParam('website') && !$this->getRequest()->getParam('store');
        $isMultiple = $element->getExtType()==='multiple';

        // replace [value] with [inherit]
        $namePrefix = preg_replace('#\[value\](\[\])?$#', '', $element->getName());

        $options = $element->getValues();

        $addInheritCheckbox = false;
        if ($element->getCanUseWebsiteValue()) {
            $addInheritCheckbox = true;
            $checkboxLabel = Mage::helper('adminhtml')->__('Use Website');
        }
        elseif ($element->getCanUseDefaultValue()) {
            $addInheritCheckbox = true;
            $checkboxLabel = Mage::helper('adminhtml')->__('Use Default');
        }

        if ($addInheritCheckbox) {
            $inherit = $element->getInherit()==1 ? 'checked="checked"' : '';
            if ($inherit) {
                $element->setDisabled(true);
            }
        }

        if ($element->getTooltip()) {
            $html .= '<td class="value with-tooltip">';
            $html .= $this->_getElementHtml($element);
        } else {
            $html .= '<td class="value">';
            $html .= $this->_getElementHtml($element);
        };
        if ($element->getComment()) {
            $html.= '<p class="note"><span>'.$element->getComment().'</span></p>';
        }
        $html.= '</td>';

        if ($addInheritCheckbox) {

            $defText = $element->getDefaultValue();
            if ($options) {
                $defTextArr = array();
                foreach ($options as $k=>$v) {
                    if ($isMultiple) {
                        if (is_array($v['value']) && in_array($k, $v['value'])) {
                            $defTextArr[] = $v['label'];
                        }
                    } elseif ($v['value']==$defText) {
                        $defTextArr[] = $v['label'];
                        break;
                    }
                }
                $defText = join(', ', $defTextArr);
            }

            // default value
            $html.= '<td class="use-default">';
            $html.= '<input id="' . $id . '_inherit" name="'
                . $namePrefix . '[inherit]" type="checkbox" value="1" class="checkbox config-inherit" '
                . $inherit . ' onclick="toggleValueElements(this, Element.previous(this.parentNode))" /> ';
            $html.= '<label for="' . $id . '_inherit" class="inherit" title="'
                . htmlspecialchars($defText) . '">' . $checkboxLabel . '</label>';
            $html.= '</td>';
        }

        $html.= '<td class="scope-label">';
        if ($element->getScope()) {
            $html .= $element->getScopeLabel();
        }
        $html.= '</td>';

        $html.= '<td class="">';
        if ($element->getHint()) {
            $html.= '<div class="hint" >';
            $html.= '<div style="display: none;">' . $element->getHint() . '</div>';
            $html.= '</div>';
        }
        $html.= '</td>';

        return $this->_decorateRowHtml($element, $html);
    }

    protected function _decorateRowHtml($element, $html) {
        return '<tr id="row_' . $element->getHtmlId() . '">' . $html . '</tr>';
    }

}
