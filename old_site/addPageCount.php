<?PHP

$txtPage = trim($txtPage);

print("count=");

global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;

require("DBinfo.php");
$tablename = "pages";
$link = mysql_connect($host,$user,$password);

// gets all links

$query = "UPDATE $tablename set visits =visits+1 WHERE txtFile = '$txtPage' ";
$query1 = "SELECT visits FROM $tablename WHERE txtFile = '$txtPage' ";

if(( mysql_db_query($dbname, $query, $link)) && ( $result = mysql_db_query($dbname, $query1, $link))){

while($row = mysql_fetch_array($result)){

echo $row[visits];

}


}else{
echo"fail";
}
@mysql_close($link1);




?>