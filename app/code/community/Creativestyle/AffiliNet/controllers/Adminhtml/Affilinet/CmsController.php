<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
class Creativestyle_AffiliNet_Adminhtml_Affilinet_CmsController extends Mage_Adminhtml_Controller_Action {

    public function introductionAction() {
        $this->loadLayout()
            ->_setActiveMenu('affilinet/introduction')
            ->_addBreadcrumb($this->__('affilinet'), $this->__('affilinet'))
            ->_addBreadcrumb($this->__('Introduction'), $this->__('Introduction'))
            ->_title($this->__('affilinet'))
            ->_title($this->__('Introduction'))
            ->renderLayout();
    }

    public function signupAction() {
        $this->loadLayout()
            ->_setActiveMenu('affilinet/signup')
            ->_addBreadcrumb($this->__('affilinet'), $this->__('affilinet'))
            ->_addBreadcrumb($this->__('Signup'), $this->__('Signup'))
            ->_title($this->__('affilinet'))
            ->_title($this->__('Signup'))
            ->renderLayout();
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('admin/affilinet/cms');
    }

}
