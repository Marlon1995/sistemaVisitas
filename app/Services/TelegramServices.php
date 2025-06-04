<?php


namespace App\Services;



class TelegramServices
{

    public function __construct()
    {
        
    }

  
    function sendMessage($msg) {
        $token=config("app.telegram.token");
        $id=config("app.telegram.chat_id");      
    
        $urlMsg = "https://api.telegram.org/bot{$token}/sendMessage";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlMsg);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "chat_id={$id}&parse_mode=HTML&text=$msg");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }  

}



