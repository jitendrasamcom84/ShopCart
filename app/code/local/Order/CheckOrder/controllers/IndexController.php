<?php
class Order_CheckOrder_IndexController extends Mage_Core_Controller_Front_Action{

	public function indexAction(){

		$this->loadLayout();
	    $block = $this->getLayout()->createBlock('core/template');
	        $block->setTemplate('checkorder/orderinfo.phtml');

	    $this->getLayout()->getBlock('content')->append($block);

	    $this->renderLayout();

	}

	public function orderAction(){

		$data = $this->getRequest()->getParams();
		$main_array = array_keys($data);

		$order_id = $main_array[0];
		$key = $main_array[1];

		$order = Mage::getModel('sales/order')->loadByIncrementId($order_id); 

		// echo "<pre/>";
		// $total = Mage::getModel('sales/order')->getCollection();
		// print_r($total);

		$shippingAddr = $order->getShippingAddress()->getData();
		$shippingAddress = $order->getShippingAddress()->getFormated(true);

		$products = $order->getAllItems();

		// print_r($shippingAddr);
		// print_r($products);

		// die;

	    Mage::register('order', $order);
	    Mage::register('products', $products);
	    Mage::register('shippingAddr', $shippingAddr);
	    Mage::register('shippingAddress', $shippingAddress);

        $this->IndexAction();
  

	}

}
?>