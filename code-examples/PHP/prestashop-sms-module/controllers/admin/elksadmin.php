<?php
class ElksAdminController extends AdminController
{

  public function initContent()
  {
    parent::initContent();
    $sms_error_code = "SMS not sent!";

    if (Tools::isSubmit('submit')) {
      $sms_content = Tools::getValue('SMSContent');
      $phone = Tools::getValue('phoneNumber');
      $sms = array(
        "from" => "PHPElk",   // Can be up to 11 alphanumeric characters
        "to" => $phone,       // The mobile number you want to send to
        "message" => $sms_content,
      );

      $sms_error_code = $this->sendSMS($sms);
    }

    // Your order id
    $order_id = 1;

    // Load order object
    $order = new Order((int) $order_id);

    // Validate order object
    if (Validate::isLoadedObject($order)) {
      $address = new Address($order->id_address_invoice);
      $customer = new Customer($order->id_customer);
      $this->context->smarty->assign("order", $order);
      $this->context->smarty->assign("address", $address);
      $this->context->smarty->assign("customer", $customer);
      $this->context->smarty->assign("sms_error_code", $sms_error_code);
    }
    $template_file = _PS_MODULE_DIR_ . 'elks/views/templates/admin/admin.tpl';
    $content = $this->context->smarty->fetch($template_file);
    $this->context->smarty->assign(array(
      'content' =>  $content
    ));
  }

  public function sendSMS($sms)
  {
    $username = "API_USER"; // Set this to your 46elks API username
    $password = "API_PASS"; // Set these to your 46elks API password

    $context = stream_context_create(array(
      'http' => array(
        'method' => 'POST',
        'header'  => 'Authorization: Basic ' .
          base64_encode($username . ':' . $password) . "\r\n" .
          "Content-type: application/x-www-form-urlencoded\r\n",
        'content' => http_build_query($sms),
        'timeout' => 10
      )
    ));
    $response = file_get_contents(
      "https://api.46elks.com/a1/sms",
      false,
      $context
    );

    if (!strstr($http_response_header[0], "200 OK"))
      return $http_response_header[0];
    return $response;
  }
}
