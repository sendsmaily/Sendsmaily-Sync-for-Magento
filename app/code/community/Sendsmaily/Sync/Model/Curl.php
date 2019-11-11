<?php

/**
 * Sendsmaily Sync
 * Export Magento newsletter subscribers to Sendsmaily
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

class Sendsmaily_Sync_Model_Curl
{
    /**
     * Request URL.
     *
     * @var string
     */
    protected $_baseUrl = '';

    /**
     * Username
     *
     * @var string
     */
    protected $_username = '';

    /**
     * Password
     *
     * @var string
     */
    protected $_password = '';

    /**
     * cURL request status code.
     *
     * @var int
     */
    protected $_statusCode = null;

    /**
     * Setup request data.
     *
     * @return void
     */
    public function __construct()
    {
        // build request url
        $domain = Mage::getStoreConfig('newsletter/sendsmaily/domain');
        $this->_baseUrl = 'https://' . $domain . '.sendsmaily.net/api/';

        $this->_username = Mage::getStoreConfig('newsletter/sendsmaily/username');
        $this->_password = Mage::getStoreConfig('newsletter/sendsmaily/password');
    }

    /**
     * Change subdomain. Required to validate credentials before settings have been saved.
     *
     * @param string $domain
     * @return void
     */
    public function setSubdomain($domain)
    {
        $this->_baseUrl = 'https://' . $domain . '.sendsmaily.net/api/';
    }

    /**
     * Change username. Required to validate credentials before settings have been saved.
     *
     * @param string $username
     * @return void
     */
    public function setUsername($username)
    {
        $this->_username = $username;
    }

    /**
     * Change password. Required to validate credentials before settings have been saved.
     *
     * @param string $password
     * @return void
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * Get status code of HTTP call.
     *
     * @return int/null
     */
    public function getStatusCode()
    {
        return $this->_statusCode;
    }


    /**
     * Call to Smaily API
     *
     * @return array
     */
    public function callApi($endpoint, $data = array(), $method = 'GET')
    {
        $response = array();
        // Standard parameters.
        $username = $this->_username;
        $password = $this->_password;
        $apiUrl = $this->_baseUrl . trim($endpoint, '/') . '.php';

        // Create connection.
        $client = curl_init();
        // Set request parameters.
        curl_setopt($client, CURLOPT_HEADER, false);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($client, CURLOPT_USERPWD, $username . ":" . $password);

        if (!$client) {
            $this->doError('Could not create cURL connection!');
        }

        if ($method === 'GET') {
            if (!empty($data)) {
                $data = urldecode(http_build_query($data));
                $apiUrl = $apiUrl . '?' . $data;
            }
            curl_setopt($client, CURLOPT_URL, $apiUrl);
        } elseif ($method === 'POST') {
            curl_setopt($client, CURLOPT_URL, $apiUrl);
            curl_setopt($client, CURLOPT_POST, true);
            curl_setopt($client, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $response = (array) json_decode(curl_exec($client), true);
        $error = curl_error($client);
        if (!$error) {
            $this->_statusCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        }

        curl_close($client);
        // Validate response.
        if ($error) {
            return $this->doError($error);
        }

        return $response;
    }

    /**
     * Logs error to smaily log and returns empty response for cURL call.
     *
     * @param string $errorMessage
     * @return array
     */
    protected function doError($errorMessage)
    {
        Mage::log($errorMessage, null, 'smaily.log', true);
        return array();
    }
}
