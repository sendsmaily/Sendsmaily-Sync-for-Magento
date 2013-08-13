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

class Sendsmaily_Sync_Helper_Data extends Mage_Adminhtml_Helper_Data
{
  /**
   * Restructure collection data for Sendsmaily.
   *
   * @param unknown $collection [optional]
   * @return array
   */
  public function getSyncData($collection=null) {
    // If collection not provided, load collection of all
    // newsletter subscribers.
    if (empty($collection)) {
      $collection = Mage::getModel('newsletter/subscriber')->getResourceCollection();
    }

    // Get fields to collect.
    $fields = (string)Mage::getStoreConfig('newsletter/sendsmaily/fields');
    $fields = (!empty($fields) ? explode(',', $fields) : array());

    // Restructure collection data.
    $data = array();
    foreach ($collection as $item) {
      $isCustomer = $item->getCustomerId() > 0 ? true : false;

      // Generate additional data fields.
      $extra = array();
      if (count($fields) > 0) {
        $extra = array_combine($fields, array_fill(0, count($fields), ''));
      }

      // Get subscription type.
      if (in_array('subscription_type', $fields)) {
        $extra['subscription_type'] = ($isCustomer ? 'customer' : 'guest');
      }

      // Get store code.
      if (in_array('store', $fields)) {
        $extra['store'] = Mage::app()
          ->getStore($item->getStoreId())
          ->getName();
      }

      // Get website code.
      if (in_array('website', $fields)) {
        $websiteId = $item->getWebsiteId();
        if (empty($websiteId)) {
          $websiteId = Mage::app()
            ->getStore($item->getStoreId())
            ->getWebsiteId();
        }
        $extra['website'] = Mage::app()
          ->getWebsite($websiteId)
          ->getName();
      }

      // Collect customer data.
      if ($isCustomer){
        $customer = Mage::getModel('customer/customer')
          ->load($item->getCustomerId());

        // Get customer ID.
        if (in_array('customer_id', $fields)) {
          $extra['customer_id'] = $item->getCustomerId();
        }

        // Get customer name prefix.
        if (in_array('prefix', $fields)) {
          $extra['prefix'] = $customer->getPrefix();
        }

        // Get customer group.
        if (in_array('customer_group', $fields)) {
          $code = Mage::getSingleton('customer/group')
            ->load($customer->getGroupId())
            ->getName();
          $extra['customer_group'] = $code;
        }

        // Get customer firstname.
        if (in_array('firstname', $fields)) {
          $extra['firstname'] = $customer->getFirstname();
        }

        // Get customer lastname.
        if (in_array('lastname', $fields)) {
          $extra['lastname'] = $customer->getLastname();
        }

        // Get customer gender.
        if (in_array('gender', $fields)) {
          $attr = $customer->getAttribute('gender');
          $extra['gender'] = $attr->getFrontend()
            ->getOption($customer->getGender());
        }

        // Get customer birthdate.
        if (in_array('birthday', $fields)) {
          $dob = $customer->getDob();
          if (!empty($dob)) {
            $extra['birthday'] = Mage::getModel('core/date')
              ->date('Y-m-d', strtotime($dob));
          }
        }

        // Clean up.
        unset($customer);
      }

      $data[] = array_merge($extra, array(
        'email' => $item->hasSubscriberEmail() ? $item->getSubscriberEmail() : $item->getEmail(),
        'subscription_status' => $item->getSubscriberStatus(),
      ));
    }

    return $data;
  }
}
