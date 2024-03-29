<?php

class Creativestyle_AffiliNet_Block_Adminhtml_Datafeed_Renderer_Filter
    extends Mage_Adminhtml_Block_Widget
    implements Varien_Data_Form_Element_Renderer_Interface
{

    public function __construct()
    {
        $this->setTemplate('creativestyle/affilinet/datafeed/filter.phtml');
    }

    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

    protected function _prepareLayout()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label' => Mage::helper('catalog')->__('Add new'),
                'onclick' => 'return filterControl.addItem()',
                'class' => 'add'
            ));
        $button->setName('add_filter_item_button');

        $this->setChild('add_button', $button);
        return parent::_prepareLayout();
    }
}