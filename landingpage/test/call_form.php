<?php
header("Content-Type: application/json");

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

if( true ){
//if(false){

$strjson = '{"MemberNo":1000543,"PageName":"NAIMMK79","FullName":"Kariman","TelMobile":"+6011344444444","Email":"kariman@karuma.my"}';

//echo $strjson;

//$res = json_decode($strjson, true); 


echo reg_form($strjson);

}

 

exit();


function reg_form($sent_jsonStr){

	$response = json_decode($sent_jsonStr,true);

	$obj->name = $response['FullName'];
	$obj->tel = $response['TelMobile'];
	$obj->email = $response['Email'];
	$obj->memno = $response['MemberNo'];
	$obj->pagename = $response['PageName'];
	
	$jsonStr = json_encode($obj);

	$url_1 = 'http://localhost/onecent/landingpage/form.php';
	$url_2 = 'http://219.92.7.188:1080/onecent/landingpage/form.php';

	
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
