<?php
if(!$checked){
require_once ("../authentication.php");
}
require_once("../header.php");
global $allowAccess;
if((($authDomain == $thisDomain) or ($thisDomain == "lantec")) and ($thisAuth == "yes")){

//check Restricted
if($thisRestricted != ""){
$pageArray = explode(",",$thisRestricted);
$total =count($pageArray);

for($i=0;$i<$total;$i++){
$thisP = $PHP_SELF;

if($pageArray[$i] == $thisP){
$allowAccess = 0;
break; // breaks out of the loop
}else{
$allowAccess = 1;
}
}
}else{
$allowAccess = 1;
}
// ends the check restricted pages loop




if($allowAccess == 1){



// START PAGE

global $topImage;
$topImage = "../logo.jpg";


global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;

require("../DBinfo.php");
$tablename = "surveys";
$link = mysql_connect($host,$user,$password);





if ($beensubmitted){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");

echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";



$surveyName = trim($surveyName);
$allFields = trim($allFields);
$date = date("Y-m-d");

$formFields = "";

$toWriteHeader = "<html>\n<title>$surveyName</title>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n<link href=\"surveyStyles.css\" rel=\"stylesheet\" type=\"text/css\">\n";
$toWriteHeader .= "<script language=\"javascript\">\n\n\nfunction checkForm(){\n\nfailList=\"\"\n";

$toWrite ="if(failList ==\"\"){\n\ndocument.theForm.submit();\n\n}else{\n\ntoAlert=\"Please complete the following fields, \"+failList;\n\nalert(toAlert);\n\n}\n}\nfunction checkWin(){\n\nwinHeight = 100;\nwindow.resizeTo(580,winHeight);\n\ndocument.body.scrollTop = 100000;\nscreenMax = screen.height - 50;\nif((document.body.scrollTop> 0)&&(screenMax > winHeight)){\n\nresizeWin();\n\n}\n\n\n\n\n\n}\n\nfunction resizeWin(){\n\nwinHeight +=20;\n\nwindow.resizeTo(600,winHeight);\n\ndocument.body.scrollTop = 100000;\n\nscreenMax = screen.height- 50;\n\nif((document.body.scrollTop> 0)&&(screenMax > winHeight)){\n\nresizeWin();\n\n}else if(winHeight > screenMax){\n\nwindow.resizeTo(600,screenMax);\ndocument.body.scrollTop = 0;\n\n\n}\n\n}\n\n</script>\n\n";
$toWrite .= "</head>\n<body onLoad =\"checkWin()\">\n";
$toWrite .="<form name=\"theForm\" action=\"processSurvey.php\" method=post><table width=\"550\"  border=\"0\" cellpadding=\"5\" cellspacing=\"0\">\n<tr class=\"headerBack\"><td colspan=\"2\"><img src=\"logo.jpg\" width=\"558\" height=\"100\"></td></tr>\n";
$toWrite .= "<tr class=\"header\"><td colspan=\"2\">$surveyName</td></tr>\n";
$toWrite .="<input type=hidden name=\"surveyName\" value=\"$surveyName\">\n";

$allF = explode("^",$allFields);
$allFLen = count($allF) - 1;

$javascriptStr = "";

for($i=0;$i<$allFLen;$i++){

$allFd = explode("|",$allF[$i]);

$txt = $allFd[0];
$obj = $allFd[1];
$val = $allFd[2];
$req = $allFd[3];

					$pattern = "\r";
					$replace = "";
					$txt1 = eregi_replace($pattern,$replace,$txt);
					$pattern = "\n";
					$replace = "";
					$txt1 = eregi_replace($pattern,$replace,$txt1);

if(($formFields == "")&&($obj !="comment")){
$formFields .= "$txt1";
}elseif($obj !="comment"){
$formFields .="|$txt1";
}

if(($req == "yes")&&($obj !="comment")){

	if(($obj == "checkBox")||($obj == "radioButton")){
	
	$javascriptStr .= "\nif(document.theForm.f$i.checked !=true){\n\nfailList +=\"$txt1, \";\n\n}\n";
	
	}else{
	$javascriptStr .= "\nif(document.theForm.f$i.value ==\"\"){\n\nfailList +=\"$txt1, \";\n\n}\n";
	}

}






$allVals = explode("\n",$val);


		if($obj == "inputText"){
		
		$objStr = "<input type=text name=\"f$i\" size=40 value=\"$allVals[0]\" class=\"i250\">\n";
		
		}else if($obj == "textarea"){
		
		$objStr ="<textarea name=\"f$i\" cols=32 rows=5>$val</textarea>\n";
		
		}else if($obj == "radioButton"){
		
		$objStr = "<input type=radio name=\"f$i\" value=\"$allVals[0]\" unchecked>\n";
		
		}else if($obj == "checkBox"){
		
		$objStr = "<input type=checkbox name=\"f$i\" value=\"$allVals[0]\" unchecked>\n";
		
		}else if($obj == "list"){
		
		$objStr = "<select name=\"f$i\">\n";
		
				for($j=0;$j<count($allVals);$j++){
				
					$pattern = "\r";
					$replace = "";
					$allVals[$j] = eregi_replace($pattern,$replace,$allVals[$j]);
					$pattern = "\n";
					$replace = "";
					$allVals[$j] = eregi_replace($pattern,$replace,$allVals[$j]);
		
					$objStr .= "<option value=\"$allVals[$j]\">$allVals[$j]</option>\n";
		
				}
		
		$objStr .="</select>\n";
		
		}else{
		
		$objStr =$obj;
		
		}


		if($obj =="comment"){
		
		$newtxt = eregi_replace("\n","<br>",$txt);
		
		

		$toWrite .= "<tr ID=\"f$i\" ><td colspan=2 width=500>$newtxt</td></tr>";
		
		}else{
		
		$toWrite .= "<tr ID=\"f$i\"><td width=250 align=\"right\">$txt</td><td width=250 align=\"left\">$objStr</td></tr>";
		
		}

}

$toWrite .= "<tr class=\"header\"><td colspan=\"2\"><a href=\"javascript:checkForm()\">submit</a></td></tr>\n<tr class=\"header\"><td colspan=\"2\"><a href=\"javascript:document.theForm.reset();\">reset</a></td></tr>\n</table></form>\n</body>\n</html>";



$htmlToWrite = $toWriteHeader.$javascriptStr.$toWrite;

$url = eregi_replace(" ","_",$surveyName).".htm";
$url = strtolower($url);

$pattern = "[?'\"]";
$replace = "";
$url = eregi_replace($pattern,$replace,$url);

global $success;
$success = 0;

$toOpen = "../surveys/$url";
if($open = fopen($toOpen,"w+")){

fwrite($open,"$htmlToWrite");

fclose($open);
$success = 1;
}


$url = "www.legacytelecom.co.uk/surveys/".$url;

$query ="INSERT into $tablename values('','$surveyName','$formFields','$url','$date')";

if($success == 1){

	if(mysql_db_query($dbname,$query,$link)){
	
	echo "<tr><td>The Survey has been successfully created</td></tr>";
	
	}else{
	
	echo "<tr><td>The Survey could not be created</td></tr>";
	
	}

}else{

echo "<tr><td>Sorry. The htm file could not be created</td></tr>";

}





}else if ($toDelete){

$url = trim($url);
$surveyName = trim($surveyName);

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";

$Link = mysql_connect ($Host, $User, $Password);

$Query1 = "DELETE from $tablename WHERE id = '$id';";
$Result = mysql_db_query ($DBName, $Query1, $Link);



if (mysql_db_query ($dbname, $Query1, $link)){
echo "<tr><td>The Survey has been successfully deleted</td>";

$fileToRemove = "../surveys/$url";
	if(unlink($fileToRemove)){
	
	echo "<tr><td>The file $url has been successfully deleted</td></tr>";
	
	}else{
	
	echo "<tr><td>The file $url could not deleted</td></tr>";
	
	}
	
	$query2 = "DELETE FROM survey_results WHERE surveyName='$surveyName'";	
	if (mysql_db_query ($dbname, $query2, $link)){
	echo "<tr><td>The Survey Results have been successfully deleted</td>";
	}else{
	echo "<tr><td>The Survey Results could not deleted</td></tr><td>&nbsp;</td></tr>";
	}

	

}else{

echo "<tr><td>The Survey could not deleted</td></tr><tr><td>&nbsp;</td></tr>";
}


mysql_close ($link);

echo"<tr><td>&nbsp;</td></tr></table>";













}elseif($view){



echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\">";
echo "<script language=\"javascript\">function openConfirm(returnLink,id,url,surveyName){leftPos = (screen.width - 300)/2;topPos = (screen.height - 200)/2;window.open(\"confirm4.php?returnLink=\"+returnLink+\"&id=\"+id+\"&url=\"+url+\"&surveyName=\"+surveyName ,\"\",\"width=300,height=200,left=\"+leftPos+\",top=\"+topPos+\",scrollbars=no,toolbar=no,location=no\");}</script>";
echo"</head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");

echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo"<tr><td colspan=\"9\"><a href=\"editSurveys.php?checked=true\">Click Here To Add A New Survey</a></td></tr><tr><td colspan=\"9\">&nbsp;</td></tr>"; 
echo"<tr class = \"backBlue\"><td>Survey Name</td><td>Date Posted</td><td>Number Of Submissions</td><td>URL</td></tr>\n";


$query = "SELECT * from $tablename ORDER by date DESC;";

$result = mysql_db_query($dbname,$query,$link);
while($row = mysql_fetch_array($result)){

	$query2 = "SELECT COUNT(*) AS total from survey_results WHERE surveyName='$row[surveyName]';";
	$result2 = mysql_db_query($dbname,$query2,$link);
	while($row2 = mysql_fetch_array($result2)){
	
	$totalSubs = $row2[total];
	
	}
$urlBroken = explode("/",$row[url]);
$urlBL = count($urlBroken) - 1;
$url = $urlBroken[$urlBL];

if($totalSubs > 0){
$numSub = $totalSubs;
}else{
$numSub = "-----";
}
echo "<tr class = \"line2\"><td>$row[surveyName]</td><td>$row[date]</td><td>$numSub</td><td>javascript:openSurvey('http://$row[url]')</td></tr>\n";
echo "<tr><td colspan=9><a href=\"editSurveys.php?viewResults=true&id=$row[id]\">view results</a> | <a href=\"javascript:openConfirm('editSurveys.php','$row[id]','$url','$row[surveyName]')\">delete survey</a></td></tr>\n";

}


echo "<tr><td colspan=9>&nbsp;</td></tr>\n</table></body></html>";




}else if($viewResults){

$id = trim($id);
global $formKeys;
$formKeys = array();
// get details form survey db

$query = "SELECT * FROM surveys WHERE id ='$id' ";

$result = mysql_db_query($dbname,$query,$link);
while($row = mysql_fetch_array($result)){

$allFields = explode("|",$row[formFields]);

	for($i=0;$i<count($allFields);$i++){
	
	array_push($formKeys,$allFields[$i]);
	
	}
$formName = $row[surveyName];


}


echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\">";
echo "<script language=\"javascript\">function openConfirm(returnLink,id,url){leftPos = (screen.width - 300)/2;topPos = (screen.height - 200)/2;window.open(\"confirm4.php?returnLink=\"+returnLink+\"&id=\"+id+\"&url=\"+url ,\"\",\"width=300,height=200,left=\"+leftPos+\",top=\"+topPos+\",scrollbars=no,toolbar=no,location=no\");}</script>";
echo"</head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");

echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo"<tr class = \"backBlue\" ><td colspan=\"9\">Results : $formName</td></tr>\n";
	echo "<tr><td colspan=9>&nbsp;</td></tr>\n";

$query = "SELECT * FROM survey_results WHERE surveyName ='$formName' ORDER by date DESC ";

$result = mysql_db_query($dbname,$query,$link);
while($row = mysql_fetch_array($result)){

	
	$allFields = explode("|",$row[resultFields]);

	echo "<tr><td colspan=9>Date Submitted: $row[date]</td></tr>\n";
	
	for($i=0;$i<count($formKeys);$i++){
	
	echo "<tr class = \"line2\"><td>$formKeys[$i]</td><td>$allFields[$i]</td></tr>\n";
	

	}

	echo "<tr><td colspan=9>&nbsp;</td></tr>\n";
	echo "<tr><td colspan=9>&nbsp;</td></tr>\n";


}




echo "<tr ><td colspan=9>&nbsp;</td></tr>\n</table></body></html>";




}else{





echo "<html><head><title>Edit Surverys</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\">";
echo "<script language=\"javascript\">


allFields = new Array();
autoClose = 1;

function addField(){


thisField = document.theForm.txt.value + \"|\" + document.theForm.formObj.value + \"|\" + document.theForm.values.value+ \"|\" + document.theForm.required.value;
allFields.push(thisField);


sendObj = \"\";
	for(i=0;i<allFields.length;i++){
	
	sendObj += allFields[i]+\"^\";
	
	
	}
document.theForm.allFields.value=sendObj;

openPreview();



}


function removeField(field){
allFields.splice(field,1);

thisMenu = preview.document.getElementById('i'+field).style;
thisMenu.display = \"none\";

sendObj = \"\";
	for(i=0;i<allFields.length;i++){
	
	sendObj += allFields[i]+\"^\";
	
	
	}
document.theForm.allFields.value=sendObj;


}

function openPreview(){

toWrite = \"<html>\\n<title>Survey Preview</title>\\n<head>\\n<meta http-equiv=\\\"Content-Type\\\" content=\\\"text/html; charset=iso-8859-1\\\">\\n<link href=\\\"../surveys/surveyStyles1.css\\\" rel=\\\"stylesheet\\\" type=\\\"text/css\\\">\\n</head>\\n<body >\\n\";
toWrite +=\"<table width=\\\"550\\\"  border=\\\"0\\\" cellpadding=\\\"5\\\" cellspacing=\\\"0\\\">\\n<tr class=\\\"headerBack\\\"><td colspan=\\\"2\\\"><img src=\\\"../surveys/logo.jpg\\\" width=\\\"558\\\" height=\\\"100\\\"></td></tr>\\n\";
toWrite += \"<tr class=\\\"header\\\"><td colspan=\\\"2\\\">\"+document.theForm.surveyName.value+\"</td></tr>\\n\";
  
  
for(i=0;i<allFields.length;i++){

allF = allFields[i];
allFull = allF.split(\"|\");
txt = allFull[0];
obj = allFull[1];
val = allFull[2];
req = allFull[3];

allVals = val.split(\"\\n\");


		if(obj == \"inputText\"){
		
		objStr = \"<input type=text name=\\\"f\"+i+\"\\\" size=40 value=\\\"\"+allVals[0]+\"\\\" class=\\\"i250\\\">\\n\";
		
		}else if(obj == \"textarea\"){
		
		objStr =\"<textarea name=\\\"f\"+i+\"\\\" cols=32 rows=5>\"+val+\"</textarea>\\n\";
		
		}else if(obj == \"radioButton\"){
		
		objStr = \"<input type=radio name=\\\"f\"+i+\"\\\" value=\\\"\"+allVals[0]+\"\\\">\\n\";
		
		}else if(obj == \"checkBox\"){
		
		objStr = \"<input type=checkbox name=\\\"f\"+i+\"\\\" value=\\\"\"+allVals[0]+\"\\\">\\n\";
		
		}else if(obj == \"list\"){
		
		objStr = \"<select name=\\\"f\"+i+\"\\\">\\n\";
		
				for(j=0;j<allVals.length;j++){
		
					objStr += \"<option value=\\\"\"+allVals[j]+\"\\\">\"+allVals[j]+\"</option>\\n\";
		
				}
		
		objStr +=\"</select>\\n\";
		
		}else{
		
		objStr =obj;
		
		}


		if(obj ==\"comment\"){
		
		newtxt = txt.split(\"\\n\");
		newtxt1 = \"\";
		
		for(k=0;k<newtxt.length;k++){
		
			if(k == 0){
			newtxt1 += newtxt[k];
			}else{
			newtxt1 += \"<br>\"+newtxt[k];
			}
		}

		toWrite += \"<tr ID=\\\"i\"+i+\"\\\" ><td colspan=2 width=500>\"+newtxt1+\"</td><td ><a href=\\\"javascript:opener.removeField('\"+i+\"');\\\">remove</a></td></tr>\\n\";
		
		}else{
		
		toWrite += \"<tr ID=\\\"i\"+i+\"\\\"><td width=250 align=\\\"right\\\">\"+txt+\"</td><td width=250 align=\\\"left\\\">\"+objStr+\"</td><td><a href=\\\"javascript:opener.removeField('\"+i+\"');\\\">remove</a></td></tr>\\n\";
		
		}

}

toWrite += \"<tr class=\\\"header\\\"><td colspan=\\\"2\\\">submit </td></tr>\\n<tr class=\\\"header\\\"><td colspan=\\\"2\\\">reset</td></tr>\\n</table>\\n</body>\\n</html>\";


screenH = screen.height - 100;
screenY = 10;
screenX = (screen.width -650)/2;

preview = window.open(\"\",\"preview\",\"width=650,height=\"+screenH+\",left=\"+screenX+\",top=\"+screenY+\",toolbars=no,location=no,resizable=yes,scrollbars=yes\");
preview.document.write(toWrite);
preview.focus();




}


</script>\n";


echo "</head><body onFocus=\"preview.close();\">";

echo"<form action=\"editSurveys.php\" name=\"theForm\" method=\"post\"  >\n";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");

echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><a href=\"editSurveys.php?checked=true&view=true\">Click Here To View All Surveys</a></td></tr><tr><td>&nbsp;</td></tr>";

echo "<tr><td>Use the follwing options to create a new survey, once you have finished creating the field click '<u>add to form</u>'.<br>Click '<u>finish</u>' to finish creating the survey and create the form</td></tr><tr><td>&nbsp;</td></tr>";

echo "<tr><td>Survey Name</td></tr><tr><td><input type=text name=\"surveyName\" size=60></td></tr>\n";
echo "<tr><td>Form Object</td></tr><tr><td><select name=\"formObj\"><option value=\"comment\" selected>comment</option><option value=\"inputText\" >text input</option><option value=\"textarea\">textarea</option><option value=\"radioButton\">radio button</option><option value=\"checkBox\">check box</option><option value=\"list\">list</option></select></td></tr>";
echo "<tr><td>Text</td></tr><tr><td><textarea name=\"txt\" cols=50 rows=5 ></textarea></td></tr>";
echo "<tr><td>Values</td></tr><tr><td><textarea name=\"values\" cols=50 rows=5 ></textarea></td></tr>";
echo "<tr><td>Required</td></tr><tr><td><select name=\"required\"><option value=\"yes\" selected>yes</option><option value=\"no\" >no</option></select></td></tr>";
echo"<input type=hidden name=\"beensubmitted\" value=true>\n";
echo"<input type=hidden name=\"allFields\" >\n";
echo"<input type=hidden name=\"checked\" value=TRUE>\n";
echo"<input type=hidden name=\"visitors\" value=\"1\">\n";
echo "<tr><td><a href=\"javascript:addField();\">add to form</a> </td></tr>";
echo "<tr><td >&nbsp;</td></tr>";
echo "<tr><td >&nbsp;</td></tr>";
echo "<tr><td >&nbsp;</td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.submit();\">finish</a> </td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.reset();\">reset</a> </td></tr>";
echo"</form>";
echo "</table></body></html>";


}





// END PAGE


}else{
// if this page is in the users restricted list
echo "access denied";

}
// if they have not logged on
}else{

header("Location: http://www.lan.co.uk/unauthorised.htm");
}
	

?>
 