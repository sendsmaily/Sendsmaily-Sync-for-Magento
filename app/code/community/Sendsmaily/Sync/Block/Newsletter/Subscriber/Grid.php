<?php

class Sendsmaily_Sync_Block_Newsletter_Subscriber_Grid extends Mage_Adminhtml_Block_Newsletter_Subscriber_Grid
{
	/**
	 * prepare grid columns
	 * @return void
	 */
	protected function _prepareColumns(){
		// return parent function
		$result = parent::_prepareColumns();
		
		// add sendsmaily export type
		if(Mage::getStoreConfig('newsletter/sendsmaily/active') == true){
		  $this->addExportType('*/*/exportSs', 'Sendsmaily');
    }
		
		return $result;
	}
	
	/**
	 * restructure collection to sendsmaily-readable form
	 * @return array
	 */
	public function toSendsmaily(){
		$this->_isExport = true;
    $this->_prepareGrid();
    $this->getCollection()->getSelect()->limit();
    $this->getCollection()->setPageSize(0);
    $this->getCollection()->load();
    $this->_afterLoadCollection();
    
    // get fields to collect
    $fields = (string)Mage::getStoreConfig('newsletter/sendsmaily/fields');
    $fields = (!empty($fields) ? explode(',', $fields) : array());
		
		// restructure data
		$data = array();
		foreach($this->getCollection() as $item){
		  $isCustomer = $item->getCustomerId() > 0 ? true : false;
      
      // generate additional data fields
      $extra = array();
      if(count($fields) > 0){
		    $extra = array_combine($fields, array_fill(0, count($fields), ''));
      }
      
      // get subscription type
      if(in_array('subscription_type', $fields)){
        $extra['subscription_type'] = ($isCustomer ? 'customer' : 'guest');
      }
      
      // get store code
      if(in_array('store_code', $fields)){
        $extra['store_code'] = Mage::app()->getStore($item->getStoreId())->getCode();
      }
      
      // collect customer data
      if($isCustomer){
        $customer = Mage::getSingleton('customer/customer')->load($item->getCustomerId());
        
        // get customer group
        if(in_array('customer_group', $fields)){
          $code = Mage::getSingleton('customer/group')->load($customer->getGroupId())->getCode();
          $extra['customer_group'] = $code;
        }
        
        // get customer firstname
        if(in_array('firstname', $fields)){
          $extra['firstname'] = $item->getCustomerFirstname();
        }
        
        // get customer lastname
        if(in_array('lastname', $fields)){
          $extra['lastname'] = $item->getCustomerLastname();
        }
        
        // @todo: add phone, street, city, region and country
      }
      
			$data[] = array_merge($extra, array(
				'email' => $item->getEmail(),
				'is_unsubscribed' => ($item->isSubscribed() ? 0 : 1)
			));
		}
		
		return $data;
	}
}
