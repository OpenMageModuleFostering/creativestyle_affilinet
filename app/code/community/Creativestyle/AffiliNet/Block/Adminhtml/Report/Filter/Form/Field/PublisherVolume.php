<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_Report_Filter_Form_Field_PublisherVolume extends Varien_Data_Form_Element_Select {

    public function getElementHtml() {
        $value = $this->getValue();

        if (!is_array($value)) {
            $value = array($value);
        }

        $volumeMinValue = array_key_exists('min', $value) ? $value['min'] : null;
        $volumeMaxValue = array_key_exists('max', $value) ? $value['max'] : null;
        $volumeTypeValue = array_key_exists('type', $value) ? $value['type'] : null;

        $this->addClass('publisher-volume-amount-input');
        $html = '<input id="' . $this->getHtmlId() . '_min" type="text" value="' . $volumeMinValue . '" name="' . $this->getName() . '[min]" ' . $this->serialize($this->getHtmlAttributes()) . '>' . "\n";
        $html .= '<span class="publisher-volume-separator">&le;</span>';
        $this->removeClass('publisher-volume-amount-input');

        $this->addClass('publisher-volume-type-select');
        $html .= '<select id="' . $this->getHtmlId() . '_type" name="' . $this->getName() . '[type]" ' . $this->serialize($this->getHtmlAttributes()) . '>' . "\n";

        if ($values = $this->getValues()) {
            foreach ($values as $key => $option) {
                if (!is_array($option)) {
                    $html.= $this->_optionToHtml(array(
                        'value' => $key,
                        'label' => $option),
                        array($volumeTypeValue)
                    );
                }
                elseif (is_array($option['value'])) {
                    $html.='<optgroup label="'.$option['label'].'">'."\n";
                    foreach ($option['value'] as $groupItem) {
                        $html.= $this->_optionToHtml($groupItem, array($volumeTypeValue));
                    }
                    $html.='</optgroup>'."\n";
                }
                else {
                    $html.= $this->_optionToHtml($option, array($volumeTypeValue));
                }
            }
        }

        $html.= '</select>'."\n";
        $this->removeClass('publisher-volume-type-select');

        $this->addClass('publisher-volume-amount-input');
        $html .= '<span class="publisher-volume-separator">&le;</span>';
        $html .= '<input id="' . $this->getHtmlId() . '_max" type="text" value="' . $volumeMaxValue . '" name="' . $this->getName() . '[max]" ' . $this->serialize($this->getHtmlAttributes()) . '>' . "\n";
        $this->removeClass('publisher-volume-amount-input');

        $html.= $this->getAfterElementHtml();
        return $html;
    }

}
