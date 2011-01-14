<?php

class Sendsmaily_Subscribe_Model_Request
{
	/**
	 * @var post request url
	 */
	protected $_requestUrl = 'https://admin.youhavemail.eu/api/magento-import/';
	
	/**
	 * @var request parameters
	 */
	protected $_params = array();

	/**
	 * subscribe addresses to list
	 * @param string $params [optional]
	 * @return array|boolean
	 */
	public function subscribe($params=array()){
		if(!is_array($params)){ return false; }
		
		// set system params
		$this->_params = array(
			'key' => Mage::getStoreConfig('newsletter/sendsmaily/api_key'),
			'autoresponder' => Mage::getStoreConfig('newsletter/sendsmaily/autoresponder_id')
		);
		
		// initialize client connection
		$client = curl_init();
		
		// parameters
		$params = array_merge($params, $this->_params, array(
			'remote' => 1
		));
		
		// set request parameters
		curl_setopt($client, CURLOPT_URL, $this->_requestUrl);
		curl_setopt($client, CURLOPT_HEADER, false);
		curl_setopt($client, CURLOPT_POST, true);
		curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($client, CURLOPT_POSTFIELDS, http_build_query($params));
		
		// make the request
		try {
			$response = curl_exec($client);
			curl_close($client);
			
			// parse json
			$response = Zend_Json::decode($response);
		}catch(Exception $e){
			return array(
				'code' => 200,
				'message' => 'Could not connect to Sendsmaily Server. Please try again later.'
			);
		}
		
		return $response;
	}
}
