<?php
/* This script is called by KooKoo when the person makes a call */
	$phone = "";
	if(isset($_GET["sid"])&&isset($_GET["cid"])) {
		$phone = $_GET['cid'];
		$msg = doQuery($phone);

		if($msg == "") {
			$msg = "Welcome to WebDevHub. You dont have any custom message.";
		}

		/*
		* Returns in the following format
		* <?xml version="1.0" encoding="UTF-8"?>
		* <Response>
		* <playtext>some custom message</playtext>
		* </Response>
		*/

		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo '<Response>';
		echo '<playtext>'.$msg.'</playtext>';
		echo '</Response>';
	}

	//gets the message associated with the phone number
	function doQuery($mobileNo) {
		$link = mysql_connect('localhost', 'akshat_kookoo', 'kookooPasswordDontHack') or die(mysql_error());
		mysql_select_db("kookoo");
		$result = mysql_query("SELECT number, message FROM readcall where number='".$mobileNo."'",$link) or die(mysql_error());
		$count = count($result);

		if($count==1) {
			$row = mysql_fetch_assoc($result);
			return $row['message'];
		}
		else {
			return "";
		}
	}

?>