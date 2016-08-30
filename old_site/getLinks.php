<?PHP
global $display;
global $n;
global $link;

print("links=");

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

$query = "SELECT * FROM $tablename WHERE name != '' AND restricted ='0' ORDER by listNum";

$Result = mysql_db_query($dbname, $query, $link);


while($Row = mysql_fetch_array($Result)){

		if($Row[image] != ""){
				$allImgs = explode("^",$Row[image]);
			$imageAlt = "";

			for($i=0;$i<count($allImgs);$i++){
			
				//gets info
				
				$file_path = $allImgs[$i];
				$size = getimagesize("$file_path", $info);
						
					 if(is_array($info)) {
					
					   $iptc = iptcparse($info["APP13"]);
						if(isset($iptc)){
					  
							   $alt = $iptc["2#120"][0];
							   
							   $imageAlt .="$allImgs[$i]<$alt^";
							   
						}
						 
					  } 
							   
			}

		}
		$imageAltLen = strlen($imageAlt) - 1;
		$imageAlt = substr($imageAlt,0,$imageAltLen);

		print("$Row[linkTxt]*$Row[name]*$Row[txtFile]*$imageAlt*$Row[thumbnail]*");
		
		
		$query1 = "SELECT * FROM $tablename WHERE linkTo ='$Row[name]' AND restricted ='0' ORDER by listNum";
		$Result1 = mysql_db_query($dbname, $query1, $link);
		
		while($Row1 = mysql_fetch_array($Result1)){
					
					if($Row1[image] != ""){
					$allImgs1 = explode("^",$Row1[image]);
					$imageAlt1 = "";
	
						for($i=0;$i<count($allImgs1);$i++){
						
							//gets info
							
							$file_path = $allImgs1[$i];
							$size = getimagesize("$file_path", $info);
									
								 if(is_array($info)) {
								
								   $iptc = iptcparse($info["APP13"]);
									if(isset($iptc)){
								  
										   $alt = $iptc["2#120"][0];
										   
										   $imageAlt1 .="$allImgs1[$i]<$alt^";
										   
									}
									 
								  } 
										   
						}
			
					}
					
				$imageAltLen = strlen($imageAlt1) - 1;
				$imageAlt1 = substr($imageAlt1,0,$imageAltLen);
				
				print("$Row1[linkTxt]~$Row1[name]~$Row1[txtFile]~$imageAlt1~$Row1[thumbnail]|");
		
		}
		
		echo ";";
}
@mysql_close($link);



?>
/