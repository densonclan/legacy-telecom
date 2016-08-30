<?PHP
global $display;
global $n;
global $link;

print("details=");

global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;

$page = trim($page);

require("DBinfo.php");
$tablename = "pages";
$link = mysql_connect($host,$user,$password);

// gets all links

$query = "SELECT * FROM $tablename WHERE txtFile = '$page' ";
$Result = mysql_db_query($dbname, $query, $link);


while($Row = mysql_fetch_array($Result)){

print("$Row[linkTxt]*$Row[txtFile]*$Row[image]");


}
mysql_close($link1);




?>