<?php

class Sendsmaily_Subscribe_IndexController extends Mage_Core_Controller_Front_Action
{
	/**
	 * export selected addresses to sendsmaily
	 * @return void
	 */
	public function exportAction(){
		// restructure data
		$data = $this->getLayout()->createBlock('adminhtml/newsletter_subscriber_grid')->toSendsmaily();
		
		// create request object
		$request = Mage::getModel('subscribe/request');
		
		// make the request
		$result = $request->subscribe($data);
		
		// handle errors
		if(is_array($result) and isset($result['code']) and $result['code'] >= 200){
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('subscribe')->__($result['message']));
			return $this->_redirectReferer();
		}
		
		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('subscribe')->__('Newsletter subscriptions syncronized.'));
		return $this->_redirectReferer();
	}
}
