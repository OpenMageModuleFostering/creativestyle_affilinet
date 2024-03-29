<?php

class Creativestyle_AffiliNet_Block_Adminhtml_Report_Order_Renderer_ActionInDays extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row) {
        $time = Mage::getModel('core/date')->timestamp(time());
        $toTime = strtotime($row->getAutoOrderManagementActionDate());

        if($toTime){
            $days = ($toTime - $time) / (3600*24);

            if($days){
                return ceil($days);
            }
        }

        return false;
    }
}