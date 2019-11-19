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

class Sendsmaily_Sync_Model_Cron
{
    /**
     * Limit Smaily unsubscribers request size.
     */
    const UNSUBSCRIBE_BATCH_LIMIT = 1000;

    /**
     * Cron task to synchronize Sendsmaily database with Magento
     * newsletter subscribers.
     *
     * @return void
     */
    public function customerSync()
    {
        // Has module been activated? Is sync enabled?
        if (Mage::getStoreConfig('newsletter/sendsmaily/active') == false ||
            Mage::getStoreConfig('newsletter/sendsmaily/active_sync') == false) {
            return;
        }

        $unsubscribers = Mage::helper('sync')->getUnsubscribersEmails(self::UNSUBSCRIBE_BATCH_LIMIT);
        Mage::helper('sync')->removeUnsubscribers($unsubscribers);

        // Get sync data.
        $data = Mage::helper('sync')->getSyncData();

        // Make the request (in chunks of 500).
        $chunks = array_chunk($data, 500);
        foreach ($chunks as $chunk) {
            $result = Mage::getModel('sync/curl')->callApi('contact', $chunk, 'POST');
            // On error go to next chunk.
            $isOk = isset($result['code']) and $result['code'] >= 200;
            if (is_array($result) and $isOk) {
                continue;
            }
        }
    }
}
