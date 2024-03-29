<?php

class Creativestyle_AffiliNet_Block_Adminhtml_Datafeed_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array());
        $fieldset->addType('logoimage', Mage::getConfig()->getBlockClassName('affilinet/adminhtml_datafeed_form_field_image'));

        $values = array();
        $frozen = Mage::registry('frozen_data');
        if ($frozen){
            if(is_array($frozen)){
                $values = $frozen;
            }else{
                $values = $frozen->getData();
            }
        }else{
            $values = array(
                'escaping' => '""',
                'delimiter' => ';',
                'column_title' => 1,
                'last_delimiter' => 1,
                'filter_active' => 1,
                'filter_stock' => 1
            );
        }

        if(!empty($values) && isset($values['last_page']) && $values['last_page'] && $values['coll_size']){
            $percent = round(($values['last_page']*100)/$values['coll_size']);
            $fieldset->addField('generatingprogress', 'note', array(
                'label' => Mage::helper('affilinet')->__('Generating progress'),
                'note' => '<span style="display:block; position:relative; width:200px; height:24px; background:#e1e1e1;">
                    <span style="display:block; position:absolute; top:0; left:0; height:24px; width:'.$percent.'%; background:#3fa46a;"></span>
                    <span style="position:absolute; top:0; left:6px; color:#fff; display:block; line-height:24px; font-size:12px;">'.$percent.'%</span>
                </span>'
            ));
        }

        $fieldset->addField('name', 'text', array(
            'label' => Mage::helper('affilinet')->__('Name'),
            'required' => true,
            'name' => 'name',
        ));

        $fieldset->addField('company_logo', 'logoimage', array(
            'label' => Mage::helper('affilinet')->__('Company logo'),
            'required' => true,
            'name' => 'company_logo'
        ));

        $escaping = $fieldset->addField('escaping', 'text', array(
            'label' => Mage::helper('affilinet')->__('Enclosure characters'),
            'required' => true,
            'name' => 'escaping',
            'after_element_html' => '<div class="field-tooltip"><div>'.Mage::helper('affilinet')->__('Please type in the sort of enclosure character for your products.').'</div></div>'
        ));

        $fieldset->addField('delimiter_escape', 'select', array(
            'label' => Mage::helper('affilinet')->__('Escape'),
            'required' => true,
            'options' => array(
                'backslash' => Mage::helper('affilinet')->__('Backslash'),
                'double' => Mage::helper('affilinet')->__('Doubling'),
                'no' => Mage::helper('affilinet')->__('No')
            ),
            'name' => 'delimiter_escape',
            'after_element_html' => '<div class="field-tooltip"><div>'.Mage::helper('affilinet')->__('Escaping character for strings').'</div></div>'
        ));

        $fieldset->addField('delimiter', 'text', array(
            'label' => Mage::helper('affilinet')->__('Delimiter'),
            'required' => true,
            'name' => 'delimiter',
            'after_element_html' => '<div class="field-tooltip"><div>'.Mage::helper('affilinet')->__('General delimiter of fields in the feed').'</div></div>'
        ));

        /*
        $fieldset->addField('language', 'select', array(
            'label' => Mage::helper('affilinet')->__('Language'),
            'required' => true,
            'options' => array(
                'Deutsch' => Mage::helper('affilinet')->__('Deutsch'),
                'English' => Mage::helper('affilinet')->__('English')
            ),
            'name' => 'language',
        ));
        */

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'select', array(
                'name' => 'store_id',
                'label' => Mage::helper('affilinet')->__('Store View'),
                'title' => Mage::helper('affilinet')->__('Store View'),
                'required' => true,
                'values' => Mage::getSingleton('adminhtml/system_store')
                        ->getStoreValuesForForm(false, true),
                'value' => 0,
                'after_element_html' => '<div class="field-tooltip"><div>'.Mage::helper('affilinet')->__('Select the Store whose products shall be included in the feed.').'</div></div>'
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name' => 'store_id',
                'value' => Mage::app()->getStore(true)->getId(),
            ));
        }

        $fieldset->addField('encoding', 'select', array(
            'label' => Mage::helper('affilinet')->__('Encoding'),
            'required' => true,
            'options' => array(
                'UTF-8' => Mage::helper('affilinet')->__('UTF-8'),
                'ISO-8859-1' => Mage::helper('affilinet')->__('ISO-8859-1')
            ),
            'name' => 'encoding',
            'after_element_html' => '<div class="field-tooltip"><div>'.Mage::helper('affilinet')->__('Encoding standards. Preferable, the encoding should be UTF-8 but the default value needs to be aligned with the encoding used when generating the feed which is in most cases the encoding of the ecommerce shop system database.').'</div></div>'
        ));

        $fieldset->addField('column_title', 'checkbox', array(
            'label' => Mage::helper('affilinet')->__('Column title'),
            'required' => false,
            'checked' => (isset($values['column_title']) && $values['column_title'] == 1) ? true : false,
            'onclick'   => 'this.value = this.checked ? 1 : 0;',
            'name' => 'column_title',
            'after_element_html' => '<div class="field-tooltip"><div>'.Mage::helper('affilinet')->__('Activate/deactivate column titles in the feed').'</div></div>'
        ));

        $fieldset->addField('last_delimiter', 'checkbox', array(
            'label' => Mage::helper('affilinet')->__('Delimiter after last column'),
            'required' => false,
            'checked' => (isset($values['last_delimiter']) && $values['last_delimiter'] == 1) ? true : false,
            'onclick'   => 'this.value = this.checked ? 1 : 0;',
            'name' => 'last_delimiter',
            'after_element_html' => '<div class="field-tooltip"><div>'.Mage::helper('affilinet')->__('General delimiter of fields in the feed').'</div></div>'
        ));

        $fieldset->addField('filter_active', 'checkbox', array(
            'label' => Mage::helper('affilinet')->__('Filter active articles'),
            'required' => false,
            'checked' => (isset($values['filter_active']) && $values['filter_active'] == 1) ? true : false,
            'onclick'   => 'this.value = this.checked ? 1 : 0;',
            'name' => 'filter_active',
            'after_element_html' => '<div class="field-tooltip"><div>'.Mage::helper('affilinet')->__('Activate the export of products only for active products').'</div></div>'
        ));

        $fieldset->addField('filter_stock', 'checkbox', array(
            'label' => Mage::helper('affilinet')->__('Filter active stock'),
            'required' => false,
            'checked' => (isset($values['filter_stock']) && $values['filter_stock'] == 1) ? true : false,
            'onclick'   => 'this.value = this.checked ? 1 : 0;',
            'name' => 'filter_stock',
            'after_element_html' => '<div class="field-tooltip"><div>'.Mage::helper('affilinet')->__('Activate the export of products only for products which can be ordered.').'</div></div>'
        ));

        $fieldset->addField('one_variation', 'checkbox', array(
            'label' => Mage::helper('affilinet')->__('Only one product variation'),
            'required' => false,
            'checked' => (isset($values['one_variation']) && $values['one_variation'] == 1) ? true : false,
            'onclick'   => 'this.value = this.checked ? 1 : 0;',
            'name' => 'one_variation',
            'after_element_html' => '<div class="field-tooltip"><div>'.Mage::helper('affilinet')->__('Product variations should be provided as separate datasets. Especially for retargeting purposes, special product feeds may be generated which holds only one variation of a product in order to avoid multiple similar products in a dynamic product banner which just deviate by color, size or price.').'</div></div>'
        ));

        if (!isset($values['company_logo']) || !$values['company_logo']){
            $values['company_logo'] = Mage::getSingleton('affilinet/config')->getCompanyLogo();
        }

        $form->setValues($values);
        $form->setAction($this->getUrl('*/*/save'));
        $form->setMethod('post');
        $form->setId('edit_form');

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
