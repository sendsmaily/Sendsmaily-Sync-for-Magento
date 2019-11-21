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
   * Check if newsletter form opt-in collection is enabled.
   *
   * @return bool
   */
  public function newsletterOptInEnabled()
  {
    $enabled = false;

    if ((bool) Mage::getStoreConfig('newsletter/sendsmaily/active') === true &&
      (bool) Mage::getStoreConfig('newsletter/sendsmaily/active_newsletter_form') == true) {
      $enabled = true;
    }

    return $enabled;
  }

  /**
   * Sends newsletter form subscriber information to Smaily
   *
   * @param array $data Subscriber data
   * @return void
   */
  public function optInSubscribe($data)
  {
    $curl = Mage::getModel('sync/curl');
    $email = $data['email'];
    $extra = $data['extra'];

    $address = array(
      'email' => $email,
    );

    if (!empty($extra)) {
      foreach ($extra as $field => $value) {
        $address[$field] = trim($value);
      }
    }
    
    $post = array(
      'addresses' => array(
        $address
      )
    );
  
    return $curl->callApi('autoresponder', $post, 'POST');
  }

  /**
   * Restructure collection data for Sendsmaily.
   *
   * @param unknown $collection [optional]
   * @return array
   */
  public function getSyncData($collection=null)
  {
    // If collection not provided, load collection of all newsletter subscribers.
    if (empty($collection)) {
      $collection = Mage::getModel('newsletter/subscriber')->getResourceCollection();
    }

    // Get fields to collect.
    $fields = (string)Mage::getStoreConfig('newsletter/sendsmaily/fields');
    $fields = (!empty($fields) ? explode(',', $fields) : array());

    // Restructure collection data.
    $data = array();
    foreach ($collection as $item) {
      $data[] = $this->gatherSubscriberData($item, $fields);
    }

    return $data;
  }

  /**
   * Get single subscriber Data
   *
   * @param Mage_Newsletter_Model_Subscriber $item Subscriber model.
   * @param array $fields Additional fields selected from admin panel.
   * @return array
   */
  public function gatherSubscriberData($item, $fields)
  {
    $data = array();

    $statusUnsubscribed = Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED;
    $isCustomer = $item->getCustomerId() > 0 ? true : false;
    $fieldsLength = count($fields);

    // Generate additional data fields.
    $extra = array();
    if ($fieldsLength > 0) {
      $extra = array_combine($fields, array_fill(0, $fieldsLength, ''));
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

    $subscriptionStatus = $item->getSubscriberStatus();
    if ((int)$subscriptionStatus === $statusUnsubscribed) {
      $extra['is_unsubscribed'] = '1';
    }

    // Collect customer data.
    if ($isCustomer) {
      $customerId= $item->getCustomerId();
      $customerExtra = $this->gatherCustomerExtra($customerId, $fields);
      $extra = array_merge($extra, $customerExtra);
    }

    $data = array_merge(
        $extra,
        array(
          'email' => $item->hasSubscriberEmail() ? $item->getSubscriberEmail() : $item->getEmail(),
          'subscription_status' => $subscriptionStatus,
        )
    );

    return $data;
  }

  /**
   * Gather extra data for customer
   *
   * @param int $customerId Customer ID
   * @param array $fields Additional fields selected from admin panel.
   * @return array
   */
  public function gatherCustomerExtra($customerId, $fields)
  {
    $extra = array();

    $customer = Mage::getModel('customer/customer')->load($customerId);
    // Get customer ID.
    if (in_array('customer_id', $fields)) {
      $extra['customer_id'] = $customerId;
    }

    // Get customer name prefix.
    if (in_array('prefix', $fields)) {
      $extra['prefix'] = $customer->getPrefix() ? $customer->getPrefix() : '';
    }

    // Get customer group.
    if (in_array('customer_group', $fields)) {
      $code = Mage::getSingleton('customer/group')
        ->load($customer->getGroupId())
        ->getCode();
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
      $gender = $attr->getFrontend()->getOption($customer->getGender());
      $extra['gender'] = $gender ? $gender : '';
    }

    // Get customer birthdate.
    if (in_array('birthday', $fields)) {
      $dob = $customer->getDob();
      if (!empty($dob)) {
        $extra['birthday'] = Mage::getSingleton('core/date')->date('Y-m-d', strtotime($dob));
      }
    }

    // Clean up.
    unset($customer);

    return $extra;
  }

  /**
   * Get unsubscriber emails from Smaily.
   *
   * @param int $limit Limit batch size.
   * @return array Unsubscriber emails list.
   */
  public function getUnsubscribersEmails($limit)
  {
    $curl = Mage::getModel('sync/curl');
    $unsubscribersEmails = array();
    $data = array(
        'list' => 2,
        'limit' => $limit,
    );

    while (true) {
        $data['offset'] = $offset;
        $unsubscribers = $curl->callApi('contact', $data);
        if (!$unsubscribers) {
            break;
        }

        foreach ($unsubscribers as $unsubscriber) {
            $unsubscribersEmails[] = $unsubscriber['email'];
        }

        // Smaily API call offset is considered as page number, not SQL offset!
        $offset++;
    }

    return $unsubscribersEmails;
  }

  /**
   * Change customers subscription status to unsubscribed.
   *
   * @param array $unsubscriberEmails List of unsubscribers emails.
   * @return void
   */
  public function removeUnsubscribers($unsubscriberEmails)
  {
    $subscriberModel = Mage::getModel('newsletter/subscriber');

    foreach ($unsubscriberEmails as $email) {
      $subscriber = $subscriberModel->loadByEmail($email);
      if ($subscriber->isSubscribed()) {
        $subscriber->unsubscribe();
      }
    }
  }
}
