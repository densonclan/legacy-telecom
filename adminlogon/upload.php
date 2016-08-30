<?php
global $thisUser;
global $thisPWord;
global $thisAuth;







session_start();
		
		$thisUser = "$HTTP_SESSION_VARS[userName]";
		$thisPWord = "$HTTP_SESSION_VARS[pWord]"; 
		$thisAuth = "$HTTP_SESSION_VARS[auth]";
		$thisRestricted ="$HTTP_SESSION_VARS[restricted]";
		

global $allowAccess;
if(($thisAuth == "yes")){

//check Restricted
if($thisRestricted != ""){
$pageArray = explode(",",$thisRestricted);
$total =count($pageArray);

for($i=0;$i<$total;$i++){
$thisP = $PHP_SELF;

if($pageArray[$i] == $thisP){
$allowAccess = 0;
break; // breaks out of the loop
}else{
$allowAccess = 1;
}
}
}else{
$allowAccess = 1;
}
// ends the check restricted pages loop




if($allowAccess == 1){






// START PAGE
global $topImage;
global $JPEG;
$JPEG = 0;
$topImage = "../logo.jpg";

if($beensubmitted){


echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;</td></tr>";



if($file){

$ex = explode(".",$file_name);
$num = count($ex)-1;

if($ex[$num] == "jpg"){
$JPEG = 1;
}

}


if (($JPEG == 1)&&($width != "")&&($height != "")){



if($bgcolor == ""){
$bgcolor = "0xffffff";
}else{

$pattern = "#";
$replace = "0x";
$bgcolor = eregi_replace($pattern,$replace,$bgcolor);

if(strlen($bgcolor) == 6){

$bgcolor = "0x".$bgcolor;
}

}

$bgcolor = hexdec($bgcolor);

$thisIm = getimagesize($file);
$thisWidth = $thisIm[0];
$thisHeight = $thisIm[1];
$thisFile = $thisIm[2];


/* IMAGES
IMAGES
IMAGES
IMAGES*/  


function iptc_maketag($rec,$dat,$val){
         $len = strlen($val);
         if ($len < 0x8000){
                 return chr(0x1c).chr($rec).chr($dat).
                 chr($len >> 8).
                 chr($len & 0xff).
                 $val;
        }else{
                 return chr(0x1c).chr($rec).chr($dat).
                 chr(0x80).chr(0x04).
                 chr(($len >> 24) & 0xff).
                 chr(($len >> 16) & 0xff).
                 chr(($len >> 8 ) & 0xff).
                 chr(($len ) & 0xff).
                 $val;
		}
}
	 
function image_createThumb($src,$dest,$maxWidth,$maxHeight,$quality,$fullWidth,$fullHeight,$backCol) { 
    if (file_exists($src)  && isset($dest)) { 
        // path info 
        $destInfo  = pathInfo($dest); 
     
        // image src size 
        $srcSize   = getImageSize($src,$info); 
		
		if(is_array($info)) {
		$iptc_new = "";
       $iptc_old = iptcparse($info["APP13"]);
       foreach (array_keys($iptc_old) as $s) {              
            $tag = str_replace("2#", "", $s);
			
     	// Creating the string
    	 $iptc_new .= iptc_maketag(2, $tag, $iptc_old[$s][0]);
       } 
	  
	               
   		} 
		
		
 
		
		

       // image dest size $destSize[0] = width, $destSize[1] = height 
        $srcRatio  = $srcSize[0]/$srcSize[1]; // width/height ratio 
        $destRatio = $maxWidth/$maxHeight; 
        if ($destRatio > $srcRatio) { 
            $destSize[1] = $maxHeight; 
            $destSize[0] = $maxHeight*$srcRatio; 
        } 
        else { 
            $destSize[0] = $maxWidth; 
            $destSize[1] = $maxWidth/$srcRatio; 
        } 
        
		$offsetW = ($fullWidth - $destSize[0])/2;
		$offsetH = ($fullHeight - $destSize[1])/2;
        // path rectification 
        if ($destInfo['extension'] == "gif") { 
            $dest = substr_replace($dest, 'jpg', -3); 
        } 
        
        // true color image,
        $destImage = imageCreateTrueColor($fullWidth,$fullHeight); 
        imagefill($destImage,0,0,$backCol);
        // src image 
        switch ($srcSize[2]) { 
            case 1: //GIF 
            $srcImage = imageCreateFromGif($src); 
            break; 
            
            case 2: //JPEG 
            $srcImage = imageCreateFromJpeg($src); 
            break; 
            
            case 3: //PNG 
            $srcImage = imageCreateFromPng($src); 
            break; 
            
            default: 
            return false; 
            break; 
        } 
        
        // resampling 
        imagecopyresampled($destImage, $srcImage, $offsetW, $offsetH, 0, 0,$destSize[0],$destSize[1],$srcSize[0],$srcSize[1]); 
		
		
		
        
        // generating image 
        switch ($srcSize[2]) { 
            case 1: 
            case 2: 
            imageJpeg($destImage,$dest,$quality); 
			$mode = 0;
			$content = iptcembed($iptc_new, $dest, $mode);

			// writes the new file
			$fp = fopen($dest, "w");
			fwrite($fp, $content);
			fclose($fp);
			
            break; 
            
            case 3: 
            imagePng($destImage,$dest); 
			
			$mode = 0;
			$content = iptcembed($iptc_new, $dest, $mode);

			// writes the new file
			$fp = fopen($dest, "w");
			fwrite($fp, $content);
			fclose($fp);
			
            break; 
        } 
		
        return true; 
    } 
    else { 
        return false; 
    } 
} 





/* IMAGES
IMAGES
IMAGES
IMAGES*/





//check for JPEG only images;
if($thisFile == 2){

	//check to see if the file already exists
	$imageDir = "../uploads/".$file_name;
	@$open = fopen($imageDir,"r");
	if($open){
	fclose($open);
	$newName = md5($file_name);
	$begin = rand(0,22);
	$tenDigitName = substr($newName,$begin,10);
	
	$newFile = $tenDigitName.$file_name;
	}else{
	
	$newFile = $file_name;
	}
	

	
	if(image_createThumb($file,"../uploads/".$newFile,$width,$height,100,$width,$height,$bgcolor)){
		echo "<tr><td colspan=\"3\">the image ".$file_name." has been sucessfully uploaded<td><tr>\n";
		$thisName = "uploads/".$newFile;
		
		echo "<tr><td>$file_name has been successfully uploaded and resampled</td></tr><tr><td>&nbsp;</td></tr>";
		echo "<tr><td>The URL to the file is :-<br> <a href=\"http://".$_SERVER["HTTP_HOST"]."/SP/$thisName\"><b>http://".$_SERVER["HTTP_HOST"]."/SP/$thisName</b></a></td></tr><tr><td>&nbsp;</td></tr>";
		
		
				if($txtFile != "NONE"){
				$URLlink ="<br><br><img src=\"http://".$_SERVER["HTTP_HOST"]."/SP/uploads/$newFile\" width=\"$width\" height=\"$height\" align=\"left\" class=\"imgMargin\">";
				
				$brNum = (($height - 16)/14);
				
				
				for($i=0;$i<$brNum;$i++){
				
					$URLlink .="<br>";
					
				}
				
				 
				
		
			// append File
		
					$toOpen = "../".$txtFile;
					$open = fopen($toOpen,"a");
						if($open){
				
				
						fwrite($open,"$URLlink");
				
						echo "<tr><td>The link has been successfully added.</td></tr><tr><td>&nbsp;</td></tr>";
						
						// remove all othe images and modify html Page
						
								require("../DBinfo.php");
								$tablename = "pages";
								$link = mysql_connect($host,$user,$password);
								
								// gets all images and deletes them
								
								$query= "SELECT * FROM $tablename WHERE txtFile='$txtFile';";
								
								$result = mysql_db_query($dbname, $query, $link);
								while($row = mysql_fetch_array($result)){
								
										$linkTxt = $row[linkTxt];
										$id = $row[id];
								
										$images = $row[image];
										$allImages = explode("^",$images);
										$total = count($allImages);
										
										for($i=0;$i<$total;$i++){
										
										$imageToDelete = "../".$allImages[$i];
										$thisT = explode("/",$allImages[$i]);
										$thumbToDelete = "../images/t".$thisT[1];
										
											if(unlink($imageToDelete)){
											
											
											echo "<tr><td>$imageToDelete has been removed</td></tr>";
											}
											if(unlink($thumbToDelete)){
											
											
											echo "<tr><td>$thumbToDelete has been removed</td></tr>";
											}
										
										}
								
								
								}
								
								$query2 = "UPDATE $tablename SET image='' WHERE txtFile='$txtFile';";
									
								if(mysql_db_query($dbname,$query2,$link)){
								echo "<tr><td>The database has been updated</td></tr>";
								
								}else{
								echo "<tr><td>The database could not be updated</td></tr>";
								
								}
							
								@mysql_close($link);
								
								$recreateHTML = true;
							
								
								/************************************************
								*************************************************
								Updates the html PAGE
						
								*************************************************
								*************************************************/
								//start
								
								
								if(include("htmlTemplate.php")){
								
								updateMenus();
								}
								
								
								//end
								
								
								/***************************************************
								****************************************************
								
								End recreating html PAGE
								
								****************************************************
								****************************************************/

						
						
						fclose($open);
						}else{
						echo "<tr><td>The link could not be added.</td></tr><tr><td>&nbsp;</td></tr>";
						}
					
				}
		
		
		}else{
		echo"<tr><td colspan=\"3\">the image ".$file_name." could not be resampled<td><tr>\n";
		
		}
		
	
	
	unlink ($file);
}





}else if(copy($file,"../uploads/$file_name")){
 
echo "<tr><td>$file_name has been successfully uploaded</td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td>The URL to the file is :-<br> <a href=\"http://".$_SERVER["HTTP_HOST"]."/SP/$thisName\"><b>http://".$_SERVER["HTTP_HOST"]."/SP/$thisName</b></a></td></tr><tr><td>&nbsp;</td></tr>";

	if($txtFile != "NONE"){
	$URLlink ="<br><a href=\"http://".$_SERVER["HTTP_HOST"]."/SP/uploads/$file_name\" target=\"_blank\">$linkTxt</a>";


			// append File
			
			$toOpen = "../".$txtFile;
			$open = fopen($toOpen,"a");
			if(open){
			
			
			fwrite($open,"$URLlink");
			
			echo "<tr><td>The link has been successfully added.</td></tr><tr><td>&nbsp;</td></tr>";
			
			fclose($open);
			}else{
			echo "<tr><td>The link could not be added.</td></tr><tr><td>&nbsp;</td></tr>";
			}
			
	}
 
 }else{
 
 echo "<tr><td>$file_name could not be uploaded</td></tr><tr><td>&nbsp;</td></tr>";
 
 }
 
 
 
echo "</table></body></html>";

}else{

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";

echo"<form name=\"theForm\" action=\"upload.php\" enctype=\"multipart/form-data\" method=\"post\">";

echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><i>Please select the file you want to upload (max file size 2mb)</i></td></tr><tr><td>&nbsp;</td></tr>";


//enter image
echo "<tr><td>Upload File<br><input type=file name=\"file\" size=20></td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>\n";

//choose page
echo "<tr><td>Please select the page you want to add the link to (OPTIONAL) :-</td></tr>";
echo "<tr><td><select name=\"txtFile\"><option value=\"NONE\" selected>No Link</option>";

require("../DBinfo.php");

$tablename = "pages";
$link = mysql_connect($host,$user,$password);

// gets all images and deletes them

$query= "SELECT txtFile,linkTxt FROM $tablename WHERE txtFile LIKE 'txtFiles%';";

$result = mysql_db_query($dbname, $query, $link);
while($row = mysql_fetch_array($result)){

echo "<option value=\"$row[txtFile]\">$row[linkTxt]</option>";

}
@mysql_close($link);

echo "</select></td><tr><tr><td>&nbsp;</td></tr>";

echo "<tr><td>Enter the link text (NON IMAGES ONLY)<br><input type=text name=\"linkTxt\" size=20></td></tr><tr><td>&nbsp;</td></tr>\n";
echo "<tr><td>If you are uploading a JPEG image, and want to resize it enter the new width and height (in pixels)in the boxes below</td></tr>\n";
echo "<tr><td>&nbsp;</td></tr>\n";
echo "<tr><td>width<br><input type=\"text\" name=\"width\" size=20></td></tr>\n";
echo "<tr><td>height<br><input type=\"text\" name=\"height\" size=20></td></tr>\n";
echo "<tr><td>background colour<br>#<input type=\"text\" name=\"bgcolor\" size=20></td></tr>\n";


echo "<input type=hidden name=\"beensubmitted\" value=true>\n";
echo "<tr><td><a href=\"javascript:document.theForm.submit();\">submit</a> </td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.reset();\">reset</a> </td></tr>";

echo "</form>";

echo "</table></body></html>";

}





// END PAGE


}else{
// if this page is in the users restricted list
echo "access denied";

}
// if they have not logged on
}else{
header("Location: http://www.lan.co.uk/unauthorised.htm");
}
	

?>
 