<?php

class Hucoco_Hucoco_Adminhtml_IndexController 
  extends Mage_Adminhtml_Controller_action
{

    protected function _initAction() {
        $this->loadLayout()
             ->_setActiveMenu('catalog/products')
             ->_addBreadcrumb(Mage::helper('adminhtml')->__('Make Default Images'), 
                    Mage::helper('adminhtml')->__('Catalog / Make Default Images'));

        return $this;
    }


    public function indexAction()
    {
        //echo 'done';exit;
        $msg = Mage::getModel('hucoco/observer')->assignImagesToDefault();
        Mage::getSingleton('core/session')->addSuccess('Images assigned to default. ' . $msg);
        $this->_redirect('adminhtml/catalog_product');
    }
}
