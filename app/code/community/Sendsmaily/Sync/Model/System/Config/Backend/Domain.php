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

class Sendsmaily_Sync_Model_System_Config_Backend_Domain extends Mage_Core_Model_Config_Data
{

  /**
   * Override field value setter to extract subdomain
   * part from the value.
   *
   * @param string $subdomain
   * @return mixed
   */
  public function setValue($subdomain)
  {
    // Normalize subdomain.
    // First, try to parse as full URL. If that fails, try to parse as subdomain.sendsmaily.net, and
    // if all else fails, then clean up subdomain and pass as is.
    if (filter_var($subdomain, FILTER_VALIDATE_URL)) {
        $url = parse_url($subdomain);
        $parts = explode('.', $url['host']);
        $subdomain = count($parts) >= 3 ? $parts[0] : '';
    } elseif (preg_match('/^[^\.]+\.sendsmaily\.net$/', $subdomain)) {
        $parts = explode('.', $subdomain);
        $subdomain = $parts[0];
    }

    $subdomain = preg_replace('/[^a-zA-Z0-9]+/', '', $subdomain);

    return parent::setValue($subdomain);
  }
}
