<?php
class Place_Order_Model_Observer
{

    public function __construct(){
        
        //overwrite mail configuration
        if (is_null($this->_mail)) {
        $my_smtp_host = 'smtp.gmail.com';
        $my_smtp_port = '587';
        $config = array(
                  'port' => $my_smtp_port,
                  'auth' => 'login',
                  'ssl' => 'tls',
                  'username' => 'test13samcom13@gmail.com',
                  'password' => 'samcom84'
                  );
         $transport = new Zend_Mail_Transport_Smtp($my_smtp_host,$config);
         Zend_Mail::setDefaultTransport($transport);         

            $this->_mail = new Zend_Mail('utf-8');
        }
    }

    public function orderMail(Varien_Event_Observer $observer)
    {

        $order = $observer->getEvent()->getOrder();
        $incrementId = $order->getIncrementId();
        $entityId = $order->getEntityId();
        $protectCode = $order->getProtectCode();
        $checkOrderurl = Mage::getUrl('check-order/index/order');
        $baseurl = Mage::getBaseUrl();
        $hashcode = md5($incrementId."-".$entityId."-".$protectCode);
        $customKey = substr($hashcode, 1, 7);
        $url = $checkOrderurl.$incrementId."/key/".$customKey;

        //sendmail here
        $customerEmail = $order->getCustomerEmail();
        $firstName = $order->getCustomerFirstname();
        $lastName = $order->getCustomerLastname();


        $emailTemplate = Mage::getModel('core/email_template')->loadDefault('place_order_email_template');

        //Getting the Store E-Mail Sender Name.
        $senderName = Mage::getStoreConfig('trans_email/ident_general/name');
        //Getting the Store General E-Mail.
        $senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');

        //Variables for Confirmation Mail.
        $emailTemplateVariables = array();
        $emailTemplateVariables['customername'] = $firstName." ".$lastName;
        $emailTemplateVariables['customorderurl'] = $url;

        //Appending the Custom Variables to Template.
        $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);

        //Sending E-Mail to Customers.
        $mail = Mage::getModel('core/email')
         ->setToName($senderName)
         ->setToEmail($customerEmail)
         ->setBody($processedTemplate)
         ->setSubject('Subject: Order Placed Successfully!')
         ->setFromEmail($senderEmail)
         ->setFromName($senderName)
         ->setType('html');
    
        try{
            //Confimation E-Mail Send
            $mail->send();
        }
        catch(Exception $error)
        {

            Mage::getSingleton('core/session')->addError($error->getMessage());
            return false;
        }
    }
}