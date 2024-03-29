<?php

class Creativestyle_AffiliNet_Block_Adminhtml_Datafeed_Renderer_Sendfeed
    extends Mage_Adminhtml_Block_Widget
    implements Varien_Data_Form_Element_Renderer_Interface
{
    public function __construct()
    {
        $this->setTemplate('creativestyle/affilinet/datafeed/sendfeed.phtml');
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

    public function getSendUrl()
    {
        $id = $this->getRequest()->getParam('id', 0);
        return $this->getUrl('*/*/sendfeed', array('id' => $id));
    }

    public function isDisabled()
    {
        $frozen = Mage::registry('frozen_data');
        if ($frozen){
            if(is_array($frozen)){
                $values = $frozen;
            }else{
                $values = $frozen->getData();
            }

            if(isset($values['last_page']) && !$values['last_page']){
                $path = Mage::getBaseDir('media') . DS . 'creativestyle' . DS . 'affilinet' . DS . 'datafeed' . DS;
                $filename = $values['cron_file'];
                if(file_exists($path . $filename)){
                    return false;
                }
            }
        }
        return true;
    }
}