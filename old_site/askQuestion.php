<?PHP
global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;


$question = trim($question);




require("DBinfo.php");
$tablename = "FAQ";
$link = mysql_connect($host,$user,$password);

$question = trim($question);

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

$query = "SELECT id,question,answer,MATCH(question,answer) AGAINST ('$question') AS score FROM $tablename WHERE MATCH(question,answer) AGAINST ('$question');";

$result = mysql_db_query ($dbname, $query, $link);


print("answer=");
while($row = mysql_fetch_array($result)){


if($row[score] > 1){

print("$row[question]*$row[id]|");

}

}

mysql_close($link);







?>
