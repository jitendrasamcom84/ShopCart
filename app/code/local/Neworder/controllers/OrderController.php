<?php

class Neworder_OrderController extends Mage_Core_Controller_Front_Action{

  /**
  * Index action
  *
  * @access public
  * @return void
  */
  public function indexAction() {

    echo "order"; 

  // var_dump($this->getRequest()->getParams());
  // echo $order = Mage::app()->getRequest()->getParam('order');

  }
}
?>