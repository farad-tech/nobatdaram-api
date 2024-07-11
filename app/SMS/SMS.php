<?php

namespace App\SMS;

use Exception;
use Illuminate\Support\Facades\Log;
use SoapClient;

class SMS {

  protected $text;
  protected $to;

  public function __construct(array $text, string $to)
  {
    $this->text = $text;
    $this->to = $to;
  }

  public function send(int $bodyId)
  {

    ini_set("soap.wsdl_cache_enabled","0");
    $sms = new SoapClient("http://api.payamak-panel.com/post/Send.asmx?wsdl",array("encoding"=>"UTF-8"));
    $data = array(
        "username"=>config('sms.username'),
        "password"=>config('sms.password'),
        "text"=> $this->text,
        "to"=> $this->to,
        "bodyId"=> $bodyId );

      try {

        $result = $sms->SendByBaseNumber($data)->SendByBaseNumberResult;

      } catch (Exception $e) {
        
        Log::error('SMS send error exception: '. $e);
        Log::error('SMS send error response: '. $result);

      }
    
  }

}