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

class Sendsmaily_Sync_Model_Rss extends Mage_Rss_Model_Rss
{

    /**
     * Add header to Smaily RSS feed
     *
     * @param array $data Header elements
     * @return Sendsmaily_Sync_Model_Rss Smaily RSS-object
     */
    public function _addHeader($data = array())
    {
        $this->_feedArray['channel'] = $data;
        return $this;
    }

    /**
     * Add items/products to RSS-feed
     *
     * @param array $entry Product data collection
     * @return Sendsmaily_Sync_Model_Rss Smaily RSS-object
     */
    public function _addEntry($entry)
    {
        $this->_feedArray['channel']['item'][]= $entry;
        return $this;
    }

    /**
     * Response to controller in XML format.
     *
     * @return string XML-string of RSS-feed
     */
    public function createRssXml()
    {
        try {
            return $this->arrayToXml($this->getFeedArray());
        } catch (Exception $e) {
            // TODO: Error message to front end.
            Mage::log('Error in processing xml. %s'. $e->getMessage(), null, 'smaily.log', true);
            return array();
        }
    }

    /**
     * Generate XML-structure based of feed array.
     *
     * @param array $array Rss-feed item
     * @param string $rootElement Root element of RSS-feed
     * @param SimpleXMLElement $xml XML-structure of current iteration
     * @return string XML-formatted string
     */
    public function arrayToXml($array, $rootElement = null, $xml = null)
    {
        $_xml = $xml;
        // Init base on first run.
        if ($_xml === null) {
            $base = '<rss xmlns:smly="https://sendsmaily.net/schema/editor/rss.xsd" version="2.0"></rss>';
            $_xml = new SimpleXMLElement($rootElement !== null ? $rootElement : $base);
        }

        // Iterate the array of RSS-feed items.
        foreach ($array as $key => $item) {
            // For nested array call this function recursively.
            if (is_array($item)) {
                // Item is an array that holds all items. Sequential arrays with numeric keys.
                if ($key === 'item') {
                    foreach ($item as $product) {
                        // Add items to channel to preserve correct structure. (Root node <item>)
                        $this->arrayToXml($product, $key, $_xml->addChild($key));
                    }
                } else {
                    $this->arrayToXml($item, $key, $_xml->addChild($key));
                }
            } else {
                // Simply add child element.
                switch ($key) {
                    case 'price':
                    case 'old_price':
                    case 'discount':
                        $_xml->addChild($key, $item, 'https://sendsmaily.net/schema/editor/rss.xsd');
                        break;
                    case 'enclosure':
                        $enclosure = $_xml->addChild($key);
                        $enclosure->addAttribute('url', $item);
                        break;
                    case 'description':
                    case 'title':
                        $value =  $_xml->addChild($key);
                        $this->addCData($item, $value);
                        break;
                    case 'guid':
                        $guid = $_xml->addChild($key, $item);
                        $guid->addAttribute('isPermaLink', 'True');
                        break;
                    default:
                        $_xml->addChild($key, $item);
                }
            }
        }

        return $_xml->asXml();
    }

    /**
     * Return CDATA element
     *
     * @param string $text Element text
     * @param SimpleXMLElement $node Parent node
     * @return void
     */
    public function addCData($text, $node)
    {
        $node = dom_import_simplexml($node);
        $no   = $node->ownerDocument; 
        $node->appendChild($no->createCDATASection($text));
    }
}
