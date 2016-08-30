<?php


global $thisUser;
global $thisPWord;
global $thisDomain;
global $thisAuth;
global $thisResricted;
global $thisFirstName;
global $thisSurname;

session_start();
		
		$thisUser = "$HTTP_SESSION_VARS[userName]";
		$thisPWord = "$HTTP_SESSION_VARS[pWord]"; 
		$thisDomain  = "$HTTP_SESSION_VARS[domain]";
		$thisAuth = "$HTTP_SESSION_VARS[auth]";
		$thisRestricted ="$HTTP_SESSION_VARS[restricted]";
		$thisFirstName ="$HTTP_SESSION_VARS[firstName]";
		$thisSurname ="$HTTP_SESSION_VARS[surname]"; 

	
 	


?>
 