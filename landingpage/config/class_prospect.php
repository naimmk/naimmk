<?php

/**
CREATE TABLE `tbl_landing_response` (                               
                        `RecNo` int(11) NOT NULL AUTO_INCREMENT,                          
                        `MemberNo` int(11) NOT NULL COMMENT 'PageOwner',                 
                        `PageName` varchar(50) NOT NULL COMMENT 'PageOwner',              
                        `FullName` varchar(150) NOT NULL COMMENT 'Prospect',              
                        `TelMobile` varchar(20) NOT NULL COMMENT 'Prospect',              
                        `Email` varchar(150) NOT NULL,                                    
                        `DateCreated` datetime NOT NULL COMMENT 'Prospect',               
                        `Status` varchar(20) NOT NULL,                                    
                        `Remark` text NOT NULL,                                           
                        PRIMARY KEY (`RecNo`)                                             
                      ) ENGINE=MyISAM
                      
 select RecNo, MemberNo, PageName, FullName, TelMobile, Email, DateCreated, Status, Remark from `onecent`.`tbl_landing_response` limit 0, 50                     
   
                   
**/

class Prospect {
	
	private $db;
	private $dt;

	// constructor with $db as database connection
    public function __construct(){
            
        /* instantiate database and member object */
		$database = new Database();
		$this->db = $database->getConnection();
		
		/* instantiate ReadyDatetime object */
		$this->dt = new ReadyDatetime();

    }
    
    public function checkProspect($memberno,$pro_mobileno){     
     	
    
    	$filter_memberno = mysqli_real_escape_string($this->db, $memberno);
    	$filter_pro_mobileno = mysqli_real_escape_string($this->db, $pro_mobileno);
    	
    	//				0			1			2			3			4
    	$q="SELECT  `MemberNo`, `TelMobile` ";
    	$q=$q."FROM `tbl_landing_response` ";
		$q=$q."WHERE (`MemberNo`='".$filter_memberno."' And `TelMobile` LIKE '".$filter_pro_mobileno."') ";
		$q=$q."OR (`MemberNo`='".$filter_memberno."' And `TelMobile` LIKE '+".$filter_pro_mobileno."') ";
		$q=$q."OR (`MemberNo`='".$filter_memberno."' And `TelMobile` LIKE '+6".$filter_pro_mobileno."') ";

		$r=mysqli_query($this->db,$q) or die(mysqli_error($this->db));
		
		if($rows=mysqli_fetch_row($r)){
			$obj->status = 1;
			$obj->msg = "Already register";	
			return json_encode($obj);
		} else {
			$obj->status = 0;
			$obj->msg = "Not register yet";	
			return json_encode($obj);
		}

		
	}
    
    
    public function getProspect($search){
    
    	$filter_search = mysqli_real_escape_string($this->db, $search);    	
    	
    	//select RecNo, MemberNo, PageName, FullName, TelMobile, Email, DateCreated, Status, Remark from `onecent`.`tbl_landing_response` limit 0, 50
    	//			0			1		2			3			
    	$q="SELECT  RecNo, MemberNo, PageName, FullName, ";
    	//		4			5			6			7		8				
    	$q=$q." TelMobile, Email, DateCreated, Status, Remark ";    	
    	$q=$q."FROM `tbl_landing_response` ";
		$q=$q."WHERE ( MemberNo = '".$filter_search."' )  ";
		$q=$q."OR ( FullName LIKE '".$filter_search."' )  ";
		$q=$q."OR ( TelMobile LIKE '".$filter_search."' )  ";
		$q=$q."OR ( TelMobile LIKE '+".$filter_search."' )  ";
		$q=$q."OR ( TelMobile LIKE '+6".$filter_search."' )  ";
		$q=$q."OR ( Email LIKE '".$filter_search."' )  ";
		
		$r=mysqli_query($this->db,$q) or die(mysqli_error($this->db));
		
		$obj->status = 0;
		$obj->prospect = array();
		
		$i = 0;
		while($rows=mysqli_fetch_row($r)){	
			$obj->status = 1;
			//data
			$obj->prospect[$i]->RecNo = $rows[0];
			$obj->prospect[$i]->MemberNo = $rows[1];
			$obj->prospect[$i]->PageName = $rows[2];
    		$obj->prospect[$i]->FullName = $rows[3];    		
    		$obj->prospect[$i]->TelMobile = $rows[4];
    		$obj->prospect[$i]->Email = $rows[5];
    		$obj->prospect[$i]->DateCreated = $rows[6];    
    		$obj->prospect[$i]->Status = $rows[7];
    		$obj->prospect[$i]->Remark= $rows[8];
    		
    		$i++;
		} 
		
		
		if( $obj->status == 1 ){
		
			$obj->count = $i;
			$obj->msg = "Found";
			
			return json_encode($obj);  	
		
		} else {
		
			$obj->status = 0;
			$obj->count = 0;
			$obj->msg = "Not found";
			
			return json_encode($obj); 
			 		
		}
    
    }
    
    
    public function newProspect($json_str){
    	//select RecNo, MemberNo, PageName, FullName, TelMobile, Email, DateCreated, Status, Remark from `onecent`.`tbl_landing_response` limit 0, 50
    
    	$usr = json_decode($json_str,true);    	
    	    	
    	if( true ){ //proceed
    	
    		$q = "INSERT `tbl_landing_response` ";
    		$q = $q."(MemberNo, PageName, FullName, TelMobile, Email, DateCreated, Status) ";
    		$q = $q."VALUES ('".mysqli_real_escape_string($this->db, $usr['MemberNo'])."', ";
    		$q = $q."'".mysqli_real_escape_string($this->db, $usr['PageName'])."', ";
			$q = $q."'".mysqli_real_escape_string($this->db, $usr['FullName'])."', ";
			$q = $q."'".mysqli_real_escape_string($this->db, $usr['TelMobile'])."', ";
			$q = $q."'".mysqli_real_escape_string($this->db, $usr['Email'])."', ";
			$q = $q."'".mysqli_real_escape_string($this->db, $this->dt->dateTodayTime())."', ";
			$q = $q."'".mysqli_real_escape_string($this->db, 'NEW')."' ) ";
    		
    		$res=mysqli_query($this->db,$q) or die(mysqli_error($this->db));
					
			if( $res ){
				$obj->status = 1;
				$obj->msg = "Record updated.";	
				//$obj->sql = $q;
				return json_encode($obj);
			}

    	} else {    	
    		$obj->status = 0;
			$obj->msg = "Update failed.";
			return json_encode($obj);	
    	}
    	
    	
    }

    
    
    public function updateProspect($json_str){
    	//select RecNo, MemberNo, PageName, FullName, TelMobile, Email, DateCreated, Status, Remark from `onecent`.`tbl_landing_response` limit 0, 50
    
    	$usr = json_decode($json_str,true);
    	
    	/**
    	$obj_log->Date = $this->dt->dateTodayTime();
    	$obj_log->Status = $usr['Status'];
    	$obj_log->Remark = $usr['Remark'];
    	
    	$upd_log = json_encode($obj_log);
    	**/
    	
    	$txt_log = '{"Date":"'.$this->dt->dateTodayTime().'", ';
    	$txt_log = $txt_log.'"Status":"'.mysqli_real_escape_string($this->db, $usr['Status']).'", ';
    	$txt_log = $txt_log.'"Remark":"'.mysqli_real_escape_string($this->db, $usr['Remark']).'"}, ';

    	
    	if( true ){ //proceed
    	
    		$q = "UPDATE `tbl_landing_response` ";
    		$q = $q."SET `UpdateLog`=CONCAT('".$txt_log."',`UpdateLog`), ";
    		$q = $q."`Status`='".mysqli_real_escape_string($this->db, $usr['Status'])."', "; 
    		$q = $q."`Remark`=CONCAT('[".$this->dt->dateTodayTime()."] ".mysqli_real_escape_string($this->db, $usr['Remark'])."',`Remark`) ";    	
    		$q = $q."WHERE 	`RecNo`='".mysqli_real_escape_string($this->db, $usr['RecNo'])."' ";
    		
    		$res=mysqli_query($this->db,$q) or die(mysqli_error($this->db));
					
			if( $res ){
				$obj->status = 1;
				$obj->msg = "Record updated.";	
				//$obj->sql = $q;
				return json_encode($obj);
			}


    	} else {    	
    		$obj->status = 0;
			$obj->msg = "Update failed.";
			return json_encode($obj);	
    	}
    	
    	
    }



}
?>
