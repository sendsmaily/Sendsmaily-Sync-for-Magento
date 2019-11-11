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

class Sendsmaily_Sync_Model_System_Config_Source_Customer_Schedule
{
  protected $_options;

  public function toOptionArray()
  {
    if (!$this->_options) {
      $this->_options = array(
        array('value' => '0 */4 * * *', 'label' => Mage::helper('sync')->__('Every 4 hours')),
        array('value' => '0 */12 * * *', 'label' => Mage::helper('sync')->__('Twice a day')),
        array('value' => '0 * */1 * *', 'label' => Mage::helper('sync')->__('Every day')),
        array('value' => '0 0 * * 0', 'label' => Mage::helper('sync')->__('Once a week')),
      );
    }

    return $this->_options;
  }
}
