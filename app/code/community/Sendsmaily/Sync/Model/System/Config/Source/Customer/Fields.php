<?php

class Sendsmaily_Sync_Model_System_Config_Source_Customer_Fields
{
  protected $_options;

  public function toOptionArray($isMultiselect=false){
    if(!$this->_options){
      $this->_options = array(
        array('value' => 'subscription_type', 'label' => Mage::helper('sync')->__('Subscription Type')),
        array('value' => 'customer_group', 'label' => Mage::helper('sync')->__('Customer Group')),
        array('value' => 'firstname', 'label' => Mage::helper('sync')->__('Firstname')),
        array('value' => 'lastname', 'label' => Mage::helper('sync')->__('Lastname')),
        array('value' => 'store_code', 'label' => Mage::helper('sync')->__('Store Code'))
      );
    }
    
    $options = $this->_options;
    if(!$isMultiselect){
      array_unshift($options, array('value' => '', 'label' => Mage::helper('sync')->__('--Please Select--')));
    }
    
    return $options;
  }
}
