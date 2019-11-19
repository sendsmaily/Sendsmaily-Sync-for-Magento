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

require_once('Mage/Adminhtml/controllers/Newsletter/SubscriberController.php');

class Sendsmaily_Sync_Newsletter_SubscriberController extends Mage_Adminhtml_Newsletter_SubscriberController
{
  /**
   * Export selected addresses to Sendsmaily.
   *
   * @return void
   */
  public function exportToSendsmailyAction()
  {
    if (Mage::getStoreConfig('newsletter/sendsmaily/active') == false) {
      $message = $this->_getHelper()->__('Module is not activated!');
      $this->_getSession()->addError($message);
      return $this->_redirectReferer();
    }

    // Restructure data.
    $data = $this->getLayout()
      ->createBlock('adminhtml/newsletter_subscriber_grid')
      ->toSendsmaily();

    // Make the request (in chunks of 500).
    $chunks = array_chunk($data, 500);
    foreach ($chunks as $chunk) {
      $result = Mage::getModel('sync/curl')->callApi('contact', $chunk, 'POST');

      // Handle errors returned from Sendsmaily.
      if (is_array($result) and isset($result['code']) and $result['code'] >= 200) {
        $message = $this->_getHelper()->__($result['message']);
        $this->_getSession()->addError($message);
        return $this->_redirectReferer();
      }
    }

    $message = $this->_getHelper()->__('Newsletter subscriptions synchronized.');
    $this->_getSession()->addSuccess($message);
    return $this->_redirectReferer();
  }

  /**
   * Retrieve sync base helper.
   *
   * @return Sendsmaily_Sync_Helper_Data
   */
  protected function _getHelper()
  {
    return Mage::helper('sync');
  }
}
