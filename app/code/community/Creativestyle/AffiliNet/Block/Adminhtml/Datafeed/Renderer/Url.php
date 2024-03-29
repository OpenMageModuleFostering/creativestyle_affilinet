<?php

class Creativestyle_AffiliNet_Block_Adminhtml_Datafeed_Renderer_Url extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row) {
        $path = Mage::getBaseDir('media') . DS . 'creativestyle' . DS . 'affilinet' . DS . 'datafeed' . DS;
        $filename = $row['cron_file'];
        if(file_exists($path . $filename)){
            $file = Mage::getBaseUrl('media') . 'creativestyle/affilinet/datafeed/' . $filename;
                return '<a href="' . $file . '" download>' . $file . '</a>';
        }elseif($row['last_page']){
            return Mage::helper('affilinet')->__('Generating..');
        }
        return null;
    }
}