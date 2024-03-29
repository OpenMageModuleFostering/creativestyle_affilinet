<?php

class Creativestyle_AffiliNet_Block_Adminhtml_Datafeed_Edit_Tab_Cron extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array());

        $values = array();
        $frozen = Mage::registry('frozen_data');
        if ($frozen){
            if(is_array($frozen)){
                $values = $frozen;
            }else{
                $values = $frozen->getData();
            }
        }

        $fieldset->addField('cron_active', 'checkbox', array(
            'label' => Mage::helper('affilinet')->__('Active'),
            'required' => false,
            'checked' => (isset($values['cron_active']) && $values['cron_active'] == 1) ? true : false,
            'onclick'   => 'this.value = this.checked ? 1 : 0;',
            'name' => 'cron_active',
            'after_element_html' => '<div class="field-tooltip"><div>'.Mage::helper('affilinet')->__('Select, if the feed should be activated for cron job batch export.').'</div></div>'
        ));

        $fieldset->addField('cron_start', 'time', array(
            'label' => Mage::helper('affilinet')->__('Start at'),
            'required' => false,
            'name' => 'cron_start',
            'class' => 'cron_start',
            'after_element_html' => '<div class="field-tooltip"><div>'.Mage::helper('affilinet')->__('Appoint the export start time.').'</div></div>'
        ));

        $fieldset->addField('cron_repeat', 'select', array(
            'label' => Mage::helper('affilinet')->__('Repeat every'),
            'required' => false,
            'options' => array(
                0 => 0,
                1 => 1,
                3 => 3,
                6 => 6,
                12 => 12
            ),
            'after_element_html' => Mage::helper('affilinet')->__('Hours'),
            'style' => 'width:50px',
            'name' => 'cron_repeat',
        ));

        $filename = (isset($values['cron_file']) && $values['cron_file']) ? $values['cron_file'] : '';
        if(strpos($values['cron_file'], '.csv') !== false){
            $values['cron_file'] = substr($values['cron_file'], 0, -4);
        }
        $note = '<span style="white-space:nowrap;font-size:12px;">' .
            Mage::getBaseUrl('media') .
            'creativestyle/affilinet/datafeed/<span id="note_cron_file_update">' .
            $filename .
            '<span></span>';

        $file = $fieldset->addField('cron_file', 'text', array(
            'label' => Mage::helper('affilinet')->__('File name'),
            'required' => false,
            'name' => 'cron_file',
            'class' => 'validate-data',
            'note' => $note,
            'onchange' => 'updateNote(this.value);',
        ));

        $file->setAfterElementHtml("<script type=\"text/javascript\">
            function updateNote(value){
                $('note_cron_file_update').update(value + '.csv');
            }
        </script>");

        $form->setValues($values);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
