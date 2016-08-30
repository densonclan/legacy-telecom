<?PHP
global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;
global $n;
global $firstRes;
global $secondRes;




require("DBinfo.php");
$tablename = "FAQ";
$link = mysql_connect($host,$user,$password);


$query = "SELECT * FROM $tablename ORDER BY visitors DESC LIMIT 10;";

$result = mysql_db_query ($dbname, $query, $link);

$n = 1;
print("reply=");

while($row = mysql_fetch_array($result)){
if($n != 10){
print("$n.   <u><a href='asfunction:_root.faq.getTech,$row[id]'>$row[question]</a>?</u><br>");
}else{
print("$n. <u><a href='asfunction:_root.faq.getTech,$row[id]'>$row[question]</a>?</u><br>");

}
$n++;
}

mysql_close($link);

echo "&end=1";


?>