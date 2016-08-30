<?PHP




$question = trim($question);
$codedquestion = urlencode($question);


$email = trim($email);

$mailFrom = "From: $email\r\n";

//$mailTo = "stuart@lan.co.uk";
$mailTo = "sales@legacytelecom.co.uk";


$url = "http://www.legacytelecom.co.uk";

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

$subject = "Legacy Telecom FAQ";
$body = "<html>\n<title>Website FAQ</title>\n<head>\n</head>\n<body>\n";
$body .= "The following question has been submitted:-<br><br><b>".$question."?</b><br><br>click the <a href=\"".$url."/adminlogon/editFAQ.php?automatic=true&question=$codedquestion&email=$email&coded=true\">HERE</a> to answer and send automatic email.";
$body .= "\n</body>\n</html>";
if(mail($mailTo,$subject,$body,$headers.$mailFrom)){
print("success=1");
}else{
print("success=0");
}






?>