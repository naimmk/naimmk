<?php
header("Content-Type: application/json");

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

if( true ){
//if(false){

$strjson = '{"pagename":"NAIMMK79"}';

//echo $strjson;

//$res = json_decode($strjson, true); 


echo call_pagename($strjson);

}

 

exit();


function call_pagename($sent_jsonStr){

	$response = json_decode($sent_jsonStr,true);
	
	$obj->pagename = $response['pagename'];
	
	$jsonStr = json_encode($obj);

	$url_1 = 'http://localhost/onecent/landingpage/pagename.php';
	$url_2 = 'http://219.92.7.188:1080/onecent/landingpage/pagename.php';

	
	$make_call = callAPI('POST', $url_2, $jsonStr);
	//$response = json_decode($make_call, true);
	//$errors   = $response['msg'];
	//$data     = $response['status'];			
	
	//echo $make_call."<br/>";
	//echo $response."<br/>";			
	//echo 'ErrorMessage: '.$errors."<br/>";
	//echo 'Status: '.$data;
	
	return $make_call;

}




function callAPI($method, $url, $data){
   $curl = curl_init();
   
   /**/	
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
   /**/

   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Access-Control-Allow-Origin: *','Content-Type: application/json; charset=UTF-8'));
   curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8'));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   //curl_setopt($curl, CURLOPT_USERPWD, "admin:mypass");
   // EXECUTE:
   $result = curl_exec($curl);
   //echo " $result";
   if(!$result){
   		die("ConnectionÃ‚Â Failure....");
   }
   curl_close($curl);
   return $result;
}

?>
