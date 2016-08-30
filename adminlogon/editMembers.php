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
$tablename = "members";
$link = mysql_connect($host,$user,$password);



if($confirmDelete){
echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\">";

echo"<script language=\"javascript\">function checkForm(){if(document.theForm.confirm.checked != true){alert(\"You need to tick the box to confirm deletion\");}else{document.theForm.submit();}}</script>";
echo"</head><body>";

echo"<form name=\"theForm\" action=\"editMembers.php?checked=true\" method=\"post\">";

echo "<table width=\"90%\"  border=\"0\"><tr><td colspan=\"9\" ><img src=\"$topImage\"></td></tr><tr><td colspan=\"9\" >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td colspan=\"9\">Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td colspan=\"9\">&nbsp;</td></tr>";
echo "<tr><td colspan=\"9\"><i>Are you sure you want to delete for following User:-</i></td></tr><td colspan=\"9\">&nbsp;</td></tr>";

//gets database info
$Query1 = "SELECT * from $tablename WHERE id='$toDelete';";
$Result = mysql_db_query ($dbname, $Query1, $link);

echo"<tr class = \"backBlue\"><td>Surname</td><td>Firstname</td><td>Username</td><td>Password</td><td>Authorised</td><td>Access Level</td><td>Notes</td></tr>\n";
// Fetch Results from database.

while ($Row = mysql_fetch_array($Result)){


if($Row[authorised] == "1"){
echo"<tr class = \"line2\"><td>$Row[surname]</td><td>$Row[firstname]</td><td>$Row[username]</td><td>$Row[password]</td><td>yes</td><td>$Row[accessLevel]</td><td>$Row[notes]</td></tr>\n";
}else{
echo"<tr class = \"lineRed\"><td>$Row[surname]</td><td>$Row[firstname]</td><td>$Row[username]</td><td>$Row[password]</td><td>no</td><td>$Row[accessLevel]</td><td>$Row[notes]</td></tr>\n";
}
echo"<tr><td colspan=\"9\">&nbsp;</td></tr>";	
}
mysql_close ($link);

echo "<tr><td colspan=\"9\">&nbsp;</td></tr><tr><td colspan=\"9\"><input type=\"checkbox\" name=\"confirm\" value=\"true\">";
echo "Tick box to confirm deletion of this User. Once deleted this is <b><u>NOT</u></b> undoable";

echo "</td></tr>";
echo "<input type=hidden name=\"Delete\" value=true>\n";
echo "<input type=hidden name=\"id\" value=\"$toDelete\">\n";
echo "<tr><td>&nbsp;</td></tr><tr><td><a href=\"javascript:checkForm();\">delete</a> </td></tr>";
echo "<tr><td><a href=\"editMembers.php?checked=true&view=true\">cancel</a> </td></tr>";

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
echo "<tr><td>The Member has been successfully deleted</td></tr><td>&nbsp;</td></tr>";

}else{

echo "<tr><td>The Member could not deleted</td></tr><td>&nbsp;</td></tr>";
}


mysql_close ($link);

echo"<tr><td>&nbsp;</td></tr></table>";

}

/* edits database */
if($edit){

$toEdit = trim($toEdit);

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";

echo"<form action=\"editMembers.php\" name=\"theForm\" method=\"post\"  >\n";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td>Edit the Member Details and click 'submit changes'</td></tr><tr><td>&nbsp;</td></tr>";

$Query1 = "SELECT * from $tablename WHERE id='$toEdit';";
$Result = mysql_db_query ($dbname, $Query1, $link);


// Fetch Results from database.

while ($Row = mysql_fetch_array($Result)){


echo "<tr><td>first name</td></tr><tr><td><input type=text name=\"newfirstName\" size=50 value=\"$Row[firstname]\"></td></tr>";
echo "<tr><td>surname</td></tr><tr><td><input type=text name=\"newsurname\" size=50 value=\"$Row[surname]\"></td></tr>";
echo "<tr><td>username</td></tr><tr><td><input type=text name=\"newuserName\" size=50 value=\"$Row[username]\"></td></tr>";
echo "<tr><td>password</td></tr><tr><td><input type=text name=\"newpword\" size=50 value=\"$Row[password]\"></td></tr>";
if($Row[authorised] == 1){
echo "<tr><td>authorised</td></tr><tr><td><select name=\"newauthorised\"><option value=\"1\" selected>yes</option><option value=\"0\">no</option></select></td></tr>";
}else{
echo "<tr><td>authorised</td></tr><tr><td><select name=\"newauthorised\"><option value=\"0\" selected>no</option><option value=\"1\">yes</option></select></td></tr>";
}
echo "<tr><td>access level</td></tr><tr><td><input type=text name=\"newaccessLevel\" size=50 value=\"$Row[accessLevel]\"></td></tr>";
echo "<tr><td>notes</td></tr><tr><td><textarea name=\"notes\" cols=\"50\" rows=\"5\">$Row[notes]</textarea></td></tr>";

}
@mysql_close ($link);

echo"<input type=hidden name=\"toEdit\" value=\"$toEdit\">\n";
echo"<input type=hidden name=\"editEntry\" value=true>\n";
echo"<input type=hidden name=\"checked\" value=TRUE>\n";

echo "<tr><td><a href=\"javascript:document.theForm.submit();\">submit changes</a> </td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.reset();\">reset</a> </td></tr>";
echo"</form>";
echo "</table></body></html>";




}else if ($editEntry){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";


$newfirstName = trim($newfirstName);
$newsurname = trim($newsurname);
$newuserName = trim($newuserName);
$newpword = trim($newpword);
$newauthorised = trim($newauthorised);
$newaccessLevel = trim($newaccessLevel);
$notes = trim($notes);

$pattern = "\"";
$replace = "";
$notes = eregi_replace($pattern,$replace,$notes);

$pattern = "'";
$replace = "";
$notes = eregi_replace($pattern,$replace,$notes);



$Query1 = "UPDATE $tablename Set firstname ='$newfirstName',surname='$newsurname',username='$newuserName',password = '$newpword',authorised='$newauthorised',accessLevel='$newaccessLevel',notes='$notes' WHERE id = '$toEdit';";
$Result = mysql_db_query ($DBName, $Query1, $Link);



if (mysql_db_query ($dbname, $Query1, $link)){
	echo "<tr><td>The Member has been successfully updated</td></tr><td>&nbsp;</td></tr>";
} else {
	echo "<tr><td>The Member has could not be updated at time time.</td></tr><td>&nbsp;</td></tr>";
}


mysql_close ($link);

echo"<tr><td>&nbsp;</td></tr></table>";

}

if ($beensubmitted){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");

echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";

$newfirstName = trim($newfirstName);
$newsurname = trim($newsurname);
$newuserName = trim($newuserName);
$newpword = trim($newpword);
$newauthorised = trim($newauthorised);
$newaccessLevel = trim($newaccessLevel);
$notes = trim($notes);

$pattern = "\"";
$replace = "";
$notes = eregi_replace($pattern,$replace,$notes);

$pattern = "'";
$replace = "";
$notes = eregi_replace($pattern,$replace,$notes);


$Query = "INSERT into $tablename values ('0', '$newfirstName' , '$newsurname' ,'$newuserName','$newpword','$newauthorised','$newaccessLevel','$notes','','' )";

if (mysql_db_query ($dbname, $Query, $link)){
	echo"<tr><td>The entry was successfully added!</td></tr>";
} else {
	echo"<tr><td>The entry could not be added!</td></tr>";
}

}

if($view){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"100%\"  border=\"0\"><tr><td colspan=\"9\" ><img src=\"$topImage\"></td></tr><tr><td  colspan=\"9\">&nbsp;</td></tr>";
require("menu.php");

echo "<tr><td colspan=\"9\">Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td colspan=\"9\">&nbsp;</td></tr>";


$Query1 = "SELECT * from $tablename ORDER BY surname,firstName;";
$Result = mysql_db_query ($dbname, $Query1, $link);


echo"<tr><td colspan=\"9\"><a href=\"editMembers.php?checked=true\">Click Here To Add An New Member</a></td></tr><tr><td colspan=\"9\">&nbsp;</td></tr>"; 

// Fetch Results from database.
echo"<tr class = \"backBlue\"><td>Surname</td><td>Firstname</td><td>Username</td><td>Password</td><td>Authorised</td><td>Access Level</td><td>Notes</td></tr>\n";

while ($Row = mysql_fetch_array($Result)){



if($Row[authorised] == "1"){
echo"<tr class = \"line2\"><td>$Row[surname]</td><td>$Row[firstname]</td><td>$Row[username]</td><td>$Row[password]</td><td>yes</td><td>$Row[accessLevel]</td><td>$Row[notes]</td></tr>\n";
}else{
echo"<tr class = \"lineRed\"><td>$Row[surname]</td><td>$Row[firstname]</td><td>$Row[username]</td><td>$Row[password]</td><td>no</td><td>$Row[accessLevel]</td><td>$Row[notes]</td></tr>\n";
}

echo"<tr><td><a href=\"editMembers.php?checked=true&edit=true&toEdit=$Row[id]\">edit</a> | <a href=\"editMembers.php?checked=true&confirmDelete=true&toDelete=$Row[id]\">delete</a></td></tr>";
echo"<tr><td colspan=\"9\">&nbsp;</td></tr>";	
}
mysql_close ($link);

print("</table><br><br>");

}

if((!$view)&&((!$beensubmitted)&&(!$confirmDelete)&&(!$Delete)&&(!$edit)&&(!$editEntry))){






echo "<html><head><title>Edit Members</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";

echo"<form action=\"editMembers.php\" name=\"theForm\" method=\"post\"  >\n";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");

echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><a href=\"editMembers.php?checked=true&view=true\">Click Here To View All Members</a></td></tr><tr><td>&nbsp;</td></tr>";

echo "<tr><td>Fill in the new members details and click submit</td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td>first name</td></tr><tr><td><input type=text name=\"newfirstName\" size=50 ></td></tr>";
echo "<tr><td>surname</td></tr><tr><td><input type=text name=\"newsurname\" size=50 ></td></tr>";
echo "<tr><td>username</td></tr><tr><td><input type=text name=\"newuserName\" size=50 ></td></tr>";
echo "<tr><td>password</td></tr><tr><td><input type=text name=\"newpword\" size=50 ></td></tr>";
echo "<tr><td>authorised</td></tr><tr><td><select name=\"newauthorised\"><option value=\"1\" selected>yes</option><option value=\"0\">no</option></select></td></tr>";
echo "<tr><td>access level</td></tr><tr><td><input type=text name=\"newaccessLevel\" size=50 ></td></tr>";
echo "<tr><td>notes</td></tr><tr><td><textarea name=\"notes\" cols=\"50\" rows=\"5\"></textarea></td></tr>";

echo"<input type=hidden name=\"beensubmitted\" value=true>\n";
echo"<input type=hidden name=\"checked\" value=TRUE>\n";
echo"<input type=hidden name=\"visitors\" value=\"1\">\n";


echo "<tr><td><a href=\"javascript:document.theForm.submit();\">submit</a> </td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.reset();\">reset</a> </td></tr>";
echo"</form>";
echo "</table></body></html>";

}else if (($view)||($confirmDelete)||($edit)){


}else{

print("<tr><td><a href=\"editMembers.php?checked=true\">Click Here To Add A New Member</a><br></td></tr>");
print("<tr><td><a href=\"editMembers.php?checked=true&view=true\">Click Here To View All Members</a><br></td></tr>");
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
 