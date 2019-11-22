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
     * Observers newsletter subscribe form.
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function newsletterOptInSubscribe($observer)
    {
        // Run only if newsletter subscriber opt-in is enabled.
        if (!Mage::helper('sync')->newsletterOptInEnabled()) {
            return;
        }

        // TODO: Should run if email is allready in newsletter subscribers list?

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

        if (isset($params['email'])) {
            $email = $params['email'];
            $store = Mage::app()->getStore()->getName();
    
            $data = array(
                'email' => $email,
                'extra' => array(
                    'store' => $store
                )
            );
            Mage::helper('sync')->optInSubscribe($data);
        }
    }

    /**
     * Shows error message and redirects to base URL.
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
