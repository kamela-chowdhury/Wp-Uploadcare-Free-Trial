<?php 
require 'vendor/autoload.php';

$sendgrid = new SendGrid('Sendgrid API key');
$api = new Uploadcare\Api('Uploadcare public key', 'Uploadcare secret key');

$mail = new SendGrid\Email();// send email to ImageEditing
$mail2 = new SendGrid\Email();// send confirmation email to client

// Sender details
$token = $_POST['token'];//get token to create Order Id
// Sender details
$name = "example";// Sender name
$fname = $_POST['fname'];// Sender Name
$email = $_POST['email'];// Sender Email
$phone = $_POST['phone'];// Sender Telephone
$description = $_POST['description'];// Sender Message
$fileurl = $_POST['uploader'];//get files URL
$country = $_POST['country'];// Sender country
$telcode = $_POST['tel-code'];
$group = $api->getGroup($fileurl);
$group->store();
$files = $group->getFiles();

$total = count($files);

// recaptcha.
if(isset($_POST['g-recaptcha-response'])){
       $response=$_POST['g-recaptcha-response'];
     }else{echo 'not posted';}
$response = $_POST["g-recaptcha-response"];
	if(!$response){
          echo '<h2>Please check the captcha form.</h2>';
          exit;
        }
        $secretKey = "Google Recaptcha Key";
        $ip = $_SERVER['REMOTE_ADDR'];
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
        $responseKeys = json_decode($response,true);
        if(intval($responseKeys["success"]) !== 1) {
          echo '<h2>Uh oh, spammer alert. This is no place for a bot. Please try again as a human.</h2>';
        } 
$msg = "Free Trial Id: $token \r\n\r\nDear Admin,\r\n\r\nA new free trial has been submitted on example.\r\n\r\nName: $fname\r\n\r\nEmail: $email\r\n\r\nCountry: $country\r\n\r\nTelephone: $telcode\r\n\r\nEmail Address: $email\r\n\r\nTelephone: $phone \r\n\r\n\r\nBrief Instruction: $description\r\n\r\n";

	for($i = 0; $i < count($files); $i++) {
    $msg.= "Download Link: ".$files[$i]->getUrl()."\r\n\r\n";
    }

	$msg .= "You can download all files from this group link: $fileurl \r\n\r\nPlease review the instruction carefully and process the trial order accordingly.\r\n\r\nKind Regards, \r\n\r\ \r\n\r\nSkype: examplebd[24 hours] \r\n\r\nEmail: support@example.com";
	$recipient ="support@example.com";
	$subject = "$token - New Free Trial from example";
	// Confirmation reciever
	$confirm_subject = "Thank you for trying example";
	$confirm_msg = "Dear $fname,\r\n\r\nThank you for trying example Free Trial service. Your free trial Id: $token. \r\n\r\nWe will notify you when your order is ready.\r\n\r\nFor any support you can reply to this email or call us at 123456789.\r\n\r\nKind Regards, \r\n\r\example \r\n\r\nVisit: www.example.com\r\n\r\nEmail: support@example.com";

	// mail($recipient, $subject, $from, $mailheader) or die("Error!");
	$mail->
	addTo( $recipient )->
	setFromName($name)->
	setFrom( $email )->
    setSubject($subject)->
	setText($msg);
	// $mail2: Send Confirmation mail to client
	$mail2->
	addTo( $email )->
	setFromName($name)->
	setFrom( $recipient )->
    setSubject($confirm_subject)->
	setText($confirm_msg);
  	//Send Mail.
	if ( ($sendgrid->send($mail)) && ($sendgrid->send($mail2)) ){
		exit(header('Location: https://example.com/thankyou/'));
	}
	else{
		echo "Order submission failed. Please check for error or report to support@example.com for Support. For more, visit example.com";
	}
?>


