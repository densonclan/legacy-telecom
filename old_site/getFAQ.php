<?PHP
global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;
global $n;


$id=trim($id);



require("DBinfo.php");
$tablename = "FAQ";
$link = mysql_connect($host,$user,$password);


$query = "SELECT * FROM $tablename WHERE id = '$id'";

$result = mysql_db_query ($dbname, $query, $link);
while($row = mysql_fetch_array($result)){
print("question=$row[question]&answer=$row[answer]&visitors=$row[visitors]");


}

mysql_close($link);
require("DBinfo.php");
$tablename = "FAQ";
$link = mysql_connect($host,$user,$password);
$query = "UPDATE $tablename SET visitors = visitors + 1 WHERE id ='$id'";

if(mysql_db_query ($dbname, $query, $link)){
print("&success=1");
}else{
print("&success=0");
} 


?>
</body>
</html>