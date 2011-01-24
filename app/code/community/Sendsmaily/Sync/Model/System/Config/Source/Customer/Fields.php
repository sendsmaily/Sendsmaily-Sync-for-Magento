<?php

/**
 * Sendsmaily Sync
 * Module to synchronize Magento newsletter subscribers to Sendsmaily
 * Copyright (C) 2010 Sendsmaily
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
