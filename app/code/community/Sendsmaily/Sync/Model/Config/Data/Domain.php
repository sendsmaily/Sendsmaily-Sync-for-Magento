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

class Sendsmaily_Sync_Model_Config_Data_Domain extends Mage_Core_Model_Config_Data
{
  /**
   * Regular expression to find subdomain from value.
   *
   * @var string
   */
  protected $_regex = '/^(https?\:\/\/)?([^\.\/]+)\.sendsmaily\.net/siU';

  /**
   * Override field value setter to extract subdomain
   * part from the value.
   *
   * @param mixed $value
   * @return mixed
   */
  public function setValue($value) {
    // Try to find subdomain from value.
    $matches = array();
    preg_match($this->_regex, $value, $matches);

    // Override value, if value matched regular expression.
    if (!empty($matches) and !empty($matches[2])) {
      $value = $matches[2];
    }

    return parent::setValue($value);
  }
}
