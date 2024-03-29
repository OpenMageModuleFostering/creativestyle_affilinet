<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_System_Config_Form_Field_Checkbox extends Mage_Adminhtml_Block_System_Config_Form_Field {

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $this->setElement($element);
        $fieldValues = is_array($this->getElement()->getValue()) ? $this->getElement()->getValue() : array();
        $i = 0;
        $output = '<input type="hidden" name="' . $this->getElement()->getName() . '[]" id="' . $this->getElement()->getId() . '" value=""/>';
        foreach ($this->getElement()->getValues() as $value) {
            if (array_key_exists('value', $value) && array_key_exists('label', $value)) {
                if ($value['value']) {
                    $output .= '<div><input type="checkbox" name="' . $this->getElement()->getName() . '[]" id="' . $this->getElement()->getId() . '_' . ++$i . '" value="' . $value['value'] . '"' . (in_array($value['value'], $fieldValues) ? ' checked="checked"' : '') . ($this->getElement()->getDisabled() ? ' disabled="disabled"' : '') . '/> <label for="' . $this->getElement()->getId() . '_' . $i . '">' . $value['label'] . '</label>';
                    if(isset($value['tooltip']) && $value['tooltip']){
                        $output .= ' <div class="field-tooltip"><div>'.$value['tooltip'].'</div></div>';
                    }
                    $output .= '</div>';
                }

            }
        }
        return $output;
    }

}
