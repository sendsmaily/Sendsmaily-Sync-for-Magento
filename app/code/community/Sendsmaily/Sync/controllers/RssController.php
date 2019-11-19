<?php
class Sendsmaily_Sync_RssController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout(false);
        $this->getResponse()->setHeader('Content-type', 'text/xml; charset=UTF-8');
        $this->renderLayout();
    }
}
