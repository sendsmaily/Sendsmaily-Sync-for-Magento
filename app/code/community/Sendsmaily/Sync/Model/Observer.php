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
    public function newsletterOptInSubscribe($observer)
    {
        // Run only if newsletter subscriber opt-in is enabled.
        if (!Mage::helper('sync')->newsletterOptInEnabled()) {
            return;
        }

        $subscriber = $observer->getEvent()->getSubscriber();
        if (!$subscriber instanceof Mage_Newsletter_Model_Subscriber) {
            return;
        }

        if ($subscriber->isSubscribed()) {
            $email = $subscriber->getEmail();
            $store = Mage::app()->getStore()->getName();

            $data = array(
                'email' => $email,
                'extra' => array(
                    'store' => $store
                )
            );

            // TODO: Show error message?
            $response = Mage::helper('sync')->optInSubscribe($data);
        }
    }
}
