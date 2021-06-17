<?php
include '../service/config/database.php';
include '../service/config/datetime.php';
include 'config/class_pagename.php';
include 'config/class_prospect.php';
?>
<?php
header("Content-Type: application/json");

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);


/* get post */
$requestbody = file_get_contents('php://input');

$response = json_decode($requestbody, true);



$obj->FullName = $response['name'];
$obj->TelMobile = $response['tel'];
$obj->Email = $response['email'];
$obj->MemberNo = $response['memno'];
$obj->PageName = $response['pagename'];

$pros = new Prospect();

$json_status = $pros->checkProspect($response['memno'],$response['tel']);

$stat = json_decode($json_status,true);

if( $stat['status'] == 0 ){

	$json_status = $pros->newProspect(json_encode($obj));
	
	$stat = json_decode($json_status,true);
	
	if( $stat['status'] == 1 ){
		$obj->status = 1;
		$obj->msg = "Your information have been sent. Thank you.";
		echo json_encode($obj);
	} else {
		$obj->status = 0;
		$obj->msg = "Sorry.. Please try again later.";
		echo json_encode($obj);
	}

} else {

	echo $json_status;
}


//SELECT `RecNo`, `MemberNo`, `NickName`, `FullName`, `TelMobile`, `Email`, `DateCreated`, `Status`, `Remark` FROM `tbl_landing_response` WHERE 1

?>
