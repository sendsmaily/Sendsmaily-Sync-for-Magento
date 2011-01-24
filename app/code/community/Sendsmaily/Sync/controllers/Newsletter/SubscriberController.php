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
