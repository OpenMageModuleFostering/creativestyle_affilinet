<?php

class Creativestyle_AffiliNet_Block_Adminhtml_Datafeed_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('datafeedGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('affilinet/datafeed')->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => Mage::helper('affilinet')->__('ID'),
            'align' => 'right',
            'width' => '10px',
            'index' => 'id',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('affilinet')->__('Name'),
            'align' => 'right',
            'index' => 'name',
        ));

        $this->addColumn('start_at', array(
            'header' => Mage::helper('affilinet')->__('Start at'),
            'align' => 'right',
            'index' => 'start_at',
            'renderer' => 'Creativestyle_AffiliNet_Block_Adminhtml_Datafeed_Renderer_Startat'
        ));

        $this->addColumn('url', array(
            'header' => Mage::helper('affilinet')->__('URL'),
            'align' => 'right',
            'index' => 'url',
            'renderer' => 'Creativestyle_AffiliNet_Block_Adminhtml_Datafeed_Renderer_Url'
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    } //test
}