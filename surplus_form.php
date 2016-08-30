<?php
require "quickcaptcha/settings.php";

session_start();
$captchastring = strtoupper($_SESSION['string']);
$userstring = strtoupper($_POST['userstring']); 
$GLOBALS['DEBUG_MODE'] = 0;
$GLOBALS['ct_recipient']   = 'sales@legacytelecom.co.uk'; // Change to your email address!
$GLOBALS['ct_msg_subject'] = 'Surplus Equipment Form';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-us">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<title>Legacy Telecom</title>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" type="text/css" href="css/form.css"/>
<script language="JavaScript" type="text/javascript" src="js/form.js"></script>
<meta name="pinterest" content="nopin"/>
  <style type="text/css">
  <!--
  .error { color: #f00; font-weight: bold; font-size: 1.2em; }
  .success { color: #00f; font-weight: bold; font-size: 1.2em; }
  fieldset { width: 90%; }
  legend { font-size: 24px; }
  .note { font-size: 18px;
  -->
  </style>


</head>


<body><script type="text/javascript">

</script>
<h1>Let's talk about value!</h1>

<div id="container">
<button id="styled_button_small" onClick="history.back()">Back</button>
<p id="fm-intro">
	<br>
<b>We buy redundant telecoms equipment.<br/>
Your unwanted telecom equipment could be worth &pound;&pound;&pound;'s.</b><br/>
<br/>
We're interested in buying used, surplus or redundant equipment.
<br/>
Avaya, Cisco, Ericsson, Mitel, Nortel & Siemens wanted.<br/>
<br/>
Systems, digital phones, IP phones and headsets all wanted.<br/>
<br/>
Recycling and recovery services available.<br/>
<br/>
Please complete the form below with as much detail as possible.<br/>
Ensure you provide contact details and a member of staff will respond as soon as possible.<br>
	<br>
	Fields in <strong>bold</strong> are required.</p>

<?php


process_si_contact_form(); // Process the form, if it was submitted

if (isset($_SESSION['ctform']['error']) &&  $_SESSION['ctform']['error'] == true): /* The last form submission had 1 or more errors */ ?>
	<span class="error">There was a problem with your submission.  Errors are displayed below in red.</span><br /><br />
	<?php elseif (isset($_SESSION['ctform']['success']) && $_SESSION['ctform']['success'] == true): /* form was processed successfully */ ?>
	   <span class="success">Thank you! Your details have been submitted.</span><br /><br />
<?php endif; ?>

<form method="post" action="<?php echo $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'] ?>" id="contact_form">
  <input type="hidden" name="do" value="contact" />

<fieldset>

  <div class="fm-req">
    <?php echo @$_SESSION['ctform']['name_error'] ?>
    <label for="fm-name">Your name:</label>
    <input name="fm-name" id="fm-name" type="text"/>
  </div>
	<div class="fm-req">
    <?php echo @$_SESSION['ctform']['company_error'] ?>
		<label for="fm-company">Company Name:</label>
		<input name="fm-company" id="fm-company" type="text"/>
	</div>
	<div class="fm-opt">
		<label for="fm-website">Company web address</label>
		<input name="fm-website" id="fm-website" type="text"/>
	</div>
	<div class="fm-opt">
		<label for="fm-tel">Daytime telephone number:</label>
		<input name="fm-tel" id="fm-tel" type="text"/>
	</div>
	<div class="fm-req">
    <?php echo @$_SESSION['ctform']['email_error'] ?>
		<label for="fm-email">Email address:</label>
		<input name="fm-email" id="fm-email" type="text"/>
	</div>
	<div class="fm-opt">
		<label for="fm-contact">Preferred method of contact:</label>
		<select id="fm-contact" name="fm-contact">
			<option value="Email" selected="selected">Email</option>
			<option value="Telephone">Telephone</option>
		</select>
	</div>
  <div class="fm-opt">
    <label for="fm-manufacturer">Equipment manufacturer:</label>
    <select id="fm-manufacturer" name="fm-manufacturer">
      <option value="Alcatel" selected="selected">Alcatel</option>
      <option value="Avaya">Avaya</option>
      <option value="CISCO">CISCO</option>
      <option value="Ericsson">Ericsson</option>
      <option value="Mitel">Mitel</option>
      <option value="Nortel">Nortel</option>
      <option value="Siemens">Siemens</option>
      <option value="Other">Other</option>
    </select>
  </div>

  <div class="fm-opt">
    <label for="fm-description">Brief description of equipment:</label>
    <textarea name="fm-description" id="fm-description" cols="40" rows="5"></textarea>
  </div>
    <div class="fm-opt">
    <label for="fm-available">Is the equipment available now?</label>
    <select id="fm-available" name="fm-available">
      <option value="Yes" selected="selected">Yes</option>
      <option value="No">No</option>
    </select>
  </div>
<div class="fm-opt">
    <label for="fm-addinfo">Additional information:</label>
    <textarea name="fm-addinfo" id="fm-addinfo" cols="40" rows="5"></textarea>
  </div>

<p><img src="quickcaptcha/imagebuilder.php" border="1">  </p>
      <?php echo @$_SESSION['ctform']['captcha_error'] ?>

      <p>Please enter the code shown above and click Submit.<br>
        <input MAXLENGTH=8 SIZE=8 name="userstring" type="text" value="">

<div id="fm-submit" class="fm-req">
<input name="Submit" value="Submit" type="submit"/>
<input name="Reset" value="Reset" type="reset"/>


</div>
ANY QUESTIONS<br>
CALL US 08456 777400<br>
Mon - Fri // 9am to 5pm</form>

</div>
</body>
</html>


<?php

// The form processor PHP code
function process_si_contact_form()
{
  $_SESSION['ctform'] = array(); // re-initialize the form session data

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && @$_POST['do'] == 'contact') {
  	// if the form has been submitted

    foreach($_POST as $key => $value) {
      if (!is_array($key)) {
      	// sanitize the input data
        if ($key != 'ct_message') $value = strip_tags($value);
        $_POST[$key] = htmlspecialchars(stripslashes(trim($value)));
      }
    }

    $name    		  = @$_POST['fm-name'];    		// name from the form
    $company 		  = @$_POST['fm-company'];		// Company name from the form
    $email   		  = @$_POST['fm-email'];   		// email from the form
    $telephone 		= @$_POST['fm-tel'];			// Telephone number from form
    $URL     		  = @$_POST['fm-website'];     	// url from the form
    $contact 		  = @$_POST['fm-contact'];		// contact method from the form
    $description  = @$_POST['fm-description'];    // the description from the form
    $manufacturer = @$_POST['fm-manufacturer'];
    $available = @$_POST['fm-available'];
    $message      = @$_POST['fm-addinfo'];    // the message from the form
    $captchastring = strtoupper($_SESSION['string']);
    $captcha      = strtoupper(@$_POST['userstring']);    // the user's entry for the captcha code
    $name    		  = substr($name, 0, 64);  		// limit name to 64 characters

    $errors = array();  // initialize empty error array

    if (isset($GLOBALS['DEBUG_MODE']) && $GLOBALS['DEBUG_MODE'] == false) {
      // only check for errors if the form is not in debug mode

      if (strlen($name) < 3) {
        // name too short, add error
        $errors['name_error'] = 'Your name is required';
      }

      if (strlen($company) < 3) {
        // company name too short, add error
        $errors['company_error'] = 'Your company name is required';
      }

      if (strlen($email) == 0) {
        // no email address given
        $errors['email_error'] = 'Email address is required';
      } else if ( !preg_match('/^(?:[\w\d]+\.?)+@(?:(?:[\w\d]\-?)+\.)+\w{2,4}$/i', $email)) {
        // invalid email format
        $errors['email_error'] = 'Email address entered is invalid';
      }

      if (($captchastring != $captcha) || (strlen($captchastring) < 5)) {
          $errors['captcha_error'] = 'Captcha code entered was invalid.';
      }

    }
/*
    // Only try to validate the captcha if the form has no errors
    // This is especially important for ajax calls
    if (sizeof($errors) == 0) {
      require_once dirname(__FILE__) . './captcha/securimage.php';
      $securimage = new Securimage();

      if ($securimage->check($captcha) == false) {
        $errors['captcha_error'] = 'Incorrect security code entered<br />';
      }
    }
*/
    if (sizeof($errors) == 0) {
      // no errors, send the form
      $time       = date('r');
      $message = "A message was submitted from the contact form.  The following information was provided.<br /><br />"
                    . "Name: $name<br />"
                    . "Company: $company<br />"
                    . "Email: $email<br />"
                    . "Tel: $telephone<br />"
                    . "Website: $URL<br />"
                    . "Preferred contact: $contact<br />"
                    . "Manufacturer: $manufacturer<br />"
                    . "Avalable now? $available<br />"
                    . "Brief description:<br />"
                    . "<pre>$description</pre>"
                    . "Additional information:<br />"
                    . "<pre>$message</pre>"
                    . "<br /><br />IP Address: {$_SERVER['REMOTE_ADDR']}<br />"
                    . "Time: $time<br />"
                    . "Browser: {$_SERVER['HTTP_USER_AGENT']}<br />";

      $message = wordwrap($message, 70);

      if (isset($GLOBALS['DEBUG_MODE']) && $GLOBALS['DEBUG_MODE'] == false) {
      	// send the message with mail()
      	$recipient=$GLOBALS['ct_recipient'];
      	$subject=$GLOBALS['ct_msg_subject'];
      	$headers="From: {$GLOBALS['ct_recipient']}\r\nReply-To: {$email}\r\nContent-type: text/html; charset=ISO-8859-1\r\nMIME-Version: 1.0";

      	mail($recipient, $subject, $message, $headers);
      	//echo "RECIPIENT:".$recipient."<br>SUBJECT: ".$subject."<br>MESSAGE: ".$message."<br>HEADERS: ".$headers;
      }


      $_SESSION['ctform']['error']   = false;  // no error with form
      $_SESSION['ctform']['success'] = true;   // message sent
    } else {
      // save the entries, this is to re-populate the form
      $_SESSION['ctform']['ct_name'] = $name;       // save name from the form submission
      $_SESSION['ctform']['ct_email'] = $email;     // save email
      $_SESSION['ctform']['ct_URL'] = $URL;         // save URL
      $_SESSION['ctform']['ct_message'] = $message; // save message

      foreach($errors as $key => $error) {
      	// set up error messages to display with each field
        $_SESSION['ctform'][$key] = "<span style=\"font-weight: bold; color: #f00\">$error</span>";
      }

      $_SESSION['ctform']['error'] = true; // set error floag
    }
  } // POST
}

$_SESSION['ctform']['success'] = false; // clear success value after running
