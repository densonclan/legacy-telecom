<?PHP


$html = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
$html .= "<html>\n<head>\n<title>Legacy Telecom Frequently Asked Questions</title>\n";
$html .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
$html .= "<META NAME=\"keywords\" content=\"Frequently Asked Questions,lantec\">\n";
$html .= "<META NAME=\"description\" content=\"If you cannot find the answer your looking for from the frequently asked questions below you can sub....\">\n";
$html .= "<link href=\"mainStyle.css\" rel=\"stylesheet\" type=\"text/css\">\n";
$html .=  "<script src=\"javascripts.js\" language=\"javascript\" type=\"text/javascript\">\n\n</script>\n\n";
$html .= "</head>\n<body>\n<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n<tr>\n";
$html .=  "<tr><td colspan=\"6\" valign=\"top\" height =\"102\" class=\"redStripeBack\"><div align=\"left\"><img src=\"../logo.jpg\" width=\"558\" height=\"100\" alt=\"Legacy Telecom\"></div></td></tr>\n";


$html .= "<tr><td width=\"5\"><img src=\"clear.gif\" width=\"9\" height=\"5\"></td><td width=\"240\" valign=\"top\" class=\"sideMenu\"><br><b><a href=\"../flash.htm\">Interactive Flash Site</a></b><br><m>LINKS</m><img src=\"image1.jpg\" width=\"240\" height=\"329\"></td>\n";

$html .= "<td width =\"10\"><img src=\"clear.gif\" width=\"10\" height=\"5\"></td>";

$html .= "<td width=\"1092\" valign=\"top\" ><br><h4><i>Frequently Asked Question</i></h4><br>";

/*****************
get FAQs
******************/

global $f;
$f=0;

require("../DBinfo.php");
$tablename = "FAQ";
$link = mysql_connect($host,$user,$password);

$Query1 = "SELECT * from $tablename ORDER BY question,answer,visitors DESC,id;";
$Result = mysql_db_query ($dbname, $Query1, $link);

while ($Row = mysql_fetch_array($Result)){
$f++;
if($f == 1){
$html .= "If you cannot find the answer your looking for from the frequently asked questions below, you can submit your question to us by entering our interactive site <a href=\"../flash.htm\">here</a>, and we will do our best to answer your question as quickly as possible. Your question will also be available to others visiting this site.<br>";
}

$html .= "<br><b>Q. <u>$Row[question]?</u></b></br>\n<b>A. </b>$Row[answer]<br>\n";



}


/*****************
END
******************/


if($f == 0){
$html .= "There are currently no questions stored. You can submit your question to us by entering our interactive site <a href=\"../flash.htm\">here</a>, and we will do our best to answer your question as quickly as possible. Your question will also be available to others visiting this site.";
}
$html .="<br><br></td>";

$html .= "<td width=\"10\" align=\"right\" valign=\"top\" ><img src=\"clear.gif\" width=\"10\" height=\"5\"></td>";
$html .= "<td width=\"10\" align=\"right\" valign=\"top\" >";
$html .= "</td>\n";

$html .= "</tr>\n</table>\n</body>\n</html>";


/****************
create html file
*****************/

$toOpen = "../html/frequently-asked-questions.htm";
$open = fopen($toOpen,"w+");
if(open){


if(fwrite($open,"$html")){

echo "<tr><td><i>The FAQ HTML page was successfully created</td></tr>";


}else{
echo "<tr><td><i>The FAQ HTML page could not be created</td></tr>";

}
fclose($open);
}

require("updateHtmlMenus.php");
updateMenus();



?>