<?php

class Creativestyle_AffiliNet_Block_Adminhtml_Datafeed_Renderer_Startat extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row) {
        return str_replace(',', ':', $row['cron_start']);
    }
}