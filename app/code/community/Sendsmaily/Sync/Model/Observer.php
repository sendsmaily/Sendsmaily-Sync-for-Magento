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

class Sendsmaily_Sync_Model_Observer
{
    /**
     * Observers Newsletter_SubscriberController_NewAction predispatch.
     * Validates CAPTCHA response if necessary.
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function newsletterCapthcaCheck()
    {
        // Run only if newsletter subscriber opt-in is enabled.
        if (!Mage::helper('sync')->newsletterOptInEnabled()) {
            return;
        }

        $params = Mage::app()->getRequest()->getParams();

        if (Mage::helper('sync')->shouldCheckCaptcha()) {
            if (isset($params['g-recaptcha-response'])) {
                $response = $params['g-recaptcha-response'];
                if (!Mage::helper('sync')->isCaptchaValid($response)) {
                    $this->showError('Error validating CAPTCHA!');
                    return;
                }
            } else {
                $this->showError('Error loading CAPTCHA!');
                return;
            }
        }
    }

    /**
     * Send subscriber information to Smaily after changing subscription status.
     * Observes Newsletter_Subscriber_Save_After. Used to trigger OPT-IN and OPT-OUT workflow.
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function subscribeNewsletter($observer)
    {
        // Run only if newsletter subscriber opt-in is enabled.
        if (!Mage::helper('sync')->newsletterOptInEnabled()) {
            return;
        }

        // Subscriber model.
        $subscriber = $observer->getEvent()->getSubscriber();

        $subscribed = Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED;
        $unsubscribed = Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED;
        $status = (int) $subscriber->getStatus();

        // New subscriber(even with same email) has always statusChanged state TRUE.
        // API call is still made, but Smaily OPT-IN workflow won't trigger if email allready exists in DB.
        $statusChanged = $subscriber->getIsStatusChanged();
        $email = $subscriber->getEmail();
        $store = Mage::app()->getStore()->getName();

        $extra = array(
            'store' => $store
        );

        if ($statusChanged) {
            if ($status == $subscribed) {
                $request = Mage::helper('sync')->optInSubscribe($email, $extra);
            } elseif ($status == $unsubscribed) {
                $request = Mage::helper('sync')->optOutSubscribe($email);
            }
        }

        // Log message if update not successful.
        if (isset($request['code']) && (int) $request['code'] !== 101) {
            $message = 'Error updateing subscriber ' . $email . ' status with subscribeNewsletter observer.';
            Mage::log($message, null, 'smaily.log', true);
        }
    }

    /**
     * Shows error message , kills controller and redirects to base URL.
     *
     * @param string $message Error message.
     * @return void
     */
    public function showError($message)
    {
        $request = Mage::app()->getRequest();
        $action = $request->getActionName();
        $session = Mage::getSingleton('core/session');
        $session->addError($message);

        Mage::app()->getFrontController()->getAction()->setFlag(
            $action,
            Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH,
            true
        );
        // TODO: find better way to redirect. Should redirect to page that customer was on.
        Mage::app()->getFrontController()->getAction()->getResponse()->setRedirect(Mage::getBaseUrl());
    }
}
