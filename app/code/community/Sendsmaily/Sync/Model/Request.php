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

class Sendsmaily_Sync_Model_Request
{
	/**
	 * post request url
   * @var string
	 */
	protected $_requestUrl = '';
	
	/**
   * request parameters
	 * @var array
	 */
	protected $_params = array();
  
  /**
   * load configuration data
   * @return void
   */
  public function __construct(){
    // build request url
    $domain = Mage::getStoreConfig('newsletter/sendsmaily/domain');
    $this->_requestUrl = 'https://' . $domain . '.sendsmaily.net/api/magento-import/';
  }

	/**
	 * subscribe addresses to list
	 * @param string $params [optional]
	 * @return array|boolean
	 */
	public function subscribe($params=array()){
		if(!is_array($params)){ return false; }
		
		// set system params
		$this->_params = array(
			'key' => Mage::getStoreConfig('newsletter/sendsmaily/api_key')
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
