<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CONFIRM DELETION</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style>
<!--
A{
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-style: normal;
	font-weight: bold;
	color: #000066;
	text-decoration: none;
}
BODY {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-style: normal;
	font-weight: normal;
	color: #000066;
	background-color: #FFFFFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

-->

</style>

<script language="javascript">


function okDel(returnLink,id,url,surveyName){


opener.document.location = returnLink+"?toDelete=true&id="+id+"&url="+url+"&surveyName="+surveyName;
window.close(this);

}


</script>
</head>

<body>
<table width="100%" height="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
  <td align="center" valign="middle"><br><br><br><br><b>Confirm Deletion</b><br>Warning this action cannot be undone!<br><br>
 <?PHP
  $returnLink = trim($returnLink);
  $id = trim($id);
  $url = trim($url);
  $surveyName = trim($surveyName);
  
  
  echo "<a href=\"javascript:okDel('$returnLink','$id','$url','$surveyName')\">delete</a> | <a href=\"javascript:window.close(this)\">cancel</a>";
	
	
?>
	</td>
  </tr>
</table>
</body>
</html>
