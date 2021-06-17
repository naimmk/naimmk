<?php

echo postAPI($_REQUEST['p']);

function postAPI($pagename){
   

   $method = 'POST';
   $url = 'http://219.92.7.188:1080/onecent/landingpage/pagename.php';	
   $data = '{"pagename":"'.$pagename.'"}';

   $curl = curl_init();
   
   /****/
   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);         
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }
   /*****/

   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8'));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   // EXECUTE:
   $result = curl_exec($curl);
   //echo " $result";
   if(!$result){
   	die("Connection Failure....");
   }
   curl_close($curl);
   return $result;
}

?>