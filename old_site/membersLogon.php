<?php

$uname = trim($uname);
$pword = trim($pword);

global $n;
$n = 0;

//check user name and password in db

require_once("DBinfo.php");

$tablename = "members";
$link = mysql_connect($host,$user,$password);

$query = "SELECT * FROM $tablename WHERE username ='$uname' and password ='$pword'";

$result = mysql_db_query($dbname,$query,$link);


while($row = @mysql_fetch_array($result)){
$n++;
$level = $row[accessLevel];
$memberFirstname = $row[firstname];
$memberSurname = $row[surname];
$memberAuthorised = $row[authorised];
}
@mysql_close($link);

if(($n > 0)&&($memberAuthorised == 1)){

echo "success=1&level=$level&memberFirstname=$memberFirstname&memberSurname=$memberSurname";

}else{

echo"success=0";

}

?>