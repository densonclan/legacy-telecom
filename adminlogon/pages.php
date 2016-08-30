<?php
global $thumbnailWidth;
global $thumbnailHeight;
global $mainImageWidth;
global $mainImageHeight;
global $largeImageWidth;
global $largeImageHeight;
global $topImage;
$topImage = "../logo.jpg";


$thumbnailWidth = 25;
$thumbnailHeight = 12;

$mainImageWidth = 200;
$mainImageHeight = 200;

$largeImageWidth = 500;
$largeImageHeight = 500;


require_once("../header.php");
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



if($selectChangeImage){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";

echo"<form name=\"theForm\" action=\"pages.php\" enctype=\"multipart/form-data\" method=\"post\">";

echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><i>Please select the new Image:- </i></td></tr><td>&nbsp;</td></tr>";
echo "<tr><td ><input type=file name=\"file\" size=20></td></tr>\n";
  echo "<tr><td align=\"center\">&nbsp;</td></tr>\n";
  echo "<input type=hidden name=\"changeImage\" value=\"true\">\n";
 echo "<input type=hidden name=\"id\" value=\"$id\">\n"; 
 echo "<input type=hidden name=\"img\" value=\"$img\">\n"; 
 echo "<tr><td><a href=\"javascript:document.theForm.submit();\">submit new image</a> | <a href=\"pages.php\">cancel</a></td></tr>";
 
echo "</form>";

echo "</table></body></html>";


}else if($changeImage){


echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";

$id = trim($id);
$img = trim($img);
global $addedImage;




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



//checks the files


if ($file){


$thisIm = getimagesize($file);
$thisWidth = $thisIm[0];
$thisHeight = $thisIm[1];
$thisFile = $thisIm[2];

//check for JPEG only images;
if($thisFile == 2){

	//check to see if the file already exists
	$imageDir = "../images/".$file_name;
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
	
	
	if(image_createThumb($file,"../images/".$newFile,$mainImageWidth,$mainImageHeight,100,$mainImageWidth,$mainImageHeight,0xffffff)){
		echo "<tr><td colspan=\"3\">the image ".$file_name." has been sucessfully uploaded<td><tr>\n";
		$thisName = "images/".$newFile;
		$addedImage = $thisName;
		
		
		}else{
		echo"<tr><td colspan=\"3\">the image ".$file_name." could not be resampled<td><tr>\n";
		
		}
		
	if(image_createThumb($file,"../images/t".$newFile,$thumbnailWidth,$thumbnailHeight,100,$thumbnailWidth,$thumbnailHeight,0xffffff)){
		echo "<tr><td colspan=\"3\">the thumbnail ".$file_name." has been sucessfully created<td><tr>\n";
		

		}else{
		echo"<tr><td colspan=\"3\">the thumbnail ".$file_name." could not be created<td><tr>\n";
		
		}
		
	if(image_createThumb($file,"../images/L".$newFile,$largeImageWidth,$largeImageHeight,100,$largeImageWidth,$largeImageHeight,0xffffff)){
		echo "<tr><td colspan=\"3\">the enlarged ".$file_name." has been sucessfully created<td><tr>\n";
		

		}else{
		echo"<tr><td colspan=\"3\">the enlarged ".$file_name." could not be created<td><tr>\n";
		
		}
	
	unlink ($file);
}
}else{

$addedImage = "";

}


/* IMAGES
IMAGES
IMAGES
IMAGES*/



global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;
global $linkTxt;
global $htmlText;
global $thisTxtFile;
global $newImage;
global $newThumb;
require("../DBinfo.php");

$tablename = "pages";
$link = mysql_connect($host,$user,$password);

// gets all images and deletes them

$query= "SELECT * From $tablename WHERE id = '$id';";

$result = mysql_db_query($dbname, $query, $link);
while($row = mysql_fetch_array($result)){

$images = $row[image];
$allImages = explode("^",$images);
$total = count($allImages);

for($i=0;$i<$total;$i++){



		if($i==$img){
		
					$imageToDelete = "../".$allImages[$i];
					$thisT = explode("/",$allImages[$i]);
					$thumbToDelete = "../images/t".$thisT[1];
					$largeToDelete = "../images/L".$thisT[1];
					
					if(unlink($imageToDelete)){
					
					
					echo "<tr><td>$imageToDelete has been removed</td></tr>";
					}
					if(unlink($thumbToDelete)){
					
					
					echo "<tr><td>$thumbToDelete has been removed</td></tr>";
					}
					if(unlink($largeToDelete)){
					
					
					echo "<tr><td>$largeToDelete has been removed</td></tr>";
					}
					if($addedImage != ""){
					$newImage[] = $addedImage;
					$thisT = explode("/",$addedImage);
					$newThumb = "images/t".$thisT[1];
					}
					
		
		
		
		}else{
		
		$newImage[] = $allImages[$i];
		$thisT = explode("/",$allImages[$i]);
		$newThumb = "images/t".$thisT[1];
		
		}




}

$thisTxtFile = $row[txtFile];
$linkTxt = $row[linkTxt];

}

$newImageStr = implode("^",$newImage);


$Query1 = "UPDATE $tablename Set image = '$newImageStr',thumbnail = '$newThumb' WHERE id = '$id';";


if (mysql_db_query ($dbname, $Query1, $link)){
	echo "<tr><td>The image was successfully cleared</td></tr><td>&nbsp;</td></tr>";
} else {
	echo "<tr><td>The image could not cleared</td></tr><td>&nbsp;</td></tr>";
}


//get txt to update HTML page

$toOpen = "../".$thisTxtFile;

$open = fopen($toOpen,"r");
if(open){

$text = file($toOpen);

fclose($open);
}

$htmlText = "";

for($n=0; $n<count($text); $n++){


$pattern = "ï»¿txt=";
$replace = "";
$text[$n] = eregi_replace($pattern,$replace,$text[$n]);

$pattern = "txt=";
$replace = "";
$text[$n] = eregi_replace($pattern,$replace,$text[$n]);





$htmlText .= $text[$n];

}

$imageName = $newImageStr;

/************************************************
*************************************************
Updates the html PAGE
Recreate the index.html

*************************************************
*************************************************/
//start


if(include("htmlTemplate.php")){

createIndex();
}

//end


/***************************************************
****************************************************

End recreating Index

****************************************************
****************************************************/






mysql_close ($link);

echo "<tr><td>&nbsp;</td></tr><tr><td><a href=\"pages.php\">click here to return to the main menu</a></td></tr>";

echo "</table></body></html>";







}elseif($removeImage){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";


$id = trim($id);
$img = trim($img);

global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;
global $linkTxt;
global $htmlText;
global $thisTxtFile;
global $newImage;
global $newThumb;
require("../DBinfo.php");

$tablename = "pages";
$link = mysql_connect($host,$user,$password);

// gets all images and deletes them

$query= "SELECT * From $tablename WHERE id = '$id';";

$result = mysql_db_query($dbname, $query, $link);
while($row = mysql_fetch_array($result)){

$images = $row[image];
$allImages = explode("^",$images);
$total = count($allImages);

for($i=0;$i<$total;$i++){



		if($i==$img){
		
					$imageToDelete = "../".$allImages[$i];
					$thisT = explode("/",$allImages[$i]);
					$thumbToDelete = "../images/t".$thisT[1];
					$largeToDelete = "../images/L".$thisT[1];
					
					if(unlink($imageToDelete)){
					
					
					echo "<tr><td>$imageToDelete has been removed</td></tr>";
					}
					if(unlink($thumbToDelete)){
					
					
					echo "<tr><td>$thumbToDelete has been removed</td></tr>";
					}
					if(unlink($largeToDelete)){
					
					
					echo "<tr><td>$largeToDelete has been removed</td></tr>";
					}
		
		
		
		}else{
		
		$newImage[] = $allImages[$i];
		$thisT = explode("/",$allImages[$i]);
		$newThumb = "images/t".$thisT[1];
		
		}




}

$thisTxtFile = $row[txtFile];
$linkTxt = $row[linkTxt];

}

$newImageStr = implode("^",$newImage);


$Query1 = "UPDATE $tablename Set image = '$newImageStr',thumbnail = '$newThumb' WHERE id = '$id';";


if (mysql_db_query ($dbname, $Query1, $link)){
	echo "<tr><td>The image was successfully cleared</td></tr><td>&nbsp;</td></tr>";
} else {
	echo "<tr><td>The image could not cleared</td></tr><td>&nbsp;</td></tr>";
}


//get txt to update HTML page

$toOpen = "../".$thisTxtFile;

$open = fopen($toOpen,"r");
if(open){

$text = file($toOpen);

fclose($open);
}

$htmlText = "";

for($n=0; $n<count($text); $n++){


$pattern = "ï»¿txt=";
$replace = "";
$text[$n] = eregi_replace($pattern,$replace,$text[$n]);

$pattern = "txt=";
$replace = "";
$text[$n] = eregi_replace($pattern,$replace,$text[$n]);





$htmlText .= $text[$n];

}

$imageName = $newImageStr;

/************************************************
*************************************************
Updates the html PAGE
Recreate the index.html

*************************************************
*************************************************/
//start


if(include("htmlTemplate.php")){

createIndex();
}

//end


/***************************************************
****************************************************

End recreating Index

****************************************************
****************************************************/






mysql_close ($link);

echo "<tr><td>&nbsp;</td></tr><tr><td><a href=\"pages.php\">click here to return to the main menu</a></td></tr>";

echo "</table></body></html>";




}else if($moveSectionDown){
global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;
require("../DBinfo.php");

$tablename = "pages";
$link = mysql_connect($host,$user,$password);
$link2 = mysql_connect($host,$user,$password);
$link3 = mysql_connect($host,$user,$password);

$id = trim($id);

//get listNum
$query = "SELECT listNum FROM $tablename WHERE id='$id';";

$result = mysql_db_query($dbname,$query,$link);
$list = mysql_fetch_array($result);
$listNum = $list[listNum];


//get next Highest Number;
$query1 = "SELECT listNum AS changeTo FROM $tablename WHERE listNum > '$listNum' ORDER BY listNum LIMIT 1";
$result = mysql_db_query($dbname,$query1,$link);
$list = mysql_fetch_array($result);
$changeTo = $list[changeTo];




$query2 = "UPDATE $tablename SET listNum='$listNum' WHERE listNum='$changeTo' AND linkTo =''";
mysql_db_query($dbname,$query2,$link); 

$query3 = "UPDATE $tablename SET listNum='$changeTo' WHERE id='$id';";
mysql_db_query($dbname,$query3,$link);

@mysql_close($link);

global $existing;
$existing = true;

/************************************************
*************************************************

Recreate the index.html

*************************************************
*************************************************/
//start



if(include("htmlTemplate.php")){


createIndex("off");
}

//end


/***************************************************
****************************************************

End recreating Index

****************************************************
****************************************************/



}elseif($moveSectionUp){
global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;
require("../DBinfo.php");

$tablename = "pages";
$link = mysql_connect($host,$user,$password);
$link2 = mysql_connect($host,$user,$password);
$link3 = mysql_connect($host,$user,$password);

$id = trim($id);

//get listNum
$query = "SELECT listNum FROM $tablename WHERE id='$id';";

$result = mysql_db_query($dbname,$query,$link);
$list = mysql_fetch_array($result);
$listNum = $list[listNum];

//get next Lowest Number;
$query1 = "SELECT listNum AS changeTo FROM $tablename WHERE listNum < '$listNum' ORDER BY listNum DESC LIMIT 1";
$result = mysql_db_query($dbname,$query1,$link);
$list = mysql_fetch_array($result);
$changeTo = $list[changeTo];


$query2 = "UPDATE $tablename SET listNum='$listNum' WHERE listNum='$changeTo' AND linkTo =''";
mysql_db_query($dbname,$query2,$link); 

$query3 = "UPDATE $tablename SET listNum='$changeTo' WHERE id='$id';";
mysql_db_query($dbname,$query3,$link);

@mysql_close($link);

global $existing;
$existing = true;

/************************************************
*************************************************

Recreate the index.html

*************************************************
*************************************************/
//start


if(include("htmlTemplate.php")){


createIndex("off");
}


//end


/***************************************************
****************************************************

End recreating Index

****************************************************
****************************************************/


}else if($movePageDown){
global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;
require("../DBinfo.php");

$tablename = "pages";
$link = mysql_connect($host,$user,$password);
$link2 = mysql_connect($host,$user,$password);
$link3 = mysql_connect($host,$user,$password);

$id = trim($id);

//get listNum
$query = "SELECT listNum,linkTo FROM $tablename WHERE id='$id';";

$result = mysql_db_query($dbname,$query,$link);
$list = mysql_fetch_array($result);
$listNum = $list[listNum];
$linkTo = $list[linkTo];

//get next Highest Number;
$query1 = "SELECT listNum AS changeTo FROM $tablename WHERE listNum > '$listNum' AND linkTo ='$linkTo'ORDER BY listNum LIMIT 1";
$result = mysql_db_query($dbname,$query1,$link);
$list = mysql_fetch_array($result);
$changeTo = $list[changeTo];


$query2 = "UPDATE $tablename SET listNum='$listNum' WHERE listNum='$changeTo' AND linkTo ='$linkTo'";
mysql_db_query($dbname,$query2,$link); 

$query3 = "UPDATE $tablename SET listNum='$changeTo' WHERE id='$id';";
mysql_db_query($dbname,$query3,$link);

@mysql_close($link);

global $existing;
$existing = true;

/************************************************
*************************************************

Recreate the index.html

*************************************************
*************************************************/
//start


if(include("htmlTemplate.php")){

createIndex("off");
}


//end


/***************************************************
****************************************************

End recreating Index

****************************************************
****************************************************/


}else if($movePageUp){
global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;
require("../DBinfo.php");

$tablename = "pages";
$link = mysql_connect($host,$user,$password);
$link2 = mysql_connect($host,$user,$password);
$link3 = mysql_connect($host,$user,$password);

$id = trim($id);

//get listNum
$query = "SELECT listNum,linkTo FROM $tablename WHERE id='$id';";

$result = mysql_db_query($dbname,$query,$link);
$list = mysql_fetch_array($result);
$listNum = $list[listNum];
$linkTo = $list[linkTo];

//get next Highest Number;
$query1 = "SELECT listNum AS changeTo FROM $tablename WHERE listNum < '$listNum' AND linkTo ='$linkTo'ORDER BY listNum DESC LIMIT 1";
$result = mysql_db_query($dbname,$query1,$link);
$list = mysql_fetch_array($result);
$changeTo = $list[changeTo];


$query2 = "UPDATE $tablename SET listNum='$listNum' WHERE listNum='$changeTo' AND linkTo ='$linkTo'";
mysql_db_query($dbname,$query2,$link); 

$query3 = "UPDATE $tablename SET listNum='$changeTo' WHERE id='$id';";
mysql_db_query($dbname,$query3,$link);

@mysql_close($link);

global $existing;
$existing = true;


/************************************************
*************************************************

Recreate the index.html

*************************************************
*************************************************/
//start


if(include("htmlTemplate.php")){


createIndex("off");
}


//end


/***************************************************
****************************************************

End recreating Index

****************************************************
****************************************************/




}elseif($clearImages){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";


$id = trim($id);

$linkTxt = trim($linkTxt);

global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;
global $linkTxt;
global $htmlText;
global $thisTxtFile;
require("../DBinfo.php");

$tablename = "pages";
$link = mysql_connect($host,$user,$password);

// gets all images and deletes them

$query= "SELECT * From $tablename WHERE id = '$id';";

$result = mysql_db_query($dbname, $query, $link);
while($row = mysql_fetch_array($result)){

$images = $row[image];
$allImages = explode("^",$images);
$total = count($allImages);

for($i=0;$i<$total;$i++){

					$imageToDelete = "../".$allImages[$i];
					$thisT = explode("/",$allImages[$i]);
					$thumbToDelete = "../images/t".$thisT[1];
					$largeToDelete = "../images/L".$thisT[1];
					
					if(unlink($imageToDelete)){
					
					
					echo "<tr><td>$imageToDelete has been removed</td></tr>";
					}
					if(unlink($thumbToDelete)){
					
					
					echo "<tr><td>$thumbToDelete has been removed</td></tr>";
					}
					if(unlink($largeToDelete)){
					
					
					echo "<tr><td>$largeToDelete has been removed</td></tr>";
					}




}

$thisTxtFile = $row[txtFile];
$linkTxt = $row[linkTxt];

}


$Query1 = "UPDATE $tablename Set image = '',thumbnail = '' WHERE id = '$id';";


if (mysql_db_query ($dbname, $Query1, $link)){
	echo "<tr><td>The images were successfully cleared</td></tr><td>&nbsp;</td></tr>";
} else {
	echo "<tr><td>The images could not cleared</td></tr><td>&nbsp;</td></tr>";
}


//get txt to update HTML page

$toOpen = "../".$thisTxtFile;

$open = fopen($toOpen,"r");
if(open){

$text = file($toOpen);

fclose($open);
}

$htmlText = "";

for($n=0; $n<count($text); $n++){


$pattern = "ï»¿txt=";
$replace = "";
$text[$n] = eregi_replace($pattern,$replace,$text[$n]);

$pattern = "txt=";
$replace = "";
$text[$n] = eregi_replace($pattern,$replace,$text[$n]);





$htmlText .= $text[$n];

}

$imageName = "";

/************************************************
*************************************************
Updates the html PAGE
Recreate the index.html

*************************************************
*************************************************/
//start


if(include("htmlTemplate.php")){

createIndex();
}

//end


/***************************************************
****************************************************

End recreating Index

****************************************************
****************************************************/






mysql_close ($link);

echo "<tr><td>&nbsp;</td></tr><tr><td><a href=\"pages.php\">click here to return to the main menu</a></td></tr>";

echo "</table></body></html>";

}else if($editLinkChanges){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";


$id = trim($id);
$thisLinkTxt = trim($thisLinkTxt);
$thisLink = trim($thisLink);
$thisLink = "LINK!".$thisLink;
$restrict = trim($restrict);

global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;
require("../DBinfo.php");



$tablename = "pages";
$link = mysql_connect($host,$user,$password);
$Query1 = "UPDATE $tablename Set txtFile = '$thisLink', linkTxt='$thisLinkTxt', restricted='$restrict' WHERE id = '$id';";


if (mysql_db_query ($dbname, $Query1, $link)){
	echo "<tr><td>The Link was successfully edited</td></tr><td>&nbsp;</td></tr>";
} else {
	echo "<tr><td>The Link could not edited</td></tr><td>&nbsp;</td></tr>";
}


mysql_close ($link);

echo "<tr><td>&nbsp;</td></tr><tr><td><a href=\"pages.php\">click here to return to the main menu</a></td></tr>";



/************************************************
*************************************************

Recreate the index.html

*************************************************
*************************************************/
//start


if(include("htmlTemplate.php")){

createIndex();
}


//end


/***************************************************
****************************************************

End recreating Index

****************************************************
****************************************************/

echo "</table></body></html>";


}else if($editSectionChanges){


echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";


$id = trim($id);

$linkTxt = trim($linkTxt);
$xtra = trim($xtra);
$restrict = trim($restrict);

global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;
require("../DBinfo.php");

$tablename = "pages";
$link = mysql_connect($host,$user,$password);
$Query1 = "UPDATE $tablename Set linkTxt = '$linkTxt', restricted='$restrict' WHERE id = '$id';";


if (mysql_db_query ($dbname, $Query1, $link)){
	echo "<tr><td>The section was successfully edited</td></tr><td>&nbsp;</td></tr>";
} else {
	echo "<tr><td>The section could not edited</td></tr><td>&nbsp;</td></tr>";
}


mysql_close ($link);

echo "<tr><td>&nbsp;</td></tr><tr><td><a href=\"pages.php\">click here to return to the main menu</a></td></tr>";




/************************************************
*************************************************

Recreate the index.html

*************************************************
*************************************************/
//start


if(include("htmlTemplate.php")){

createIndex();
}


//end


/***************************************************
****************************************************

End recreating Index

****************************************************
****************************************************/
echo "</table></body></html>";


}else if($editPageChanges){

global $imageName;

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";


$id = trim($id);

$toOpen = "../".$txtFile;
$open = fopen($toOpen,"w+");
if(open){

$replace = "(A)";
$pattern = "&";
$text = ereg_replace($pattern,$replace,$text);

$replace = "<br>";
$pattern = "\n";
$text = eregi_replace($pattern,$replace,$text);

$replace = "";
$pattern = "\r";
$text = eregi_replace($pattern,$replace,$text);

$replace = "'";
$pattern = "’";
$text = eregi_replace($pattern,$replace,$text);

$replace = "'";
$pattern = "‘";
$text = eregi_replace($pattern,$replace,$text);

/*$replace = "";
$pattern = "'";
$text = eregi_replace($pattern,$replace,$text);
*/

//$text = stripslashes($text);
$text = trim($text);

$htmlText = $text;

$text = utf8_encode($text);

$toWrite = "txt=".$text;
fwrite($open,"$toWrite");
fclose($open);
}

$linkTxt = trim($linkTxt);

$replace = "";
$pattern = "’";
$linkTxt  = eregi_replace($pattern,$replace,$linkTxt );

$pattern = "‘";
$replace = "";
$linkTxt  = eregi_replace($pattern,$replace,$linkTxt );

$pattern = "'";
$replace = "";
$linkTxt  = eregi_replace($pattern,$replace,$linkTxt );

$date = date("Y-m-d");

//Create Description



$replace = " ";
$pattern = "\n";
$descript = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "\t";
$descript = eregi_replace($pattern,$replace,$descript);

$replace = " ";
$pattern = "[$<][^<^>]{1,100}[$>]";
$descript = eregi_replace($pattern,$replace,$descript);

$replace = " ";
$pattern = "\r";
$descript = eregi_replace($pattern,$replace,$descript);



$replace = "and";
$pattern = "\(A\)";
$descript = ereg_replace($pattern,$replace,$descript);

$replace = "";
$pattern = "[,!@\&\$£;:\\?%\"\'<>\\/=#]";
$descript = eregi_replace($pattern,$replace,$descript);

$replace = "£";
$pattern = "[[:space:]]{1,100}";
$descript = eregi_replace($pattern,$replace,$descript);

$replace = " ";
$pattern = "£";
$descript = eregi_replace($pattern,$replace,$descript);


if(strlen($descript) > 100){
$description = substr($descript,0,100)."....";
}else{
$description = $descript;
}



//Create keywords
$text = $linkTxt.$text;

$replace = " ";
$pattern = "\n";
$text = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "\r";
$text = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "\t";
$text = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "[$<][^<^>]{1,100}[$>]";
$text = eregi_replace($pattern,$replace,$text);

$replace = "";
$pattern = "[,.!@\&\$£;:\\?%#]";
$text = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "[^a-z][a-z]{1,3}[^a-z]";
$text = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "[^a-z][a-z]{1,3}[^a-z]";
$text = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "[^a-z][a-z]{1,3}[^a-z]";
$text = eregi_replace($pattern,$replace,$text);


$replace = "and";
$pattern = "\(A\)";
$text = ereg_replace($pattern,$replace,$text);


$replace = "";
$pattern = "[,!@\&\$£;:\\?%\"\'=<>\\/]";
$text = eregi_replace($pattern,$replace,$text);

$replace = "£";
$pattern = "[[:space:]]{1,100}";
$text = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "£";
$text = eregi_replace($pattern,$replace,$text);


$keywords = $text;

// end create keywords



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



//checks the files


if ($file){

$thisIm = getimagesize($file);
$thisWidth = $thisIm[0];
$thisHeight = $thisIm[1];
$thisFile = $thisIm[2];

//check for JPEG only images;
if($thisFile == 2){

	//check to see if the file already exists
	$imageDir = "../images/".$file_name;
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
	
	
	if(image_createThumb($file,"../images/".$newFile,$mainImageWidth,$mainImageHeight,100,$mainImageWidth,$mainImageHeight,0xffffff)){
		echo "<tr><td colspan=\"3\">the image ".$file_name." has been sucessfully uploaded<td><tr>\n";
		$thisName = "images/".$newFile;
		
		if($oldImages != ""){
		$imageName = $oldImages."^".$thisName;
		}else{
		$imageName = $thisName;
		}
		
		}else{
		echo"<tr><td colspan=\"3\">the image ".$file_name." could not be resampled<td><tr>\n";
		$imageName = $oldImages;
		}
		
	if(image_createThumb($file,"../images/t".$newFile,$thumbnailWidth,$thumbnailHeight,100,$thumbnailWidth,$thumbnailHeight,0xffffff)){
		echo "<tr><td colspan=\"3\">the thumbnail ".$file_name." has been sucessfully created<td><tr>\n";
		
		
		
		$thumbnail = "images/t".$newFile;
		
		
		}else{
		echo"<tr><td colspan=\"3\">the thumbnail ".$file_name." could not be created<td><tr>\n";
		$imageName = $oldImages;
		}
		
	if(image_createThumb($file,"../images/L".$newFile,$largeImageWidth,$largeImageHeight,100,$largeImageWidth,$largeImageHeight,0xffffff)){
		echo "<tr><td colspan=\"3\">the enlarged ".$file_name." has been sucessfully created<td><tr>\n";
		

		}else{
		echo"<tr><td colspan=\"3\">the enlarged ".$file_name." could not be created<td><tr>\n";
		
		}
	
	unlink ($file);
}
}else{

$imageName = $oldImages;
$thumbnail = $oldThumb;
}


/* IMAGES
IMAGES
IMAGES
IMAGES*/

$restrict = trim($restrict);

/************************************************
*************************************************

Start create a HTML version of the page

*************************************************
*************************************************/
//start



include("htmlTemplate.php");



//end


/***************************************************
****************************************************

End create a HTML version of the page

****************************************************
****************************************************/







global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;
require("../DBinfo.php");

$tablename = "pages";
$link = mysql_connect($host,$user,$password);
$Query1 = "UPDATE $tablename Set html='$htmlPageName', txtFile = '$txtFile',linkTxt = '$linkTxt',image='$imageName', keywords='$keywords', description='$description',thumbnail='$thumbnail', date='$date', xtra='$xtra',restricted='$restrict' WHERE id = '$id';";


if (mysql_db_query ($dbname, $Query1, $link)){
	echo "<tr><td>The page was successfully edited</td></tr><td>&nbsp;</td></tr>";
} else {
	echo "<tr><td>The page could not edited</td></tr><td>&nbsp;</td></tr>";
}

@createIndex();

mysql_close ($link);

echo "<tr><td>&nbsp;</td></tr><tr><td><a href=\"pages.php\">click here to return to the main menu</a></td></tr>";

echo "</table></body></html>";

}else if($editSection){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";

echo"<form name=\"theForm\" action=\"pages.php\" method=\"post\">";

echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><i>The edit the following and click 'submit changes':-</i></td></tr><tr><td>&nbsp;</td></tr>";

// gets existing sections from database
global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;

require("../DBinfo.php");
$tablename = "pages";
$link = mysql_connect($host,$user,$password);
$query = "SELECT * FROM $tablename WHERE id= '$id'";

$result = mysql_db_query($dbname,$query,$link);

while($row = mysql_fetch_array($result)){
echo "<tr><td>Section Name(this Cannot be Edited):-<br><b>$row[name]</b></td></tr>";
echo "<tr><td>Edit the text section (this is what will be display on the home page)</td></tr><tr><td><input type=text name=\"linkTxt\" size=20 value=\"$row[linkTxt]\"></td></tr>";
echo "<tr><td>Access Level<br><input type=text name=\"restrict\" size=5 value=\"$row[restricted]\"></td></tr>\n";
}
if($result){
mysql_close($link);
}
echo "<input type=hidden name=\"editSectionChanges\" value=true>\n";

echo "<input type=hidden name=\"id\" value=\"$id\">\n";


echo "<tr><td><a href=\"javascript:document.theForm.submit();\">submit changes</a> </td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.reset();\">reset</a> </td></tr>";

echo "</form>";

echo "</table></body></html>";




}else if($editLink){



echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";

echo"<form name=\"theForm\" action=\"pages.php\" method=\"post\">";

echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><i>Edit the following link and click 'submit changes':- </i></td></tr><td>&nbsp;</td></tr>";


global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;
global $thisTxtFile;
global $thisLinkTxt;
global $thisRestrict;


require("../DBinfo.php");
$tablename = "pages";
$link = mysql_connect($host,$user,$password);
$query = "SELECT * FROM $tablename WHERE id = '$id'";

$result = mysql_db_query($dbname,$query,$link);
while($row = mysql_fetch_array($result)){

$thisTxtFile = $row[txtFile];
$thisLinkTxt = $row[linkTxt];
$thisRestrict = $row[restricted];

}
if($result){
mysql_close($link);
}

$pattern = "LINK!";
$replace = "";
$txtFile = eregi_replace($pattern,$replace,$thisTxtFile);
echo "<tr><td>Enter the new link text</td></tr><tr><td><input type=text name=\"thisLinkTxt\" size=60 value=\"$thisLinkTxt\"></td></tr>";
echo "<tr><td>Enter the new link</td></tr><tr><td><input type=text name=\"thisLink\" size=60 value=\"$txtFile\"></td></tr>";
echo "<tr><td>Access Level<br><input type=text name=\"restrict\" size=5 value=\"$thisRestrict\"></td></tr>\n";

echo "<input type=hidden name=\"editLinkChanges\" value=true>\n";
echo "<input type=hidden name=\"id\" value=\"$id\">\n";

echo "<tr><td>&nbsp;</td></tr><tr><td><a href=\"javascript:document.theForm.submit();\">submit changes</a> </td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.reset();\">reset</a> </td></tr>";

echo "</form>";

echo "</table></body></html>";



}else if ($editPage){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\">";


echo "<script language=\"javascript\">function openConfirm(returnLink,id){leftPos = (screen.width - 300)/2;topPos = (screen.height - 200)/2;window.open(\"confirm.php?returnLink=\"+returnLink+\"&id=\"+id ,\"\",\"width=300,height=200,left=\"+leftPos+\",top=\"+topPos+\",scrollbars=no,toolbar=no,location=no\");}</script>";
echo "<script language=\"javascript\">function imageEdit(returnLink,id,img){leftPos = (screen.width - 300)/2;topPos = (screen.height - 200)/2;window.open(\"imageEdit.php?returnLink=\"+returnLink+\"&id=\"+id+\"&img=\"+img ,\"\",\"width=300,height=200,left=\"+leftPos+\",top=\"+topPos+\",scrollbars=no,toolbar=no,location=no\");}</script>";

echo"</head><body>";

echo"<form name=\"theForm\" action=\"pages.php\"  enctype=\"multipart/form-data\" method=\"post\">";

echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><i>Edit the following fields and click 'submit changes':- </i></td></tr><td>&nbsp;</td></tr>";

global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;
global $thisLinkTxt;
global $thisTxtFile;
global $thisImage;
global $thisThumb;
global $thisXtra;
global $thisVisits;
global $thisRestrict;


require("../DBinfo.php");
$tablename = "pages";
$link = mysql_connect($host,$user,$password);
$query = "SELECT * FROM $tablename WHERE id = '$id'";

$result = mysql_db_query($dbname,$query,$link);
while($row = mysql_fetch_array($result)){

$thisLinkTxt = $row[linkTxt];
$thisTxtFile = $row[txtFile];
$thisImage = $row[image];
$thisThumb = $row[thumbnail];
$thisXtra = $row[xtra];
$thisVisits = $row[visits];
$thisRestrict = $row[restricted];

}
if($result){
mysql_close($link);
}


echo "<tr><td>Edit link Text, please try to keep this as short as possible</td></tr><tr><td><input type=text name=\"linkTxt\" size=60 value=\"$thisLinkTxt\"></td></tr>";
echo "<tr><td>Edit the text content for the page</td></tr><tr><td><textarea name=\"text\" cols=100 rows = 20>";

//display txtFile
$toOpen = "../".$thisTxtFile;

$open = fopen($toOpen,"r");
if(open){

$text = file($toOpen);

fclose($open);
}


for($n=0; $n<count($text); $n++){


$pattern = "ï»¿txt=";
$replace = "";
$text[$n] = eregi_replace($pattern,$replace,$text[$n]);

$pattern = "txt=";
$replace = "";
$text[$n] = eregi_replace($pattern,$replace,$text[$n]);

$pattern = "<br>";
$replace = "\r";
$text[$n] = eregi_replace($pattern,$replace,$text[$n]);

$text[$n] = utf8_decode($text[$n]);

$pattern = "\(A\)";
$replace = "&";
$text[$n] = ereg_replace($pattern,$replace,$text[$n]);



print("$text[$n]");

}

echo "</textarea></td></tr>";

//images
if($thisImage != ""){
echo "<tr><td>Currently Stored Images (click on an image to change/remove)</td></tr><tr><td>";
$images = $thisImage;
$allImages = explode("^",$images);
$total = count($allImages);

for($i=0;$i<$total;$i++){

echo"<a href=\"javascript:imageEdit('pages.php','$id','$i')\"><img src=\"../$allImages[$i]\" width=100 height=100 border=1></a>";
}
echo"</td></tr>";
echo "<tr><td><a href=\"javascript:openConfirm('pages.php','$id');\">Click here to CLEAR ALL images for this page</a></td></tr>\n";

}else{
echo "<tr><td>There are currently NO stored images for this page</td></tr><tr><td>";

}
echo "<tr><td>Upload an additional image<br><input type=file name=\"file\" size=20></td></tr>\n";

echo "<tr><td>Access Level<br><input type=text name=\"restrict\" size=5 value=\"$thisRestrict\"></td></tr>\n";
echo "<tr><td>This page has been visited $thisVisits time(s)</td></tr><tr><td>&nbsp;</td></tr>";

echo "<input type=hidden name=\"oldImages\" value=\"$thisImage\">\n";
echo "<input type=hidden name=\"oldThumb\" value=\"$thisThumb\">\n";
echo "<input type=hidden name=\"oldLinkTxt\" value=\"$thisLinkTxt\">\n";


//end images
echo "<input type=hidden name=\"editPageChanges\" value=true>\n";
echo "<input type=hidden name=\"id\" value=\"$id\">\n";
echo "<input type=hidden name=\"txtFile\" value=\"$thisTxtFile\">\n";

echo "<tr><td><a href=\"javascript:document.theForm.submit();\">submit changes</a> </td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.reset();\">reset</a> </td></tr>";

echo "</form>";

echo "</table></body></html>";

}else if($confirmDelete){



echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\">";

echo"<script language=\"javascript\">function checkForm(){if(document.theForm.confirm.checked != true){alert(\"You need to tick the box to confirm deletion\");}else{document.theForm.submit();}}</script>";
echo"</head><body>";

echo"<form name=\"theForm\" action=\"pages.php\" method=\"post\">";

echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><i>Are you sure you want to delete:-</i></td></tr><td>&nbsp;</td></tr>";





//Gets Details from the db

global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;


require("../DBinfo.php");
$tablename = "pages";
$link = mysql_connect($host,$user,$password);
$query = "SELECT * FROM $tablename WHERE id = '$id'";

$result = mysql_db_query($dbname,$query,$link);
while($row = mysql_fetch_array($result)){

if($row[name] != ""){
echo "<tr><td><b><i>$row[name] > ";
}else if($row[linkTo] != ""){
echo "<tr><td><b><i>$row[linkTo] > ";
}else{
echo "<tr><td><b><i>";
}

echo "$row[linkTxt]</i></b></td></tr>";


}
if($result){
mysql_close($link);
}
echo "<tr><td>&nbsp;</td></tr><tr><td><input type=\"checkbox\" name=\"confirm\" value=\"true\">";

if($type == "section"){

echo "Tick box to confirm deletion of this section and <b><u>ALL</u></b> linked pages <b><u>AND</u></b> their content. Once deleted this is <b><u>NOT</u></b> undoable";

}else if ($type == "page"){

echo "Tick box to confirm deletion of this page and <b><u>ALL</u></b> it's contents. Once deleted this is <b><u>NOT</u></b> undoable";

}else if ($type == "link"){

echo "Tick box to confirm deletion of this link. Once deleted this is <b><u>NOT</u></b> undoable";

}


echo "</td></tr>";
echo "<input type=hidden name=\"toDelete\" value=true>\n";
echo "<input type=hidden name=\"id\" value=\"$id\">\n";
echo "<input type=hidden name=\"type\" value=\"$type\">\n";


echo "<tr><td>&nbsp;</td></tr><tr><td><a href=\"javascript:checkForm();\">delete</a> </td></tr>";
echo "<tr><td><a href=\"pages.php?existing=true\">cancel</a> </td></tr>";

echo "</form>";

echo "</table></body></html>";








}else if ($toDelete){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";




global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;


require("../DBinfo.php");
$tablename = "pages";
$link = mysql_connect($host,$user,$password);


if($type == "section"){

//gets the name
global $sectionName;
global $success;
$query = "SELECT name FROM $tablename WHERE id ='$id';";

$result = mysql_db_query($dbname, $query, $link);

while($row = mysql_fetch_array($result)){

$sectionName = $row[name];

}

$query = "DELETE FROM $tablename WHERE id=$id";

if(mysql_db_query($dbname,$query,$link)){

echo "<tr><td>The Section has been successfully deleted</td></tr><td>&nbsp;</td></tr>";
$success = 1;
}else{

echo "<tr><td>The Section could not deleted</td></tr><td>&nbsp;</td></tr>";
$success = 0;
}

if($success == 1){
// deletes all pages from that section

$query = "SELECT txtFile,image,thumbnail,html FROM $tablename WHERE linkTo='$sectionName' AND linkTo !=''";

$result = mysql_db_query($dbname, $query, $link);

while($row = mysql_fetch_array($result)){

$htmlToDelete = "../html/".$row[html];
if(unlink($htmlToDelete)){


echo "<tr><td>$row[html] has been removed</td></tr>";
}


$fileToDelete = "../".$row[txtFile];

if(unlink($fileToDelete)){


echo "<tr><td>$fileToDelete has been removed</td></tr>";
}

$fileToDelete = "../".$row[thumbnail];

if(unlink($fileToDelete)){


echo "<tr><td>$fileToDelete has been removed</td></tr>";
}


$fileToDelete = 

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



$query = "DELETE FROM $tablename WHERE linkTo='$sectionName' AND linkTo !=''";

if(mysql_db_query($dbname,$query,$link)){

echo "<tr><td>The Page(s) has been successfully deleted</td></tr><td>&nbsp;</td></tr>";

}else{

echo "<tr><td>The Page(s) could not deleted</td></tr><td>&nbsp;</td></tr>";

}

@mysql_close($link);

}


}else if($type == "page"){

//deletes the txtFile
$query = "SELECT txtFile,image,thumbnail,html FROM $tablename WHERE id=$id";

$result = mysql_db_query($dbname, $query, $link);

while($row = mysql_fetch_array($result)){

$htmlToDelete = "../html/".$row[html];
if(unlink($htmlToDelete)){


echo "<tr><td>$row[html] has been removed</td></tr>";
}

$fileToDelete = "../".$row[txtFile];

if(unlink($fileToDelete)){


echo "<tr><td>$fileToDelete has been removed</td></tr>";
}



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



$query = "DELETE FROM $tablename WHERE id=$id";

if(mysql_db_query($dbname,$query,$link)){

echo "<tr><td>The Page has been successfully deleted</td></tr><td>&nbsp;</td></tr>";

}else{

echo "<tr><td>The Page could not deleted</td></tr><td>&nbsp;</td></tr>";

}

@mysql_close($link);




}else if($type == "link"){

$query = "DELETE FROM $tablename WHERE id=$id";

if(mysql_db_query($dbname,$query,$link)){

echo "<tr><td>The Link has been successfully deleted</td></tr><td>&nbsp;</td></tr>";

}else{

echo "<tr><td>The Link could not be deleted</td></tr><td>&nbsp;</td></tr>";

}

@mysql_close($link);

}


/************************************************
*************************************************

Recreate the index.html

*************************************************
*************************************************/
//start


if(include("htmlTemplate.php")){

createIndex();
}

//end


/***************************************************
****************************************************

End recreating Index

****************************************************
****************************************************/


echo "<tr><td>&nbsp;</td></tr><tr><td><a href=\"pages.php\">click here to return to the main menu</a></td></tr>";

echo "</table></body></html>";


}else if ($beensubmitted){

global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;
global $imageName;
global $thumbnail;

require("../DBinfo.php");
$tablename = "pages";
$link = mysql_connect($host,$user,$password);

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";




$name = trim($name);
$txtFile= trim($txtFile);
$linkTxt= trim($linkTxt);
$restrict = trim($restrict);




$replace = "";
$pattern = "’";
$linkTxt = eregi_replace($pattern,$replace,$linkTxt);

$pattern = "‘";
$replace = "";
$linkTxt = eregi_replace($pattern,$replace,$linkTxt);

$pattern = "'";
$replace = "";
$linkTxt = eregi_replace($pattern,$replace,$linkTxt);


$hyperlink = trim($hyperlink);
$linkTo = trim($linkTo);
$xtra = trim($xtra);
$visits = 1;

$pageType = trim($pageType);
$mailTxt = trim($mailTxt);
$urlTxt = trim($urlTxt);



if(($pageType == "Standalone Page") || ($pageType == "Link")){
$name = $linkTxt;
}







$date = date("Y-m-d");

if($hyperlink != ""){

$imageName = "";

if($hyperlink == "swf"){
$txtFile1 = "swf";

}else if($mailTxt != ""){

$txtFile1 = "LINK!mailto:".$mailTxt;

}else if ($urlTxt != ""){

$txtFile1 = "LINK!http://".$urlTxt;
}




}else{

$encodedFile = md5($text);
$begin = rand(0,22);
$tenDigitName = substr($encodedFile,$begin,10);
$txtFile = "../txtFiles/".$tenDigitName.".txt";
$txtFile1 = "txtFiles/".$tenDigitName.".txt";


$open = fopen($txtFile,"w+");
if(open){

//$text = stripslashes($text);
$text = trim($text);



/*$replace = "";
$pattern = "'";
$text = eregi_replace($pattern,$replace,$text);*/

$replace = "<br>";
$pattern = "\n";
$text = eregi_replace($pattern,$replace,$text);

$replace = "";
$pattern = "\r";
$text = eregi_replace($pattern,$replace,$text);

$replace = "(A)";
$pattern = "&";
$text = ereg_replace($pattern,$replace,$text);

$replace = "'";
$pattern = "’";
$text = eregi_replace($pattern,$replace,$text);

$replace = "'";
$pattern = "‘";
$text = eregi_replace($pattern,$replace,$text);

$htmlText = $text;

$text = utf8_encode($text);



$toWrite = "txt=".$text;

fwrite($open,"$toWrite");
fclose($open);
}

//Create Description



$replace = " ";
$pattern = "\n";
$descript = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "\t";
$descript = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "[$<][^<^>]{1,100}[$>]";
$descript = eregi_replace($pattern,$replace,$descript);

$replace = " ";
$pattern = "\r";
$descript = eregi_replace($pattern,$replace,$descript);



$replace = "and";
$pattern = "\(A\)";
$descript = ereg_replace($pattern,$replace,$descript);

$replace = "";
$pattern = "[,!@\&\$£;:\\?%\"\'<>\\/=#]";
$descript = eregi_replace($pattern,$replace,$descript);

$replace = "£";
$pattern = "[[:space:]]{1,100}";
$descript = eregi_replace($pattern,$replace,$descript);

$replace = " ";
$pattern = "£";
$descript = eregi_replace($pattern,$replace,$descript);


if(strlen($descript) > 100){
$description = substr($descript,0,100)."....";
}else{
$description = $descript;
}



//Create keywords
$text = $linkTxt.$text;

$replace = " ";
$pattern = "\n";
$text = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "\r";
$text = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "\t";
$text = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "[$<][^<^>]{1,100}[$>]";
$text = eregi_replace($pattern,$replace,$text);

$replace = "";
$pattern = "[,.!@\&\$£;:\\?%#]";
$text = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "[^a-z][a-z]{1,3}[^a-z]";
$text = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "[^a-z][a-z]{1,3}[^a-z]";
$text = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "[^a-z][a-z]{1,3}[^a-z]";
$text = eregi_replace($pattern,$replace,$text);


$replace = "and";
$pattern = "\(A\)";
$text = ereg_replace($pattern,$replace,$text);


$replace = "";
$pattern = "[,!@\&\$£;:\\?%\"\'=<>\\/]";
$text = eregi_replace($pattern,$replace,$text);

$replace = "£";
$pattern = "[[:space:]]{1,100}";
$text = eregi_replace($pattern,$replace,$text);

$replace = " ";
$pattern = "£";
$text = eregi_replace($pattern,$replace,$text);


$keywords = $text;

// end create keywords




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



//checks the files

if ($file){

$thisIm = getimagesize($file);
$thisWidth = $thisIm[0];
$thisHeight = $thisIm[1];
$thisFile = $thisIm[2];

//check for JPEG only images;
if($thisFile == 2){

	//check to see if the file already exists
	$imageDir = "../images/".$file_name;
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
	
	
	if(image_createThumb($file,"../images/".$newFile,$mainImageWidth,$mainImageHeight,100,$mainImageWidth,$mainImageHeight,0xffffff)){
		echo "<tr><td colspan=\"3\">the image ".$file_name." has been sucessfully uploaded<td><tr>\n";
		$imageName = "images/".$newFile;
		
		}else{
		echo"<tr><td colspan=\"3\">the image ".$file_name." could not be resampled<td><tr>\n";
		$imageName = "";
		}
	
	// creates thumbnail
	if(image_createThumb($file,"../images/t".$newFile,$thumbnailWidth,$thumbnailHeight,100,$thumbnailWidth,$thumbnailHeight,0xffffff)){
		echo "<tr><td colspan=\"3\">the thumbnail ".$file_name." has been sucessfully created<td><tr>\n";
		$thumbnail = "images/t".$newFile;
		
		}else{
		echo"<tr><td colspan=\"3\">the thumbnail ".$file_name." could not be created<td><tr>\n";
		$thumbnail = "";
		}
		
	if(image_createThumb($file,"../images/L".$newFile,$largeImageWidth,$largeImageHeight,100,$largeImageWidth,$largeImageHeight,0xffffff)){
		echo "<tr><td colspan=\"3\">the enlarged ".$file_name." has been sucessfully created<td><tr>\n";
		

		}else{
		echo"<tr><td colspan=\"3\">the enlarged ".$file_name." could not be created<td><tr>\n";
		
		}
	
	unlink ($file);
}
}


/* IMAGES
IMAGES
IMAGES
IMAGES*/

}


//get next List Number

global $n;

$n = 0;

if(($pageType == "Standalone Page") ||($pageType == "Link")||($pageType == "New Section")){

$numQuery = "SELECT listNum from $tablename WHERE name != '' ORDER BY listNum DESC LIMIT 1;";

}else{

$numQuery = "SELECT listNum from $tablename WHERE linkTo='$linkTo' ORDER BY listNum DESC LIMIT 1;";

}

$result = mysql_db_query($dbname, $numQuery, $link);

while($row = mysql_fetch_array($result)){

$n = $row[listNum];



}
$n++;



/************************************************
*************************************************

Start create a HTML version of the page

*************************************************
*************************************************/
//start






include("htmlTemplate.php");




//end


/***************************************************
****************************************************

End create a HTML version of the page

****************************************************
****************************************************/




$Query = "INSERT into $tablename values ('0', '$name' , '$linkTxt' , '$description' ,'$txtFile1','$imageName','$date','$linkTo','$keywords','$thumbnail','$xtra','$visits','$restrict','$n','$htmlPageName')";


if (mysql_db_query ($dbname, $Query, $link)){
	echo "<tr><td><i>The <b>$pageType</b> was successfully created</i></td></tr><td>&nbsp;</td></tr>";
} else {
	echo "<tr><td><i>The <b>$pageType</b> could not be created</i></td></tr><td>&nbsp;</td></tr>";
}

@createIndex();

mysql_close ($link);



echo "<tr><td><i><a href=\"pages.php\">click here to go back to the main options</a></td></tr><td>&nbsp;</td></tr>";
echo "</table></body></html>";


}else if($newSection){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";

echo"<form name=\"theForm\" action=\"pages.php\" method=\"post\">";

echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><i>The following sections have already been created:-</i></td></tr>";

// gets existing sections from database
global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;

require("../DBinfo.php");
$tablename = "pages";
$link = mysql_connect($host,$user,$password);
$query = "SELECT name FROM $tablename WHERE name != ''";

$result = mysql_db_query($dbname,$query,$link);

echo "<tr><td><b><i>";

while($row = mysql_fetch_array($result)){

echo "$row[name], ";
}
if($result){
mysql_close($link);
}

echo "</i></b></td></tr><tr><td>&nbsp;</td><tr>";

echo "<tr><td>Enter the name of the NEW section <b>(ONE WORD ONLY)</b>, please be careful not to use a name from the list above has this will cause errors</td></tr><tr><td><input type=text name=\"name\" size=20></td></tr>";
echo "<tr><td>Enter the text description for the NEW section (this is what will be display on the home page)</td></tr><tr><td><input type=text name=\"linkTxt\" size=20></td></tr>";
echo "<tr><td>Access Level<br><input type=text name=\"restrict\" size=5 value=\"0\"></td></tr>\n";
echo "<input type=hidden name=\"beensubmitted\" value=true>\n";
echo "<input type=hidden name=\"text\" value=\"\">\n";
echo "<input type=hidden name=\"description\" value=\"\">\n";
echo "<input type=hidden name=\"hyperlink\" value=\"swf\">\n";
echo "<input type=hidden name=\"image\" value=\"\">\n";
echo "<input type=hidden name=\"linkTo\" value=\"\">\n";
echo "<input type=hidden name=\"pageType\" value=\"New Section\">\n";

echo "<tr><td><a href=\"javascript:document.theForm.submit();\">submit</a> </td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.reset();\">reset</a> </td></tr>";

echo "</form>";

echo "</table></body></html>";


}else if ($linkToExisting){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";

echo"<form name=\"theForm\" action=\"pages.php\" enctype=\"multipart/form-data\" method=\"post\">";

echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><i>Please fill in the following details:- </i></td></tr><td>&nbsp;</td></tr>";

echo "<tr><td>Enter the text you want for the link to the page, please try to keep this as short as posible</td></tr><tr><td><input type=text name=\"linkTxt\" size=20></td></tr>";
echo "<tr><td>Enter the text content for the page</td></tr><tr><td><textarea name=\"text\" cols=100 rows = 20></textarea></td></tr>";

//enter image
echo "<tr><td>Upload Image<br><input type=file name=\"file\" size=20></td></tr>\n";

echo "<tr><td>Access Level<br><input type=text name=\"restrict\" size=5 value=\"0\"></td></tr>\n";


// gets existing sections from database
global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;

require("../DBinfo.php");
$tablename = "pages";
$link = mysql_connect($host,$user,$password);
$query = "SELECT name FROM $tablename WHERE name != '' AND txtFile like 'swf'";

$result = mysql_db_query($dbname,$query,$link);

echo "<tr><td>Select the EXISTING section you want to link the NEW page too</td></tr><tr><td><select name=\"linkTo\">";

while($row = mysql_fetch_array($result)){

echo "<option value=\"$row[name]\">$row[name]</option>";
}

echo "</select></td><tr>";

if($result){
mysql_close($link);
}


echo "<input type=hidden name=\"beensubmitted\" value=true>\n";
echo "<input type=hidden name=\"name\" value=\"\">\n";
echo "<input type=hidden name=\"description\" value=\"\">\n";
echo "<input type=hidden name=\"hyperlink\" value=\"\">\n";
echo "<input type=hidden name=\"image\" value=\"\">\n";
echo "<input type=hidden name=\"pageType\" value=\"Linked Page\">\n";

echo "<tr><td><a href=\"javascript:document.theForm.submit();\">submit</a> </td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.reset();\">reset</a> </td></tr>";

echo "</form>";

echo "</table></body></html>";


}else if ($existingSectionLink){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";

echo"<form name=\"theForm\" action=\"pages.php\" method=\"post\">";

echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><i>Please fill in the following details:- </i></td></tr><td>&nbsp;</td></tr>";

echo "<tr><td>Enter the text you want for the link, please try to keep this as short as posible</td></tr><tr><td><input type=text name=\"linkTxt\" size=20></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td>Please fill in only <b>ONE</b> of the following:-</td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td>Enter the email address if applicable</td></tr><tr><td><input type=text name=\"mailTxt\" size=20></td></tr>";
echo "<tr><td>Enter the website address if applicable</td></tr><tr><td>http://<input type=text name=\"urlTxt\" size=20></td></tr>";

// gets existing sections from database
global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;

require("../DBinfo.php");
$tablename = "pages";
$link = mysql_connect($host,$user,$password);
$query = "SELECT name FROM $tablename WHERE name != '' AND txtFile like 'swf'";

$result = mysql_db_query($dbname,$query,$link);

echo "<tr><td>Select the EXISTING section you want to link to appear on</td></tr><tr><td><select name=\"linkTo\">";

while($row = mysql_fetch_array($result)){

echo "<option value=\"$row[name]\">$row[name]</option>";
}

echo "</select></td><tr>";

if($result){
mysql_close($link);
}

echo "<tr><td>Access Level<br><input type=text name=\"restrict\" size=5 value=\"0\"></td></tr>\n";
echo "<input type=hidden name=\"beensubmitted\" value=true>\n";
echo "<input type=hidden name=\"name\" value=\"\">\n";
echo "<input type=hidden name=\"description\" value=\"\">\n";
echo "<input type=hidden name=\"hyperlink\" value=\"yes\">\n";
echo "<input type=hidden name=\"image\" value=\"\">\n";
echo "<input type=hidden name=\"text\" value=\"\">\n";
echo "<input type=hidden name=\"pageType\" value=\"sectionLink\">\n";

echo "<tr><td>&nbsp;</td></tr><tr><td><a href=\"javascript:document.theForm.submit();\">submit</a> </td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.reset();\">reset</a> </td></tr>";

echo "</form>";

echo "</table></body></html>";


}else if ($homePageLink){



echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";

echo"<form name=\"theForm\" action=\"pages.php\" method=\"post\">";

echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><i>Please fill in the following details:- </i></td></tr><td>&nbsp;</td></tr>";

echo "<tr><td>Enter the text you want for the link, please try to keep this as short as posible</td></tr><tr><td><input type=text name=\"linkTxt\" size=20></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td>Please fill in only <b>ONE</b> of the following:-</td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td>Enter the email address if applicable</td></tr><tr><td><input type=text name=\"mailTxt\" size=20></td></tr>";
echo "<tr><td>Enter the website address if applicable</td></tr><tr><td>http://<input type=text name=\"urlTxt\" size=20></td></tr>";
echo "<tr><td>Access Level<br><input type=text name=\"restrict\" size=5 value=\"0\"></td></tr>\n";


echo "<input type=hidden name=\"beensubmitted\" value=true>\n";
echo "<input type=hidden name=\"name\" value=\"\">\n";
echo "<input type=hidden name=\"description\" value=\"\">\n";
echo "<input type=hidden name=\"hyperlink\" value=\"yes\">\n";
echo "<input type=hidden name=\"image\" value=\"\">\n";
echo "<input type=hidden name=\"linkTo\" value=\"\">\n";
echo "<input type=hidden name=\"text\" value=\"\">\n";
echo "<input type=hidden name=\"pageType\" value=\"Link\">\n";

echo "<tr><td>&nbsp;</td></tr><tr><td><a href=\"javascript:document.theForm.submit();\">submit</a> </td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.reset();\">reset</a> </td></tr>";

echo "</form>";

echo "</table></body></html>";





}else if($standalonePage){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";

echo"<form name=\"theForm\" action=\"pages.php\" enctype=\"multipart/form-data\" method=\"post\">";

echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><i>Please fill in the following details:- </i></td></tr><tr><td>&nbsp;</td></tr>";

echo "<tr><td>Enter the text you want for the link to the page, please try to keep this as short as posible</td></tr><tr><td><input type=text name=\"linkTxt\" size=20></td></tr>";
echo "<tr><td>Enter the text content for the page</td></tr><tr><td><textarea name=\"text\" cols=100 rows = 20></textarea></td></tr>";
//enter image
echo "<tr><td>Upload Image<br><input type=file name=\"file\" size=20></td></tr>\n";

echo "<tr><td>Access Level<br><input type=text name=\"restrict\" size=5 value=\"0\"></td></tr>\n";

echo "<input type=hidden name=\"beensubmitted\" value=true>\n";
echo "<input type=hidden name=\"name\" value=\"\">\n";
echo "<input type=hidden name=\"description\" value=\"\">\n";
echo "<input type=hidden name=\"hyperlink\" value=\"\">\n";
echo "<input type=hidden name=\"image\" value=\"\">\n";
echo "<input type=hidden name=\"linkTo\" value=\"\">\n";
echo "<input type=hidden name=\"pageType\" value=\"Standalone Page\">\n";

echo "<tr><td><a href=\"javascript:document.theForm.submit();\">submit</a> </td></tr>";
echo "<tr><td><a href=\"javascript:document.theForm.reset();\">reset</a> </td></tr>";

echo "</form>";

echo "</table></body></html>";

}else if($new){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><i>You have the following options available:- </i></td></tr><td>&nbsp;</td></tr>";
echo "<tr><td><a href=\"pages.php?standalonePage=true\">Do you want to create a STANDALONE page on the home menu?</a></td></tr>";
echo "<tr><td><a href=\"pages.php?newSection=true\">Do you want to create a NEW section?</a></td></tr>";
echo "<tr><td><a href=\"pages.php?linkToExisting=true\">Do you want to create a page that links to an EXISTING section?</a </td></tr>";
echo "<tr><td><a href=\"pages.php?homePageLink=true\">Do you want to create a link on the home menu to another WEBSITE or EMAIL?</a></td></tr>";
echo "<tr><td><a href=\"pages.php?existingSectionLink=true\">Do you want to create a link on an EXISTING section menu to another WEBSITE or EMAIL?</a></td></tr>";
echo "</table></body></html>";




}elseif(!$existing){
echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td ><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td >Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td><i>You have the following options available:- </i></td></tr><td>&nbsp;</td></tr>";
echo "<tr><td>Add a <a href=\"pages.php?new=true\">NEW</a> Page or Section </td></tr>";
echo "<tr><td>Edit an <a href=\"pages.php?existing=true\">EXISTING</a> Page or Section </td></tr>";
echo "<tr><td>edit <a href=\"editFAQ.php\">FAQ</a></td></tr>";

//gets the counter

global $Count;
 	
	$TheFile ="../counter.txt";

	$Open = fopen ($TheFile, "r");
	if ($Open){
		$Data = file($TheFile);
		$Count = $Data[0];
		fclose ($Open);
	}
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>You have had $Count visitors to your site</td></tr>";

echo "</table></body></html>";

}


if($existing){

echo "<html><head><title>Edit Mode</title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"><link href=\"thisStyle.css\" rel=\"stylesheet\" type=\"text/css\"></head><body>";
echo "<table width=\"90%\"  border=\"0\"><tr><td><img src=\"$topImage\"></td></tr><tr><td >&nbsp;</td></tr>";
require("menu.php");
echo "<tr><td colspan =6>Welcome <b>$thisFirstName $thisSurname</b></td></tr><tr><td>&nbsp;</td></tr>";
echo "<tr><td colspan=6><i>You have created the following pages and sections:- </i></td></tr><td>&nbsp;</td></tr>";




global $host;
global $user;
global $password;
global $dbname;
global $tablename;
global $link;

require("../DBinfo.php");
$tablename = "pages";
$link = mysql_connect($host,$user,$password);

//get Highest and Lowest
global $highest;
global $lowest;
$totalQuery = "SELECT listNum AS highest FROM $tablename WHERE name != '' ORDER by listNum DESC LIMIT 1";
$thisNum = mysql_db_query($dbname,$totalQuery,$link);

$res = mysql_fetch_array($thisNum);
$highest = $res[highest];

$totalQuery = "SELECT listNum AS lowest FROM $tablename WHERE name != '' ORDER by listNum LIMIT 1";
$thisNum = mysql_db_query($dbname,$totalQuery,$link);

$res = mysql_fetch_array($thisNum);
$lowest = $res[lowest];

//end get highest lowest


$query = "SELECT * FROM $tablename WHERE name != '' ORDER BY listNum;";

$result = mysql_db_query($dbname,$query,$link);



while($row = mysql_fetch_array($result)){

$thisType = substr($row[txtFile],0,3);

if($thisType == "swf"){
$type = "section";
$toEdit = "?editSection=true&id=".$row[id];
}else if ($thisType == "LIN"){
$type = "link";
$toEdit = "?editLink=true&id=".$row[id];
}else if ($thisType == "txt"){
$type = "page";
$toEdit = "?editPage=true&id=".$row[id];
}

if($row[image] != ""){
$displayImg = "<img src=\"image.gif\">";
$images = $row[image];
$allImages = explode("^",$images);
$total = count($allImages);
}else{
$displayImg = "";
$total = "";
}



if($row[listNum]== $lowest){

$upDown = "<a href=\"pages.php?moveSectionDown=true&id=$row[id]\"><img src=\"arrow_down.gif\" width=20 height=8 border=0></a>";

}elseif($row[listNum] == $highest){
$upDown = "<a href=\"pages.php?moveSectionUp=true&id=$row[id]\"><img src=\"arrow_up.gif\" width=20 height=8 border=0></a>";
}else{
$upDown = "<a href=\"pages.php?moveSectionUp=true&id=$row[id]\"><img src=\"arrow_up.gif\" width=20 height=8 border=0></a><br><a href=\"pages.php?moveSectionDown=true&id=$row[id]\"><img src=\"arrow_down.gif\" width=20 height=8 border=0></a>";
}



if($row[restricted] != 0){


echo "<tr><td width=\"70%\" class=\"lineRed\"><b><i>$row[linkTxt]</i></b></td><td width=\"5%\">$type</td><td width=\"5%\"><a href=\"pages.php".$toEdit."\">edit</a></td><td width=\"5%\"><a href=\"pages.php?confirmDelete=true&id=".$row[id]."&type=".$type."\">delete</a></td><td align=\"left\" valign=\"middle\" width=\"5%\" >".$upDown."</td><td align=\"left\" valign=\"middle\" width=\"5%\" >".$displayImg.$total."</td><td>&nbsp;</td></tr>";

}else{



echo "<tr><td width=\"70%\" ><b><i>$row[linkTxt]</i></b></td><td width=\"5%\">$type</td><td width=\"5%\"><a href=\"pages.php".$toEdit."\">edit</a></td><td width=\"5%\"><a href=\"pages.php?confirmDelete=true&id=".$row[id]."&type=".$type."\">delete</a></td><td align=\"left\" valign=\"middle\" width=\"5%\">".$upDown."</td><td align=\"left\" valign=\"middle\" width=\"5%\" >".$displayImg.$total."</td><td>&nbsp;</td></tr>";
}
// second db query
$tablename = "pages";
$link2 = mysql_connect($host,$user,$password);
$query2 = "SELECT * FROM $tablename WHERE linkTo = '$row[name]' ORDER BY listNum;";

//get Highest and Lowest
global $highest2;
global $lowest2;
$totalQuery2 = "SELECT listNum AS highest FROM $tablename WHERE linkTo = '$row[name]' ORDER by listNum DESC LIMIT 1";
$thisNum2 = mysql_db_query($dbname,$totalQuery2,$link2);

$res2= mysql_fetch_array($thisNum2);
$highest2 = $res2[highest];

$totalQuery2 = "SELECT listNum AS lowest FROM $tablename WHERE linkTo = '$row[name]' ORDER by listNum LIMIT 1";
$thisNum2 = mysql_db_query($dbname,$totalQuery2,$link2);

$res2 = mysql_fetch_array($thisNum2);
$lowest2 = $res2[lowest];

//end get highest lowest


$result2 = mysql_db_query($dbname,$query2,$link2);

while($row2 = mysql_fetch_array($result2)){

$thisType = substr($row2[txtFile],0,3);
if($thisType == "swf"){
$type = "section";
$toEdit = "?editSection=true&id=".$row2[id];
}else if ($thisType == "LIN"){
$type = "link";
$toEdit = "?editLink=true&id=".$row2[id];
}else if ($thisType == "txt"){
$type = "page";
$toEdit = "?editPage=true&id=".$row2[id];
}

if($row2[image] != ""){
$displayImg = "<img src=\"image.gif\">";

$images = $row2[image];
$allImages = explode("^",$images);
$total = count($allImages);


}else{
$displayImg = "";
$total = "";
}


if($lowest2 == $highest2){
$upDown2 = "";
}else if($row2[listNum]== $lowest2){

$upDown2 = "<a href=\"pages.php?movePageDown=true&id=$row2[id]\"><img src=\"arrow_down_grey.gif\" width=20 height=8 border=0></a>";

}elseif($row2[listNum] == $highest2){
$upDown2 = "<a href=\"pages.php?movePageUp=true&id=$row2[id]\"><img src=\"arrow_up_grey.gif\" width=20 height=8 border=0></a>";
}else{
$upDown2 = "<a href=\"pages.php?movePageUp=true&id=$row2[id]\"><img src=\"arrow_up_grey.gif\" width=20 height=8 border=0></a><br><a href=\"pages.php?movePageDown=true&id=$row2[id]\"><img src=\"arrow_down_grey.gif\" width=20 height=8 border=0></a>";
}


if($row2[restricted] != 0){


echo "<tr><td width=\"70%\" class=\"lineRed\">$row2[linkTxt]</td><td width=\"5%\">$type</td><td width=\"5%\"><a href=\"pages.php".$toEdit."\">edit</a></td><td width=\"5%\"><a href=\"pages.php?confirmDelete=true&id=".$row2[id]."&type=".$type."\">delete</a></td><td align=\"left\" valign=\"middle\" width=\"5%\" >".$upDown2."</td><td align=\"left\" valign=\"middle\" width=\"5%\" >".$displayImg.$total."</td><td>&nbsp;</td></tr>";

}else{



echo "<tr><td width=\"70%\" >$row2[linkTxt]</td><td width=\"5%\">$type</td><td width=\"5%\"><a href=\"pages.php".$toEdit."\">edit</a></td><td width=\"5%\"><a href=\"pages.php?confirmDelete=true&id=".$row2[id]."&type=".$type."\">delete</a></td><td align=\"left\" valign=\"middle\" width=\"5%\">".$upDown2."</td><td align=\"left\" valign=\"middle\" width=\"5%\" >".$displayImg.$total."</td><td>&nbsp;</td></tr>";
}
}
if($result2){
mysql_close($link2);
}

echo"<tr><td colspan=\"4\" class=\"line\">&nbsp;</td><tr>";
}



if($result){
mysql_close($link);
}

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
 