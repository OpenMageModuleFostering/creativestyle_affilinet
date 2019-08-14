<?php

class Creativestyle_AffiliNet_Block_Adminhtml_Datafeed_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_blockGroup = 'affilinet';
        $this->_mode = 'edit';
        $this->_controller = 'adminhtml_datafeed';

        $disabled = '';

        if ($this->getRequest()->getParam($this->_objectId)) {
            $id = $this->getRequest()->getParam($this->_objectId);
            $data = Mage::getModel('affilinet/datafeed')->load($id);
            $mapperData = Mage::getModel('affilinet/mapper')
                ->getCollection()
                ->addFieldToFilter('datafeed_id', $id)
                ->toArray();

            if(isset($mapperData['items'])){
                $data['field_mapper'] = $mapperData['items'];
            }

            $filterData = Mage::getModel('affilinet/filter')
                ->getCollection()
                ->addFieldToFilter('datafeed_id', $id)
                ->toArray();

            if(isset($filterData['items'])){
                $data['field_filter'] = $filterData['items'];
            }

            Mage::register('frozen_data', $data);

            if($data['field_mapper']){
                $disabled = (isset($data['last_page']) && $data['last_page']) ? 'disabled' : '';

                $this->_addButton('preview', array(
                    'label' => Mage::helper('affilinet')->__('Preview'),
                    'onclick' => "openFeedPreview()",
                    'class' => 'save',
                    'disabled' => $disabled
                ), 10);

                $this->_addButton('generate', array(
                    'label' => Mage::helper('affilinet')->__('Generate File'),
                    'onclick' => "setLocation('" . $this->getUrl('*/*/generate/', array('id' => $id)) . "')",
                    'class' => 'save',
                    'disabled' => $disabled
                ), 10);

                if($disabled){
                    $this->_addButton('generatestop', array(
                        'label' => Mage::helper('affilinet')->__('Stop generating'),
                        'onclick' => 'deleteConfirm(\''. Mage::helper('adminhtml')->__('Are you sure you want to do this?')
                            .'\', \'' . $this->getUrl('*/*/generatestop/', array('id' => $id)) . '\')',
                        'class' => 'save',
                    ), 10);
                }
            }
        }
        $this->removeButton('reset');
        $this->removeButton('save');

        if($disabled){
            $onclickSave = 'deleteConfirm(\''. Mage::helper('adminhtml')->__('If you save it now, file generating will be stopped')
                .'\', editForm.submit())';
        }else{
            $onclickSave = 'editForm.submit();';
        }

        $this->_addButton('save', array(
            'label'     => Mage::helper('adminhtml')->__('Save'),
            'onclick' => $onclickSave,
            'class'     => 'save',
        ), 1);

        if($disabled){
            $onclickSaveCE = 'deleteConfirm(\''. Mage::helper('adminhtml')->__('If you save it now, file generating will be stopped')
                .'\', \'' . $this->_getSaveAndContinueUrl() . '\')';
        }else{
            $onclickSaveCE = 'saveAndContinueEdit(\'' . $this->_getSaveAndContinueUrl() . '\')';
        }
        $this->_addButton('save_and_continue', array(
            'label' => Mage::helper('affilinet')->__('Save and Continue Edit'),
            'onclick' => $onclickSaveCE,
            'class' => 'save'
        ), 10);
        $this->_formScripts[] = " function saveAndContinueEdit(){ editForm.submit($('edit_form').action + 'back/edit/') } ";
    }

    public function getHeaderText() {
        return Mage::helper('affilinet')->__('Product Data Feed');
    }

    protected function _getSaveAndContinueUrl() {
        return $this->getUrl('*/*/save', array(
            '_current' => true,
            'back' => 'edit',
            'tab' => '{{tab_id}}'
        ));
    }
}