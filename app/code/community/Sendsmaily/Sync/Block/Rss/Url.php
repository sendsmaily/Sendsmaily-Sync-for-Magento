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

class Sendsmaily_Sync_Block_Rss_Url extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Creates table with buttons to link to Smaily RSS feed for easy access from admin page.
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return $html table of store names with buttons to RSS-feed.
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        $stores = Mage::app()->getStores();
        // Table header.
        $html = '<table style="width:100%">' .
                '<tr>' .
                    '<th>' . $this->__('Store') . '</th>' .
                    '<th>' . $this->__('Link') . '</th>' .
                '</tr>';

        // Add rows.
        foreach ($stores as $store) {
            $element = '<tr>';
            $element .= '<td>' . $store->getName() . '</td>';

            $url = $this->getUrl(
                'sync/rss',
                array(
                    '_use_rewrite' => true,
                    'key'          => '', // Remove key from generated URL.
                    '_query'       => array(
                        'store_id'     => $store->getId()
                    )
                )
            );
            $button = $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setType('button')
                ->setClass('scalable')
                ->setLabel($this->__('View RSS-feed'))
                ->setOnClick("window.open('$url')")
                ->toHtml();

            $element .= '<td>' . $button . '</td>';
            $element .= '</tr>';

            $html .= $element;
        }

        $html .= '</table>';

        return $html;
    }
}
