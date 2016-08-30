<?php



//check to see if they have tried logging in

if((!isset($PHP_AUTH_USER)) OR ($login_page AND !strcmp($existing_username, $PHP_AUTH_USER))){
header("WWW-Authenticate: Basic realm = \"Legacy\"");
header("HTTP/1.0 401 Unauthorised");
echo"<html><head><script language=\"javascript\">window.location = \"http://www.lan.co.uk/unauthorised.htm\" </script></head><body></body></html>";



exit;
}else{

//get username and password from UserList



$thisFirstName = "legacy telecom";
$thisSurname = "administrator";
$thisUN = "legacy";
$thisPW = "fullaccess";

$thisAuthorised = "yes";
$thisRestricted = "";


	if(($PHP_AUTH_USER == $thisUN) and ($PHP_AUTH_PW == $thisPW) and ($thisAuthorised == "yes")){
// proper access info

// add log on to database



 		if(strstr($PHP_SELF, "default.php") || strstr($PHP_SELF, "editFAQ.php")){
		
		session_start();
		$userName = $thisUN;
		$pWord = $thisPW;
		$domain = $thisDomain;
		$aDomain = $authDomain;
		$auth = $thisAuthorised;
		$restricted = $thisRestricted;
		$firstName = $thisFirstName;
		$surname = $thisSurname;
		
		session_register('userName');
		session_register('pWord');
		session_register('domain');
		session_register('aDomain');
		session_register('auth');
		session_register('restricted');
		session_register('firstName');
		session_register('surname');
		if(strstr($PHP_SELF, "editFAQ.php")){
		
		if($automatic){
		
		$question = trim($question);
		$coded = urlencode($question);
		$email = trim($email);
		
		header("Location: editFAQ.php?checked=true&automatic=true&question=$coded&email=$email&coded=true");
		}else{
		header("Location: editFAQ.php?checked=true");
		}
		}else{
		 header("Location: http://d2l.lan.co.uk/LT/LTpages.php");
		 }
 		exit;
 		}
 	}else{
 		
 
		if(!strstr($PHP_SELF, "default.php")){
 
 		header("Location: default.php");
 		exit;
 		}
	}
}
?>
 