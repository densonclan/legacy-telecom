<?PHP
global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;


require("DBinfo.php");
$tablename = "pages";
$tablename1 = "FAQ";
$link = mysql_connect($host,$user,$password);

$search = trim($search);
$level = trim($level);


$pattern = "\&";
$replace = "and";
$search = eregi_replace($pattern,$replace,$search);


$pattern = "\'";
$replace = "";
$search = eregi_replace($pattern,$replace,$search);

$pattern = "\?";
$replace = "";
$search = eregi_replace($pattern,$replace,$search);

$testStr = explode(" ",$search);

if(count($testStr) > 1){

$query = "SELECT *,MATCH(linkTxt,description,keywords) AGAINST ('$search' ) AS score FROM $tablename WHERE MATCH(linkTxt,description,keywords) AGAINST ('$search') AND txtFile like 'txtFiles%' AND restricted <='$level' ;";


}else{


$query = "SELECT *,MATCH(linkTxt,description,keywords) AGAINST ('+$search*' IN BOOLEAN MODE) AS score FROM $tablename WHERE MATCH(linkTxt,description,keywords) AGAINST ('+$search*' IN BOOLEAN MODE) AND txtFile like 'txtFiles%' AND restricted <='$level';";

}

$result = mysql_db_query ($dbname, $query, $link);


print("pages=");

while($row = mysql_fetch_array($result)){


//if($row[score] > 1){

print("$row[id]*$row[linkTxt]*$row[description]*$row[visits]*$row[thumbnail]*$row[txtFile]*$row[score]^");

//}

}

//search faq


$query = "SELECT id,question,answer,MATCH(question,answer) AGAINST ('$search') AS score FROM $tablename1 WHERE MATCH(question,answer) AGAINST ('$search');";

$result = mysql_db_query ($dbname, $query, $link);

echo "&faq=";
while($row = mysql_fetch_array($result)){


//if($row[score] > 1){

print("$row[question]*$row[id]*$row[score]^");

//}

}


mysql_close($link);







?>
