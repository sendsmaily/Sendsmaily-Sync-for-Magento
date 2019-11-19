<?php

/**
 * Sendsmaily Sync
 * Module to export Magento newsletter subscribers to Sendsmaily
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

class Sendsmaily_Sync_Block_Rss extends Mage_Rss_Block_Catalog_Abstract
{
    protected function _construct()
    {
        /*
        * setting cache to save the rss for 5 minutes
        */
        $this->setCacheKey('sendsmaily/rss/index');
        $this->setCacheLifetime(300);
    }

    protected function _toHtml()
    {
        $storeId = $this->_getStoreId();

        // HEADER
        $newurl = Mage::getUrl('sendsmaily/rss/');
        $title = Mage::app()->getStore()->getGroup()->getName();
        $lang = Mage::getStoreConfig('general/locale/code');
        $lastBuildDate = Mage::getSingleton('core/date')->date();

        // BUILD ARRAY OF RSS ELEMENTS
        $rssObj = Mage::getModel('sync/rss');
        $data = array(
            'title' => $title,
            'link'        => $newurl,
            'description' => 'Product Feed',
            'lastBuildDate' => $lastBuildDate
        );
        $rssObj->_addHeader($data);

        // Get store currency symbol.
        $currencySymbol = Mage::app()
            ->getLocale()
            ->currency(
                Mage::app()
                ->getStore()
                ->getCurrentCurrencyCode()
            )->getSymbol();


        // GATHER PRODUCTS
        $product = Mage::getModel('catalog/product');

        $products = $product->getCollection()
            ->setStoreId($storeId)
            ->addStoreFilter()
            ->addAttributeToSelect(array('name', 'price', 'special_price', 'description', 'thumbnail'), 'inner')
            ->addAttributeToSort('created_at', 'desc')
            ->setPageSize(50)
            ->setCurPage(1);

        /*
        using resource iterator to load the data one by one
        instead of loading all at the same time. loading all data at the same time can cause the big memory allocation.
        */
        Mage::getSingleton('core/resource_iterator')->walk(
            $products->getSelect(),
            array(array($this, 'addNewItemXmlCallback')),
            array('rssObj'=> $rssObj, 'product'=>$product, 'symbol' => $currencySymbol)
        );

        return $rssObj->createRssXml();
    }

    /**
     * Preparing data and adding to rss object
     *
     * @param array $args
     */
    public function addNewItemXmlCallback($args)
    {
        $product = $args['product'];
        $currencySymbol = $args['symbol'];

        $product->setAllowedInRss(true);
        $product->setAllowedPriceInRss(true);
        Mage::dispatchEvent('rss_catalog_new_xml_callback', $args);

        if (!$product->getAllowedInRss()) {
            //Skip adding product to RSS
            return;
        }

        $product->setData($args['row']);
        $imgUrl = Mage::helper('catalog/image')->init($product, 'thumbnail')->__toString();
        $price = $product->getPrice();
        $finalPrice = $product->getFinalPrice();

        $rssObj = $args['rssObj'];
        $data = array(
            'title'         => $product->getName(),
            'link'          => $product->getProductUrl(),
            'guid'          => $product->getProductUrl(),
            'pubDate'       => $product->getCreatedAt(),
            'description'   => $product->getDescription(),
            'imageUrl'      => $imgUrl,
        );
        
        if ($finalPrice < $price) {
            $percentage = (float)($price - $finalPrice) / $price * 100;
            $discount = number_format($percentage, 2);
            $data['price'] =  number_format((float) $finalPrice, 2) . $currencySymbol;
            $data['discount'] = $discount . '%';
            $data['oldPrice'] = number_format((float) $price, 2) . $currencySymbol;
        } else {
            $data['price'] = number_format((float) $price, 2) . $currencySymbol;
        }

        $rssObj->_addEntry($data);
    }
}
