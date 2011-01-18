<?php

require_once('Mage/Adminhtml/controllers/Newsletter/SubscriberController.php');

class Sendsmaily_Sync_Newsletter_SubscriberController extends Mage_Adminhtml_Newsletter_SubscriberController
{
	/**
	 * export selected addresses to sendsmaily
	 * @return void
	 */
	public function exportSsAction(){
	  if(Mage::getStoreConfig('newsletter/sendsmaily/active') == false){
	    $this->_getSession()->addError($this->_getHelper()->__('Module is not activated!'));
      return $this->_redirectReferer();
    }
    
		// restructure data
		$data = $this->getLayout()->createBlock('adminhtml/newsletter_subscriber_grid')->toSendsmaily();
		
		// make the request (in chunks of 500)
		$chunks = array_chunk($data, 500);
    foreach($chunks as $chunk){
		  $result = Mage::getModel('sync/request')->subscribe($chunk);
      
      // handle errors
      if(is_array($result) and isset($result['code']) and $result['code'] >= 200){
        $this->_getSession()->addError($this->_getHelper()->__($result['message']));
        return $this->_redirectReferer();
      }
    }
		
		$this->_getSession()->addSuccess($this->_getHelper()->__('Newsletter subscriptions synchronized.'));
		return $this->_redirectReferer();
	}
  
  /**
   * retrieve sync base helper
   * @return Sendsmaily_Sync_Helper_Data
   */
  protected function _getHelper(){
    return Mage::helper('sync');
  }
}
