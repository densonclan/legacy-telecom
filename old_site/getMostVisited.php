<?PHP
global $display;
global $n;
global $link;

print("pages=");

global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;

@$page = trim($page);

require("DBinfo.php");
$tablename = "pages";
$link = mysql_connect($host,$user,$password);

// gets all links

$query = "SELECT * FROM $tablename WHERE txtFile like 'txtFiles%' AND restricted !='1' ORDER BY visits DESC,linkTxt  LIMIT 10; ";
$Result = mysql_db_query($dbname, $query, $link);

 
while($Row = mysql_fetch_array($Result)){

print("$Row[id]*$Row[linkTxt]*$Row[description]*$Row[visits]*$Row[thumbnail]*$Row[txtFile]^");


}
@mysql_close($link1);




?>