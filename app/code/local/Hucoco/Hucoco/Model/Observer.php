<?php

class Hucoco_Hucoco_Model_Observer
{
    public function addButtonMakeimages($observer)
    {
        $container = $observer->getBlock();
        if(null !== $container && $container->getType() == 'adminhtml/catalog_product') {
            $data = array(
                'label'     => 'Assign Default Images',
                'class'     => 'some-class',
                'onclick'   => 'setLocation(\''  
                    . Mage::helper('adminhtml')->getUrl(
                        'makeproductdefaultimages/adminhtml_index', 
                        array('_current' => true)) . '\')',
            );
            $container->addButton('hucoco_button_makeimages', $data);
        }

        return $this;
    }

    public function assignImagesToDefault()
    {
        $products = Mage::getResourceModel('catalog/product_collection')
                //->addAttributeToFilter('is_imported', 1)
		;

        $c = 0;
        foreach($products as $p) {
            $pid = $p->getId();
            $product = Mage::getModel('catalog/product')->load($pid);
            $mediaGallery = $product->getMediaGallery();
            if (isset($mediaGallery['images'])){
                foreach ($mediaGallery['images'] as $image){
                    Mage::getSingleton('catalog/product_action')
                    ->updateAttributes(array($pid), array('image'=>$image['file']), 0);
                    $c++;
                    break;
                }
            }
        }
        $msg = $c . " product(s) updated.";//exit;

        $products = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');
        foreach ($products as $product) {
            if (!$product->hasImage()) continue;
            if (!$product->hasSmallImage()) $product->setSmallImage($product->getImage());
            if (!$product->hasThumbnail()) $product->setThumbnail($product->getImage());
            $product->save();
        }
        
        return $msg;
    } //endfunction
}
