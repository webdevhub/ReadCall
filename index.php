<?php
	$error = "";$success = "";
	
	//check whether the user has POST number and message or not
	if(isset($_POST['message'])&&isset($_POST['number'])) {

		$msg = $_POST['message'];
		$numb = $_POST['number'];

		//validate the POST data
		if(validateData($msg,$numb)) {
			doQuery($msg, $numb);
		}
	}

	function validateData($message, $number) {
		global $error;global $success;

		$validated = false;

		//the message can contain characters such as, A-Z a-z 0-9 .,! (space)
		$regex_msg = "^[\.a-zA-Z0-9 ,!]+$";

		//the mobile number should be of 10 digits only
		$regex_mobNo = "^[0-9]{10}$";

		$valid_msg = eregi($regex_msg, $message);
		$valid_modNo = eregi($regex_mobNo, $number);
		
		if($valid_modNo&&$valid_msg) {
			//if both message and mobile number are valid, then SUCESS
			$validated = true;
			$success = 'Your message has been recorded. Please call on any of the three numbers, <span class="bold">040-39411020, 080-39411020, 022-39411020</span>. The Pin number is <span class="bold">1779</span>';

		} else {
			//if anyone of the message or mobile number is not valid, then FAILURE
			if (!$valid_modNo) {
				$error = "Please enter a valid 10-digit mobile number";
			} else if(!$valid_msg) {
				$error = "Your message should consist of only alphabets, numbers, spaces, dot(.)";
			}
		}
		
		return $validated;
	}

	//Store the POST data into the database
	function doQuery($message, $number) {

		//connect to the MySQL server
		$link = mysql_connect('localhost', 'akshat_kookoo', 'kookooPasswordDontHack') or die("Query 0:".mysql_error());

		//select the Database
		mysql_select_db("kookoo");

		$result = mysql_query("SELECT number, message FROM readcall WHERE number='$number'",$link) or die("Query 1:".mysql_error());

		if(mysql_num_rows($result)==0) {
			$result = mysql_query("INSERT INTO readcall (number, message) VALUES ('$number','$message')",$link) or die("Query 2:".mysql_error());
		} else {
			$result = mysql_query("UPDATE readcall SET message = '$message' WHERE number = '$number'",$link) or die("Query 3:".mysql_error());
		}

		//close the mysql connection
		mysql_close($link);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>ReadCall Application : Listen your message on phone</title>
<!-- Include the normal stylesheet-->
<link href="style.css" rel="stylesheet" />
</head>
<body>
      <div id="wrapper">
		<h2>ReadCall - Listen your message on phone</h2>

		<?php 
		//display the success message if the variable is not empty
		if($success!="") { ?>
		<div class="success"> <?php echo $success; ?> </div>
		<? } ?>

		<?php 
		//display the error message is the variable is not empty
		if($error!="") { ?>
			<div class="error"> <?php echo $error; ?> </div>
		<? } ?>

		<form action="index.php" method="post">
			<span>Number</span> <input type="text" class="btn" maxlength="10" name="number"/>
			<br/>
			<span>Message</span> <input type="text" class="btn" maxlength="100" name="message"/>
			<br/>
			<input class="btn" type="submit" value="Submit" />
		</form>
		<ul>
			<li>Enter your name, 10 digit mobile number and a message</li>
			<li>Call on any of the three numbers, <span class="bold">040-39411020, 080-39411020, 022-39411020</span>. All calls are normally charged, there are no premium charges.</li>
			<li>Enter the PIN number <span class="bold">1779</span></li>
			<li>Listen the text that you just wrote, on your phone</li>
		</ul>
      </div>
</body>
</html>