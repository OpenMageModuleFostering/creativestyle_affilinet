<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Grzegorz Bogusz / creativestyle GmbH <support@creativestyle.de>
 */

class Creativestyle_AffiliNet_Adminhtml_Affilinet_DatafeedController extends Mage_Adminhtml_Controller_Action
{

    protected function _getLogoUploadDir($absolutePath = true) {
        $uploadDir = 'affilinet' . DS . 'logo' . DS . 'datafeed';
        if ($absolutePath) {
            $uploadDir = Mage::getBaseDir('media') . DS . $uploadDir;
        }
        return $uploadDir;
    }

    public function indexAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('affilinet/datafeed')
            ->_addBreadcrumb(Mage::helper('affilinet')->__('Product Data Feed'), Mage::helper('affilinet')->__('Product Data Feed'));
        $this->_addContent($this->getLayout()->createBlock('affilinet/adminhtml_datafeed'));
        $this->_title(Mage::helper('affilinet')->__('Product Data Feed'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('affilinet');
        $this->_title(Mage::helper('affilinet')->__('Product Data Feed'));

        $this->_addBreadcrumb(Mage::helper('affilinet')->__('Product Data Feed'), Mage::helper('affilinet')->__('Product Data Feed'));
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock('affilinet/adminhtml_datafeed_edit'))->_addLeft($this->getLayout()
            ->createBlock('affilinet/adminhtml_datafeed_edit_tabs'));

        $this->renderLayout();
    }

    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        $id = (int)$this->getRequest()->getParam('id');

        if ($data) {
            $time = Mage::getModel('core/date')->timestamp(time());
            $model = Mage::getModel('affilinet/datafeed');

            if($id){
                $data['id'] = $id;
            }else{
                $data['created_at'] = $time;
            }
            $data['updated_at'] = $time;

            $data['language'] = 'English';

            if(isset($data['cron_start']) && !empty($data['cron_start'])){
                $data['cron_start'] = implode(',', $data['cron_start']);
            }

            $checkboxes = array(
                'column_title' => (isset($data['column_title']) && $data['column_title']) ? 1 : 0,
                'last_delimiter' => (isset($data['last_delimiter']) && $data['last_delimiter']) ? 1 : 0,
                'filter_active' => (isset($data['filter_active']) && $data['filter_active']) ? 1 : 0,
                'filter_stock' => (isset($data['filter_stock']) && $data['filter_stock']) ? 1 : 0,
                'one_variation' => (isset($data['one_variation']) && $data['one_variation']) ? 1 : 0,
                'send_test' => (isset($data['send_test']) && $data['send_test']) ? 1 : 0
            );

            $data = array_merge($data, $checkboxes);

            $fieldMapper = array();
            if(isset($data['field_mapper']) && $data['field_mapper']){
                $fieldMapper = $data['field_mapper'];
                unset($data['field_mapper']);
            }

            $fieldFilter = array();
            if(isset($data['field_filter']) && $data['field_filter']){
                $fieldFilter = $data['field_filter'];
                unset($data['field_filter']);
            }

            if (isset($_FILES['company_logo']['name']) && $_FILES['company_logo']['name']) {

                $targetDir = $this->_getLogoUploadDir();

                try {
                    $uploader = new Varien_File_Uploader('company_logo');
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(false);
                    $uploadResult = $uploader->save($targetDir, $_FILES['company_logo']['name']);

                    if (file_exists($uploadResult['path'] . DS . $uploadResult['name'])) {
                        $imageObj = new Varien_Image($uploadResult['path'] . DS . $uploadResult['name']);
                        $imageObj->constrainOnly(true);
                        $imageObj->keepAspectRatio(true);
                        $imageObj->keepFrame(true);
                        $imageObj->backgroundColor(array(255,255,255));
                        $imageObj->resize(120, 40);
                        $imageObj->save($uploadResult['path'] . DS . $uploadResult['name']);
                    }

                } catch (Exception $e) {
                    Mage::log('Creativestyle_AffiliNet error - script could not save company_logo in datafeed', 0, 'affilinet.log');
                }

                $data['company_logo'] = $this->_getLogoUploadDir(false) . DS . $uploadResult['name'];

            } elseif (isset($data['company_logo'])) {
                if (isset($data['company_logo']['delete']) && $data['company_logo']['delete'] == 1) {
                    if (file_exists($this->_getLogoUploadDir() . DS . basename($data['company_logo']['value']))) {
                        unlink($this->_getLogoUploadDir() . DS . basename($data['company_logo']['value']));
                    }
                    $data['company_logo'] = '';
                } else if (isset($data['company_logo']['value'])) {
                    $data['company_logo'] = $data['company_logo']['value'];
                }
            }

            if (!isset($data['cron_file']) || empty($data['cron_file'])){
                $data['cron_file'] = $data['name'] . '.csv';
            }

            if (strpos($data['cron_file'], '.csv') === false){
                $data['cron_file'] = $data['cron_file'] . '.csv';
            }

            if($id){
                $datafeed = Mage::getModel('affilinet/datafeed')->load($id);
                if($datafeed && ($datafeed->getLastPage() != 0 || $datafeed->getCronLock() != 0)){
                    $filepath = $path = Mage::getBaseDir('media') . DS . 'creativestyle' . DS . 'affilinet' . DS . 'datafeed' . DS;
                    if(file_exists($filepath . $data['cron_file'])){
                        unlink($filepath . $data['cron_file']);
                    }
                    if(file_exists($filepath . 'temp' . DS . $data['cron_file'])){
                        unlink($filepath . 'temp' . DS . $data['cron_file']);
                    }
                }
            }
            $data['last_page'] = 0;
            $data['cron_lock'] = 0;

            try {
                $model->setData($data);
                $model->save();
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }

            $datafeedId = $model->getId();

            if($fieldMapper){
                Mage::getModel('affilinet/mapper')->cleanData($datafeedId);

                $position = 1;
                foreach($fieldMapper AS $field){
                    if($field['delete'] != '1'){
                        $mapperModel = Mage::getModel('affilinet/mapper');
                        $mapperData = array(
                            'datafeed_id' => $datafeedId,
                            'title' => $field['title'],
                            'preffix' => $field['preffix'],
                            'fieldname' => $field['fieldname'],
                            'suffix' => $field['suffix'],
                            'concatenation' => isset($field['concatenation']) ? 1 : 0,
                            'position' => $position
                        );

                        $position++;

                        $mapperModel->setData($mapperData);
                        $mapperModel->save();
                    }
                }
            }

            if($fieldFilter){
                Mage::getModel('affilinet/filter')->cleanData($datafeedId);

                $position = 1;
                foreach($fieldFilter AS $field){
                    if($field['delete'] != '1'){
                        $filterModel = Mage::getModel('affilinet/filter');
                        $filterData = array(
                            'datafeed_id' => $datafeedId,
                            'fieldname' => $field['fieldname'],
                            'filter' => $field['filter'],
                            'position' => $position
                        );

                        $position++;

                        $filterModel->setData($filterData);
                        $filterModel->save();
                    }
                }
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('affilinet')->__('Item was successfully saved'));
            Mage::getSingleton('adminhtml/session')->setFormData(false);
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('*/*/edit', array('id' => $model->getId()));
                return;
            }
            $this->_redirect('*/*/');
            return;
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('affilinet')->__('Unable to find item to save'));
        $this->_redirect('');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('affilinet/datafeed');
                $model->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('affilinet')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function previewAction()
    {
        $this->_forward('generate', null, null, array(
            'preview' => true,
            'id' => $this->getRequest()->getParam('id')
        ));
    }

    public function generateAction()
    {
        $id = $this->getRequest()->getParam('id');
        if($id){
            $path = Mage::getBaseDir() . DS . 'media' . DS . 'creativestyle' . DS . 'affilinet' . DS . 'datafeed' . DS . 'temp';
            $dirExists = Mage::getModel('affilinet/datafeed')->checkDir($path);
            if(!$dirExists){
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('affilinet')->__('The directory is missing and the script has been unable to create it. Please create it manually. Path %s', $path));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }

            $preview = $this->getRequest()->getParam('preview', false);
            $datafeed = Mage::getModel('affilinet/datafeed')->load($id)->getData();

            $csv = Mage::getModel('affilinet/datafeed')->prepareCsv($id, $preview, $datafeed);

            if($preview){
                echo '<table style="border-collapse:collapse;"><tbody>';
                foreach($csv AS $row){
                    echo '<tr>';
                    foreach($row AS $r){
                        echo '<td style="border:1px solid #333; padding:5px;">' . $r . '</td>';
                    }
                    echo '</tr>';
                }
                echo '</tbody></table>';
                return;
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('affilinet')->__('File is generating. You will be able to download file when it finish'));
            Mage::getSingleton('adminhtml/session')->setFormData(false);
            if ($id) {
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }
            $this->_redirect('*/*/');
            return;
        }
    }

    public function generatestopAction()
    {
        $id = $this->getRequest()->getParam('id', 0);
        if($id){
            Mage::getModel('affilinet/datafeed')->stopGenerating($id);

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('affilinet')->__('Generating feed was successfully stopped'));
            Mage::getSingleton('adminhtml/session')->setFormData(false);
            $this->_redirect('*/*/edit', array('id' => $id));
            return;
        }else{
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('affilinet')->__('Unable to stop'));
            $this->_redirect('*/*/');
            return;
        }
    }

    public function sendfeedAction()
    {
        $id = $this->getRequest()->getParam('id', 0);
        if($id){
            Mage::getModel('affilinet/datafeed')->sendFeed($id);

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('affilinet')->__('Feed was successfully sent to Affili.net'));
            Mage::getSingleton('adminhtml/session')->setFormData(false);
            $this->_redirect('*/*/edit', array('id' => $id));
            return;
        }else{
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('affilinet')->__('Unable to send feed'));
            $this->_redirect('*/*/');
            return;
        }
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('admin/affilinet/datafeed');
    }

}
