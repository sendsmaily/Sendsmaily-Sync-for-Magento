<?php

class Sendsmaily_Subscribe_Block_Newsletter_Subscriber_Grid extends Mage_Adminhtml_Block_Newsletter_Subscriber_Grid
{
	/**
	 * prepare grid columns
	 * @return void
	 */
	protected function _prepareColumns(){
		// return parent function
		$result = parent::_prepareColumns();
		
		// add sendsmaily export type
		$this->addExportType('*/*/exportSendsmaily', 'Sendsmaily');
		
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
		
		// restructure data
		$data = array();
		foreach($this->getCollection() as $item){
			$data[] = array(
				'email' => $item->getEmail(),
				'firstname' => $item->getCustomerFirstname(),
				'lastname' => $item->getCustomerLastname(),
				'is_subscribed' => ($item->getSubscriberStatus() == '1' ? '1' : '0')
			);
		}
		
		return $data;
	}
}
