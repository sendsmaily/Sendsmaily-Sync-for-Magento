<?php

/**
 * Sendsmaily Sync
 * Module to export Magento newsletter subscribers to Sendsmaily
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

class Sendsmaily_Sync_Block_Newsletter_Subscriber_Grid extends Mage_Adminhtml_Block_Newsletter_Subscriber_Grid
{
  /**
   * Prepare grid columns.
   *
   * @return void
   */
  protected function _prepareColumns()
  {
    // Call parent class function.
    $result = parent::_prepareColumns();

    // Add Sendsmaily export type.
    if (Mage::getStoreConfig('newsletter/sendsmaily/active') == true) {
      $this->addExportType('*/*/exportToSendsmaily', 'Sendsmaily');
    }

    return $result;
  }

  /**
   * Restructure collection to Sendsmaily-readable form.
   *
   * @return array
   */
  public function toSendsmaily()
  {
    $this->_isExport = true;
    $this->_prepareGrid();
    $this->getCollection()->getSelect()->limit();
    $this->getCollection()->setPageSize(0);
    $this->getCollection()->load();
    $this->_afterLoadCollection();

    // Get synchronization data based on selected subscribers.
    $data = Mage::helper('sync')->getSyncData($this->getCollection());
    return $data;
  }
}
