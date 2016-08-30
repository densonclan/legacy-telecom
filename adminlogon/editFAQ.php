<?php
if(!$checked){
require_once ("../authentication.php");
}
require_once("../header.php");
global $allowAccess;
if(($thisAuth == "yes")){

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
$tablename = "FAQ";
$link = mysql_connect($host,$user,$password);

if($automatic){

$question = trim($question);
$email = trim($email);

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";

echo"<form action=\"editFAQ.php\" name=\"theForm\" method=\"post\"  >\n";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td>Fill in the question and click submit</td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td>question</td></tr><tr><td><input type=text name=\"question\" size=70 value=\"$question\"></td></tr>";
echo "<tr><td>answer</td></tr><tr><td><textarea name=\"answer\" cols=50 rows = 20></textarea></td></tr>";
echo "<tr><td>Restricted<br><select name=\"restricted\"><option value=0 selected>0</option><option value=1 >1</option></select></td></tr>\n";
echo"<input type=hidden name=\"beensubmitted\" value=true>\n";
echo"<input type=hidden name=\"checked\" value=TRUE>\n";
echo"<input type=hidden name=\"visitors\" value=\"1\">\n";
echo"<input type=hidden name=\"email\" value=\"$email\">\n";
echo "<tr><td><a href=\"javascript:document.theForm.submit();\">submit</a> </td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.reset();\">reset</a> </td></tr>";
echo"</form>";
echo "</table></body></html>";

}else{

if($confirmDelete){
echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\">";

echo"<script language=\"javascript\">function checkForm(){if(document.theForm.confirm.checked != true){alert(\"You need to tick the box to confirm deletion\");}else{document.theForm.submit();}}</script>";
echo"</head><body>";

echo"<form name=\"theForm\" action=\"editFAQ.php?checked=true\" method=\"post\">";

echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><i>Are you sure you want to delete for following FAQ:-</i></td></tr><td>&nbsp;</td></tr>";

//gets database info
$Query1 = "SELECT * from $tablename WHERE id='$toDelete';";
$Result = mysql_db_query ($dbname, $Query1, $link);


// Fetch Results from database.

while ($Row = mysql_fetch_array($Result)){


echo"<tr class=\"line2\"><td><b><i>Q: $Row[question]</i></b><i> has been asked </i><b>$Row[visitors]</b> time(s)</td></tr>";
echo"<tr class=\"line2\"><td>A: $Row[answer]</td></tr>";
echo"<tr><td>&nbsp;</td></tr>";	
}
mysql_close ($link);

echo "<tr><td>&nbsp;</td></tr><tr><td><input type=\"checkbox\" name=\"confirm\" value=\"true\">";
echo "Tick box to confirm deletion of this FAQ. Once deleted this is <b><u>NOT</u></b> undoable";

echo "</td></tr>";
echo "<input type=hidden name=\"Delete\" value=true>\n";
echo "<input type=hidden name=\"id\" value=\"$toDelete\">\n";
echo "<tr><td>&nbsp;</td></tr><tr><td><a href=\"javascript:checkForm();\">delete</a> </td></tr>";
echo "<tr><td><a href=\"editFAQ.php?checked=true&view=true\">cancel</a> </td></tr>";

print("</table><br><br>");


}else if ($Delete){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";

$Link = mysql_connect ($Host, $User, $Password);

$Query1 = "DELETE from $tablename WHERE id = '$id';";
$Result = mysql_db_query ($DBName, $Query1, $Link);



if (mysql_db_query ($dbname, $Query1, $link)){
echo "<tr><td>The FAQ has been successfully deleted</td></tr><td>&nbsp;</td></tr>";

}else{

echo "<tr><td>The FAQ could not deleted</td></tr><td>&nbsp;</td></tr>";
}


mysql_close ($link);

/********************
Create frequently-asked-questions.htm

*********************/


include("createFAQHtml.php");


/*********************
END 
**********************/

echo"<tr><td>&nbsp;</td></tr></table>";

}

/* edits database */
if($edit){

$toEdit = trim($toEdit);

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";

echo"<form action=\"editFAQ.php\" name=\"theForm\" method=\"post\"  >\n";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td>Edit the FAQ and click 'submit changes'</td></tr><tr><td>&nbsp;</td></tr>";

$Query1 = "SELECT * from $tablename WHERE id='$toEdit';";
$Result = mysql_db_query ($dbname, $Query1, $link);


// Fetch Results from database.

while ($Row = mysql_fetch_array($Result)){

$pattern = "<br>";
$replace = "\r";
$Row[answer] = eregi_replace($pattern,$replace,$Row[answer]);

echo "<tr><td>question</td></tr><tr><td><input type=text name=\"newquestion\" size=70 value=\"$Row[question]\"></td></tr>";
echo "<tr><td>answer</td></tr><tr><td><textarea name=\"newanswer\" cols=50 rows = 20>$Row[answer]</textarea></td></tr>";
echo "<tr><td>visitors</td></tr><tr><td><input type=text name=\"newvisitors\" size=70 value=\"$Row[visitors]\"></td></tr>";
echo "<tr><td>Restricted<br><select name=\"restricted\"><option value=$Row[restricted] selected>$Row[restricted]</option><option value=0 >0</option><option value=1 >1</option></select></td></tr>\n";

}
@mysql_close ($link);

echo"<input type=hidden name=\"toEdit\" value=\"$toEdit\">\n";
echo"<input type=hidden name=\"editEntry\" value=true>\n";
echo"<input type=hidden name=\"checked\" value=TRUE>\n";

echo"<input type=hidden name=\"email\" value=\"$email\">\n";
echo "<tr><td><a href=\"javascript:document.theForm.submit();\">submit changes</a> </td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.reset();\">reset</a> </td></tr>";
echo"</form>";
echo "</table></body></html>";




}else if ($editEntry){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";

$newquestion = trim($newquestion);
$newanswer = trim($newanswer);
$newvisitors = trim($newvisitors);

$pattern = "\&";
$replace = "and";
$newquestion = eregi_replace($pattern,$replace,$newquestion);
$newanswer = eregi_replace($pattern,$replace,$newanswer);

$pattern = "\'";
$replace = "";
$newquestion = eregi_replace($pattern,$replace,$newquestion);
$newanswer = eregi_replace($pattern,$replace,$newanswer);
$pattern = "\?";
$replace = "";
$newquestion = eregi_replace($pattern,$replace,$newquestion);
$newanswer = eregi_replace($pattern,$replace,$newanswer);

$replace = "";
$pattern = "\r";
$newquestion = eregi_replace($pattern,$replace,$newquestion);
$newanswer = eregi_replace($pattern,$replace,$newanswer);

$replace = "<br>";
$pattern = "\n";
$newquestion = eregi_replace($pattern,$replace,$newquestion);
$newanswer = eregi_replace($pattern,$replace,$newanswer);

$replace = "'";
$pattern = "’";
$newquestion = eregi_replace($pattern,$replace,$newquestion);
$newanswer = eregi_replace($pattern,$replace,$newanswer);

$replace = "'";
$pattern = "‘";
$newquestion = eregi_replace($pattern,$replace,$newquestion);
$newanswer = eregi_replace($pattern,$replace,$newanswer);

$restricted = trim($restricted);

$Query1 = "UPDATE $tablename Set question = \"$newquestion\",answer = \"$newanswer\",visitors = \"$newvisitors\", restricted=\"$restricted\"  WHERE id = \"$toEdit\";";
$Result = mysql_db_query ($DBName, $Query1, $Link);



if (mysql_db_query ($dbname, $Query1, $link)){
	echo "<tr><td>The FAQ has been successfully updated</td></tr><td>&nbsp;</td></tr>";
} else {
	echo "<tr><td>The FAQ has could not be updated at time time.</td></tr><td>&nbsp;</td></tr>";
}


mysql_close ($link);



/********************
Create frequently-asked-questions.htm

*********************/


include("createFAQHtml.php");


/*********************
END 
**********************/

echo"<tr><td>&nbsp;</td></tr></table>";

}

if ($beensubmitted){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");

echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";

$question = trim($question);
$answer = trim($answer);
$visitors = trim($visitors);
$restricted = trim($restricted);

if($email){
$email = trim($email);
}


if($visitors == ""){
$visitors = 1;



}

$pattern = "\&";
$replace = "and";
$question = eregi_replace($pattern,$replace,$question);
$answer = eregi_replace($pattern,$replace,$answer);

$pattern = "\'";
$replace = "";
$question = eregi_replace($pattern,$replace,$question);
$answer = eregi_replace($pattern,$replace,$answer);
$pattern = "\?";
$replace = "";
$question = eregi_replace($pattern,$replace,$question);
$answer = eregi_replace($pattern,$replace,$answer);

$replace = "";
$pattern = "\r";
$question = eregi_replace($pattern,$replace,$question);
$answer = eregi_replace($pattern,$replace,$answer);

$replace = "<br>";
$pattern = "\n";
$question = eregi_replace($pattern,$replace,$question);
$answer = eregi_replace($pattern,$replace,$answer);

$replace = "'";
$pattern = "’";
$question = eregi_replace($pattern,$replace,$question);
$answer = eregi_replace($pattern,$replace,$answer);

$replace = "'";
$pattern = "‘";
$question = eregi_replace($pattern,$replace,$question);
$answer = eregi_replace($pattern,$replace,$answer);


//check question

global $n;
$n=0;
$query = "SELECT * FROM $tablename WHERE question like \"$question\"";

$link1 = mysql_connect($host,$user,$password);
$result1 = mysql_db_query ($dbname,$query,$link1);

while($row1 = mysql_fetch_array($result1)){
$n++;
}
mysql_close($link1);

if($n == 0){




$Query = "INSERT into $tablename values (\"0\", \"$question\" , \"$answer\" , \"$visitors\", \"$restricted\"  )";

if (mysql_db_query ($dbname, $Query, $link)){
	echo"<tr><td>The entry was successfully added!</td></tr>";
} else {
	echo"<tr><td>The entry could not be added!</td></tr>";
}

if($email){

$subject = "Legacy Telecom FAQ";
$mailTo = $email;
$body = "Thank You for your question. Here is our reply:-\n\nQuestion:\n".$question."\n\nAnswer:\n".$answer."\n\n";

$mailFrom = "sales@legacytelecom.co.uk";

if(mail($mailTo,$subject,$body,"FROM:$mailFrom")){
echo"<tr><td>An email has been sent to the customer</td></tr>";
}else{
echo"<tr><td>An email could not be sent to the customer</td></tr>";
}


}

mysql_close ($link);
}else{
echo"<tr><td>This Question has already been added! Check entries</td></tr>";

}

/********************
Create frequently-asked-questions.htm

*********************/


include("createFAQHtml.php");


/*********************
END 
**********************/



echo"<tr><td>&nbsp;</td></tr></table>";
}

/* uploads to database */
// Trim the incoming data


if($view){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"100%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");

echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";


$Query1 = "SELECT * from $tablename ORDER BY question,answer,visitors DESC,id;";
$Result = mysql_db_query ($dbname, $Query1, $link);


echo"<tr><td><a href=\"editFAQ.php?checked=true\">Click Here To Add An New Question</a></td></tr><tr><td>&nbsp;</td></tr>";

// Fetch Results from database.

while ($Row = mysql_fetch_array($Result)){

if($Row[restricted] == 1){
echo"<tr class=\"lineRed\"><td><b><i>Q: $Row[question]</i></b><i> has been asked </i><b>$Row[visitors]</b> time(s)</td></tr>";
echo"<tr class=\"lineRed\"><td>A: $Row[answer]</td></tr>";
}else{
echo"<tr class=\"line2\"><td><b><i>Q: $Row[question]</i></b><i> has been asked </i><b>$Row[visitors]</b> time(s)</td></tr>";
echo"<tr class=\"line2\"><td>A: $Row[answer]</td></tr>";
}
echo"<tr><td><a href=\"editFAQ.php?checked=true&edit=true&toEdit=$Row[id]\">edit</a> | <a href=\"editFAQ.php?checked=true&confirmDelete=true&toDelete=$Row[id]\">delete faq</a></td></tr>";
echo"<tr><td>&nbsp;</td></tr>";	
}
mysql_close ($link);

print("</table><br><br>");

}

if((!$view)&&((!$beensubmitted)&&(!$confirmDelete)&&(!$Delete)&&(!$edit)&&(!$editEntry))){






echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";

echo"<form action=\"editFAQ.php\" name=\"theForm\" method=\"post\"  >\n";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");

echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><a href=\"editFAQ.php?checked=true&view=true\">Click Here To View All FAQ</a></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td>Fill in the question and click submit</td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td>question</td></tr><tr><td><input type=text name=\"question\" size=70 ></td></tr>";
echo "<tr><td>answer</td></tr><tr><td><textarea name=\"answer\" cols=50 rows = 20></textarea></td></tr>";
echo "<tr><td>Restricted<br><select name=\"restricted\"><option value=0 selected>0</option><option value=1 >1</option></select></td></tr>\n";

echo"<input type=hidden name=\"beensubmitted\" value=true>\n";
echo"<input type=hidden name=\"checked\" value=TRUE>\n";
echo"<input type=hidden name=\"visitors\" value=\"1\">\n";


echo "<tr><td><a href=\"javascript:document.theForm.submit();\">submit</a> </td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.reset();\">reset</a> </td></tr>";
echo"</form>";
echo "</table></body></html>";

}else if (($view)||($confirmDelete)||($edit)){


}else{

print("<a href=\"editFAQ.php?checked=true\">Click Here To Add An New Question</a><br>");
print("<a href=\"editFAQ.php?checked=true&view=true\">Click Here To View All FAQ</a><br>");

}
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
 