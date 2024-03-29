<?php

/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
abstract class Creativestyle_AffiliNet_Model_Api_Model_Abstract extends Varien_Object {

    /**
     * Name of the resource collection model
     *
     * @var string
     */
    protected $_resourceCollectionName;

    /**
     * Standard model initialization
     *
     * @param string $resourceCollectionName
     * @param string $idFieldName
     * @return Mage_Core_Model_Abstract
     */
    protected function _init($resourceCollectionName, $idFieldName = 'id') {
        $this->_setResourceCollectionName($resourceCollectionName);
        $this->setIdFieldName($idFieldName);
    }

    /**
     * Set resource collection name
     *
     * @param string $resourceCollectionName
     */
    protected function _setResourceCollectionName($resourceCollectionName) {
        $this->_resourceCollectionName = $resourceCollectionName;
    }

    protected function _convertSoapResponseToData($response, $returnObject = false) {
        switch (gettype($response)) {
            case 'object':
            case 'array':
                $data = array();
                foreach ($response as $key => $value) {
                    if (strpos($key, 'KeyValueOf') === 0 && gettype($value) == 'array') {
                        foreach ($value as $dictionaryItem) {
                            if (is_object($dictionaryItem) && property_exists($dictionaryItem, 'Key') && property_exists($dictionaryItem, 'Value')) {
                                $data[$this->_underscore($dictionaryItem->Key)] = $this->_convertSoapResponseToData($dictionaryItem->Value, true);
                            }
                        }
                    } else {
                        $key = str_replace('URL', 'Url', $key);
                        $data[$this->_underscore($key)] = $this->_convertSoapResponseToData($value, true);
                    }
                }
                if ($returnObject) {
                    $data = new Varien_Object($data);
                }
                return $data;
            default:
                return $response;
        }
    }
    /**
     * Get collection instance
     *
     * @return object
     */
    public function getResourceCollection() {
        if (empty($this->_resourceCollectionName)) {
            Mage::throwException(Mage::helper('core')->__('Model collection resource name is not defined.'));
        }
        return Mage::getResourceModel($this->_resourceCollectionName);
    }

    public function getCollection() {
        return $this->getResourceCollection();
    }

    public function createFromSoapResponse($response) {
        $this->setData($this->_convertSoapResponseToData($response))->setDataChanges(false);
        return $this;
    }

}
