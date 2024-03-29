<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
abstract class Creativestyle_AffiliNet_Model_Resource_Api_Model_Collection_Abstract extends Varien_Data_Collection {

    /**
     * Model name
     *
     * @var string
     */
    protected $_model;

    protected $_store = null;

    protected $_additionalData = array();

    protected $_traceData = array();

    /**
     * Standard resource collection initialization
     *
     * @param string $model
     * @return Creativestyle_AffiliNet_Model_Resource_Order_Collection
     */
    protected function _init($model) {
        $this->setModel($model);
        return $this;
    }

    protected function _getApi() {
        return Mage::getSingleton('affilinet/api')->setStore($this->_store);
    }

    protected function _extractAdditionalDataFromSoapResponse($soapResponse, $excludeKeys = array()) {
        $additionalData = array();
        foreach ($soapResponse as $key => $value) {
            if (!in_array($key, $excludeKeys)) {
                $additionalData[$key] = $value;
            }
        }
        return $additionalData;
    }

    public function load($printQuery = false, $logQuery = false) {
        if ($this->isLoaded()) {
            return $this;
        }

        $data = $this->_getData();

        if (array_key_exists('data', $data) && !empty($data['data'])) {
            if (is_array($data['data'])) {
                $dataRows = $data['data'];
            } else {
                $dataRows = array($data['data']);
            }
            foreach ($dataRows as $row) {
                $item = $this->getNewEmptyItem();
                $item->createFromSoapResponse($row);
                $this->addItem($item);
            }
        }

        if (array_key_exists('additional', $data) && !empty($data['additional'])) {
            foreach ($data['additional'] as $key => $value) {
                if ($key == 'TotalCount') {
                    $this->_totalRecords = (int)$value;
                } else {
                    $this->setAdditionalData($key, $value);
                }
            }
        }

        $this->_setIsLoaded();
        return $this;

    }

    /**
     * Set model name for collection items
     *
     * @param string $model
     * @return Creativestyle_AffiliNet_Model_Resource_Order_Collection
     */
    public function setModel($model) {
        if (is_string($model)) {
            $this->_model = $model;
            $this->setItemObjectClass(Mage::getConfig()->getModelClassName($model));
        }
        return $this;
    }

    public function getStore() {
        return $this->_store;
    }

    public function setStore($store) {
        $this->_store = $store;
        return $this;
    }

    public function hasAdditionalData($key) {
        if (array_key_exists($key, $this->_additionalData)) {
            return true;
        }
        return false;
    }

    public function getAdditionalData($key) {
        if ($this->hasAdditionalData($key)) {
            return $this->_additionalData[$key];
        }
        return null;
    }

    public function setAdditionalData($key, $value) {
        $this->_additionalData[$key] = $value;
        return $this;
    }

}
