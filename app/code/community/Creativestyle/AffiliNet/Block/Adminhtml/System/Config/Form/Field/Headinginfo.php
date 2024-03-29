<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Grzegorz Bogusz / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Block_Adminhtml_System_Config_Form_Field_Headinginfo extends Mage_Adminhtml_Block_System_Config_Form_Field {

    public function render(Varien_Data_Form_Element_Abstract $element) {

        $old = sprintf('<tr class="system-fieldset-sub-head" id="row_%s"><td colspan="5"><h4 id="%s">%s</h4></td></tr>',
            $element->getHtmlId(), $element->getHtmlId(), $element->getLabel()
        );

        $id = $element->getHtmlId();

        $html = '<tr class="system-fieldset-sub-head" id="row_'.$element->getHtmlId().'"><td colspan="5"><h4 id="'.$element->getHtmlId().'">'.$element->getLabel();
        if ($element->getTooltip()) {
            $html .= '  <div class="field-tooltip"><div>' . $element->getTooltip() . '</div></div>';
        }

        $html .= '</h4></td></tr>';

        return $html;

    }

}
