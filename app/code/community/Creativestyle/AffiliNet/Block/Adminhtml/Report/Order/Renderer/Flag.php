<?php

class Creativestyle_AffiliNet_Block_Adminhtml_Report_Order_Renderer_Flag extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row) {
        $html = '';
        if($row->getSubIds()){
            $subs = $row->getSubIds()->toArray();

            if(isset($subs['p_sub1']) && $subs['p_sub1']){
                $html .= Mage::helper('affilinet')->__('pSub1') . ': ' . urldecode($subs['p_sub1']) . '</br>';
            }
            if(isset($subs['p_sub2']) && $subs['p_sub2']){
                $html .= Mage::helper('affilinet')->__('pSub2') . ': ' . urldecode($subs['p_sub2']) . '</br>';
            }
            if(isset($subs['p_sub3']) && $subs['p_sub3']){
                $html .= Mage::helper('affilinet')->__('pSub3') . ': ' . urldecode($subs['p_sub3']) . '</br>';
            }
            if(isset($subs['p_sub4']) && $subs['p_sub4']){
                $html .= Mage::helper('affilinet')->__('pSub4') . ': ' . urldecode($subs['p_sub4']) . '</br>';
            }
        }

        return $html;
    }
}