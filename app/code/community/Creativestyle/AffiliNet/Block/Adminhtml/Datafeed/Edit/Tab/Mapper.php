<?php

class Creativestyle_AffiliNet_Block_Adminhtml_Datafeed_Edit_Tab_Mapper extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array());

        $fieldset->addField('field_mapper', 'text', array(
            'name'=>'field_mapper',
            'class'=>'requried-entry',
            'value'=> ''
        ));

        $form->getElement('field_mapper')->setRenderer(
            $this->getLayout()->createBlock('affilinet/adminhtml_datafeed_renderer_mapper')
        );

        $form->setValues(array());
        $form->setMethod('post');
        $form->setId('edit_form');

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
