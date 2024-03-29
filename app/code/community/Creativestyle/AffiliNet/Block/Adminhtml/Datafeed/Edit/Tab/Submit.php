<?php

class Creativestyle_AffiliNet_Block_Adminhtml_Datafeed_Edit_Tab_Submit extends Mage_Adminhtml_Block_Widget_Form
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

        $name = (isset($values['name']) && $values['name']) ? $values['name'] : '';
        $fieldset->addField('note_name', 'note', array(
            'label' => Mage::helper('affilinet')->__('Name'),
            'text' => $name,
        ));

        $logoHtml = '';
        if (isset($values['company_logo']) && $values['company_logo']) {
            if (file_exists(Mage::getBaseDir('media') . DS . $values['company_logo'])) {
                $logoHtml = sprintf('<img src="%s" />', Mage::getBaseUrl('media') . str_replace(DS, '/', $values['company_logo']));
            }
        } else if ($defaultLogo = Mage::getSingleton('affilinet/config')->getCompanyLogo()) {
            if (file_exists(Mage::getBaseDir('media') . DS . $defaultLogo)) {
                $logoHtml = sprintf('<img src="%s" />', Mage::getBaseUrl('media') . str_replace(DS, '/', $defaultLogo));
            }
        }

        $fieldset->addField('note_logo', 'note', array(
            'label' => Mage::helper('affilinet')->__('Company logo'),
            'text' => $logoHtml
        ));


        $cronjob = (isset($values['cron_active']) && $values['cron_active']) ? Mage::helper('affilinet')->__('Active') : Mage::helper('affilinet')->__('Disabled');
        $fieldset->addField('note_cronjob', 'note', array(
            'label' => Mage::helper('affilinet')->__('Cronjob'),
            'text' => $cronjob,
        ));

        $begin = (isset($values['cron_start']) && $values['cron_start']) ? str_replace(',', ':', $values['cron_start']) : '';
        $fieldset->addField('note_begin', 'note', array(
            'label' => Mage::helper('affilinet')->__('Begin at'),
            'text' => $begin,
        ));

        $filename = (isset($values['cron_file']) && $values['cron_file']) ? Mage::getBaseUrl('media') . 'creativestyle/affilinet/datafeed/' . $values['cron_file'] : '';
        $fieldset->addField('note_link', 'note', array(
            'label' => Mage::helper('affilinet')->__('Feed link'),
            'text' => '<span style="white-space:nowrap;">' . $filename . '</span>'
        ));
/*
        $sendtest = $fieldset->addField('send_test', 'checkbox', array(
            'label' => Mage::helper('affilinet')->__('Send message to the test e-mail'),
            'required' => false,
            'checked' => (isset($values['send_test']) && $values['send_test'] == 1) ? true : false,
            'onclick'   => 'this.value = this.checked ? 1 : 0;',
            'name' => 'send_test',
            'after_element_html' => '<div class="field-tooltip"><div>'.Mage::helper('affilinet')->__('Send message to the test e-mail').'</div></div>'
        ));
*/
        $fieldset->addField('testemail', 'text', array(
            'label' => Mage::helper('affilinet')->__('Test e-mail'),
            'required' => false,
            'name' => 'testemail',
            'after_element_html' => '<div class="field-tooltip"><div>'.Mage::helper('affilinet')->__('Test e-mail').'</div></div>'
        ));
/*
        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                ->addFieldMap('testemail', 'testemail')
                ->addFieldMap('send_test', 'send_test')
                ->addFieldDependence('testemail', 'send_test', '1')
        );
*/
        $fieldset->addField('sendfeed', 'text', array(
            'name'=>'sendfeed',
            'value'=> '',
        ));

        $form->getElement('sendfeed')->setRenderer(
            $this->getLayout()->createBlock('affilinet/adminhtml_datafeed_renderer_sendfeed')
        );

        $form->setValues($values);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
