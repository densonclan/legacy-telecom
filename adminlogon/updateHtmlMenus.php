<?PHP



/***********************
GET LATEST MENUS
************************/
$host = "mysql3.amenworld.com:3306";
$user = "my50060";
$password = "unzc0pbu";
$dbname = "my50060";
$tablename = "pages";
$link = mysql_connect($host,$user,$password);

global $newLinks;
$newLinks = "";
// gets all links

$query = "SELECT * FROM $tablename WHERE name != '' AND restricted ='0' ORDER by listNum";

$Result = mysql_db_query($dbname, $query, $link);

while($Row = mysql_fetch_array($Result)){

$checkText = substr($Row[txtFile],0,3);

if($checkText == "txt"){

$newLinks .= "<br><a href=\"$Row[html]\">$Row[linkTxt]</a><br>\n";

}else if($checkText == "LIN"){

$pattern = "LINK!";
$replace = "";
$thisLink = eregi_replace($pattern,$replace,$Row[txtFile]);

$linkSub = substr($thisLink,0,5);

if($thisLink == "asfunction:_root.faq.toptenFAQ"){

$newLinks .= "<br><a href=\"frequently-asked-questions.htm\">$Row[linkTxt]</a><br>\n";

}elseif($linkSub == "asfun"){

$newLinks .= "<br><a href=\"visit-flash-site.htm\">$Row[linkTxt]</a><br>\n";

}else{

$newLinks .= "<br><a href=\"$thisLink\">$Row[linkTxt]</a><br>\n";

}


}else{

$newLinks .= "<br>$Row[linkTxt]<br>\n";

}


$query1 = "SELECT * FROM $tablename WHERE linkTo ='$Row[name]' AND restricted ='0' ORDER by listNum";
$Result1 = mysql_db_query($dbname, $query1, $link);

while($Row1 = mysql_fetch_array($Result1)){

$checkText1 = substr($Row1[txtFile],0,3);

if($checkText1 == "txt"){

$newLinks .= "<li><a href=\"$Row1[html]\">$Row1[linkTxt]</a></li><br>\n";

}else if($checkText1 == "LIN"){

$pattern = "LINK!";
$replace = "";
$thisLink = eregi_replace($pattern,$replace,$Row1[txtFile]);

$linkSub = substr($thisLink,0,5);

if($thisLink == "asfunction:_root.faq.toptenFAQ"){

$newLinks .= "<li><a href=\"frequently-asked-questions.htm\">$Row1[linkTxt]</a></li><br>\n";

}elseif($linkSub == "asfun"){

$newLinks .= "<li><a href=\"visit-flash-site.htm\">$Row1[linkTxt]</a></li><br>\n";

}else{

$newLinks .= "<li><a href=\"$thisLink\">$Row1[linkTxt]</a></li><br>\n";

}

}

}

}

/**************************
End get links
***************************/




$open =opendir("../html");

	while($files = readdir($open)){

	$filename = "../html/".$files;
		if(is_file($filename)){
	
		$extension = explode(".",$filename);
		$num = count($extension) - 1;
		$ext = $extension[$num];

			if(($ext == "htm")||($ext == "html")){
			
			$toOpen = $filename;

			$openHtml = fopen($toOpen,"r");
				if($openHtml){
						$text = file($toOpen);
						
						fclose($openHtml);
				}
				
				
				$html_old = implode("<newline>",$text);
	
				$pattern = "(<m>)+(.)+(</m>)";
				$replace = "<m>$newLinks</m>";
				$html_new = eregi_replace($pattern,$replace,$html_old);
				
				$pattern = "\n";
				$replace = "";
				$html_new = eregi_replace($pattern,$replace,$html_new);
				
				$pattern = "<newline>";
				$replace = "\n";
				$html_new = eregi_replace($pattern,$replace,$html_new);
				
				
				$toOpen = $filename;

				$openHtml = fopen($toOpen,"w+");
				if($openHtml){
			
						if(fwrite($openHtml,"$html_new")){
							
							if($toggle != "off"){
							echo "<tr><td><i>The menu on $files has been successfully updated</td></tr>";
							}

						}else{
						
							if($toggle != "off"){
							echo "<tr><td><i>The menu on $files could not be updated</td></tr>";
							}
						}
							
					
				
				fclose($openHtml);
				}
				
				

			}

		}

	}
	






?>

