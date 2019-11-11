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

class Sendsmaily_Sync_Model_System_Config_Source_Customer_Autoresponder
{
  protected $_options;

  public function toOptionArray()
  {
    if (!$this->_options) {
      $list = array();
      $curl = Mage::getModel('sync/curl');
      $autoresponders = $curl->callApi('workflows', array('trigger_type' => 'form_submitted'));

      // TODO: Find out how to show errors when not receiving autoresponders.
      // Issue comes before validating credentials.

      // if ($curl->getStatusCode() !== 200) {
      //   $message = 'There seems to be problem getting autoresponders. Please check credentials and try again!';
      //   Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__($message));
      // }

      if ($autoresponders) {
        foreach ($autoresponders as $autoresponder) {
          $list[] = array('value' => $autoresponder['id'], 'label' => $autoresponder['title']);
        }
      }

      $this->_options = $list;
    }

    $options = $this->_options;

    array_unshift(
        $options,
        array(
          'value' => '',
          'label' => Mage::helper('sync')->__('--Please Select Autoresponder--')
        )
    );

    return $options;
  }
}
