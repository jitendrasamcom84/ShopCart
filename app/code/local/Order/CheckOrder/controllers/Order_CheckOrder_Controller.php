<?php 
class Order_CheckOrder_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract{
    public function initControllerRouters($observer){
        $front = $observer->getEvent()->getFront();
        $front->addRouter('CheckOrder', $this);
        return $this;
    }
    public function match(Zend_Controller_Request_Http $request){
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }
        $urlKey = trim($request->getPathInfo(), '/');
        $check = array();
        $parts = explode('/', $urlKey);
        if ($parts[0] == 'brands'){//if url key starts with 'brands'
            if (!isset($parts[1])){ //if the url is just 'brands' - then redirect to brand list
                return false;//do nothing...the request will be handled by the default router and you will see `brands/index/index/`.
            }
            elseif ($parts[1] == 'index'){//if your controller is named differently change 'index' to your controller name
                return false; //do nothing...the request will be handled by the default router and you will see `brands/index/index/`.
            }
            else {
                //if a name is specified
                //get the entities with that name.
                $collection = Mage::getModel('[module]/[entity]')->getCollection()
                    //if the field is named differently change 'name' to your field name.
                    ->addAttributeToFilter('name', $parts[1]);
                $collection->getSelect()->limit(1);//limit to only one
                $itemId = (int)$collection->getFirstItem()->getId();
                //redirect to 'brands/index/view/id/$itemId'
                $request->setModuleName('brands')
                    ->setControllerName('index') //if your controller is named differently change 'index' to your controller name
                    ->setActionName('view') //if your action is named differently change 'view' to action name
                    ->setParam('id', $itemId);
                $request->setAlias(
                    Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                    $urlKey
                );
                return true;
            }
        }
        return false;
    }
}