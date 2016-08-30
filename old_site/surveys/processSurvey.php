<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Survey</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="surveyStyles.css" rel="stylesheet" type="text/css">

</head>

<body onLoad="resizeTo(600,320)">

<table width="550"  border="0" cellpadding="5" cellspacing="0">
  <tr class="headerBack">
    <td><img src="logo.jpg" width="558" height="100"></td>
  </tr>
<?php

$formValues = array();
$formKeys = array();
global $formName;
$formName = "";

$response= ($_REQUEST);


foreach($response as $key => $value){

if($key == "surveyName"){
$formName = "$value";
}else{
array_push($formValues,$value);

}
}

echo"<tr class=\"header\">\n<td>$formName</td>\n</tr>\n";

// get formKeys

require("../DBinfo.php");
$tablename = "surveys";
$link = mysql_connect($host,$user,$password);

$query = "SELECT formFields FROM surveys WHERE surveyName ='$formName' LIMIT 1";

$result = mysql_db_query($dbname,$query,$link);
while($row = mysql_fetch_array($result)){

$allFields = explode("|",$row[formFields]);

	for($i=0;$i<count($allFields);$i++){
	
	$thisVal = trim($allFields[$i]);
	array_push($formKeys,$thisVal);
	
	}


}

$body= "$formName\r\n\r\n";
for($i=0;$i<count($formKeys);$i++){

$body .= "$formKeys[$i]: $formValues[$i]\r\n";

}

$subject = "WEBSITE SURVEY: $formName";

$mailTo = "chris@legacytelecom.co.uk";
mail($mailTo,$subject,$body,"FROM: website@lan.co.uk");


//add to surveyResults
$resultFields = "";
for($i=0;$i<count($formKeys);$i++){
	

		if($i == 0){
		$resultFields .="$formValues[$i]";
		}else{
		$resultFields .="|$formValues[$i]";
		}

}
$date = date("Y-m-d");
$query = "INSERT INTO survey_results VALUES('','$formName','$resultFields','$date');";

if(mysql_db_query($dbname,$query,$link)){
	
	echo "<tr><td>&nbsp;</td></tr>\n<tr><td>Thank You. The survey has been successfully sent.</td></tr>\n<tr><td>&nbsp;</td></tr>\n";
	
	}else{
	
	echo "<tr><td>&nbsp;</td></tr>\n<tr><td>Sorry. The survey could not be sent at this this</td></tr>\n<tr><td>&nbsp;</td></tr>\n";
	
	}
?>

<tr class="header">
    <td>&nbsp;</td>
  </tr>
  <tr class="header">
    <td>&nbsp;</td>
  </tr>
</table>

</body>
</html>

 