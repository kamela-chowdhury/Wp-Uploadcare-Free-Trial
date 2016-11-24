<?php 
require 'vendor/autoload.php';
use \Uploadcare;

$sendgrid = new SendGrid('sendgrid api key');// sendgrid api key
$api = new Uploadcare\Api('uploadcare public key', 'uploadcare secret key');// uploadcare api key

$mail = new SendGrid\Email();// send email to ImageEditing
$mail2 = new SendGrid\Email();// send confirmation email to client

// Sender details
$name = "Sender name";// Sender name
$fname = $_POST['fname'];// Sender Name
$email = $_POST['email'];// Sender Email
$phone = $_POST['phone'];// Sender Telephone
$client_country = $_POST['client_country'];// Sender Country
$description = $_POST['description'];// Sender Message

$fileurl = $_POST['uploader'];//get files URL
$token = $_POST['token'];//get token to create Order Id

$group = $api->getGroup($fileurl);
$group->store();
$files = $group->getFiles();

$total = count($files);
$msg = "Free Trial Id: $token \nDear Super Admin\nA new free trial has been submitted on example.com.\n\nName: $fname\nEmail Address: $email\nTelephone: $phone \nCountry: $client_country.\n\nBrief: $description\n\nAll files download Link: $fileurl \n\n\n";


for($i = 0; $i < count($files); $i++) {
    $msg.= "Download Link: ".$files[$i]->getUrl()."\n";
    }
$msg .= "\n\nKind Regards \example.com \nSkype: example[24 hours] \nEmail:support@example.com";
$recipient ="example@example.com";
$subject = "$token - New Free Trial from ImageEditing";
// Confirmation reciever
$confirm_subject = "Free trial order submitted";
$confirm_msg = "Dear $fname,\nThank you for trying ImageEditing.Com free trial service. Your free trial Id: $token. \n\nWe will notify you when your order is ready.\n\nFor any support you can reply to this email or call us at 123456789.\n\nKind Regards \example \nVisit: www.example.com \nSkype: example[24 hours] \nEmail: support@example.com";

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
		exit(header('Location: http://example.com/thank-you/'));
	}
	else{
		echo "Order submission failed. Please check for error or report to support@example.com for Support.";
	}


?>


