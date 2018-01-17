<?php 
/**
 * Plugin Name: Free Trial
 * Plugin URI: https://github.com/kamela-peppylish/Wp-Uploadcare-Free-Trial
 * Description: This plugin adds a free trial form which allows user to upload images and submit to try services provided. The image uploading and handling is done using Uploadcare. Enjoy this plugin! 
 * Version: 2.0.0
 * Author: Kamela Chowdhury
 * Author URI: https://about.me/kamelac
 * License: GPL2
 */
require_once 'vendor/autoload.php';
include 'country-list.php';
function get_client_ip() {
     $ipaddress = '';
     if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
     else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
     else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
     else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
     else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
     else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
     else
        $ipaddress = 'UNKNOWN';
     return $ipaddress;
}

function ip_details($url) {
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
   $data = curl_exec($ch);
   curl_close($ch);

   return $data;
}
function trial_form(){
?>
<script src="https://ucarecdn.com/widget/2.10.0/uploadcare/uploadcare.full.min.js" charset="utf-8" defer></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__);?>ie-uploadcare.js" defer></script>
<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(__FILE__);?>trial-styles.css">
<script type="text/javascript">
  UPLOADCARE_LOCALE = "en";
  UPLOADCARE_TABS = "file";
  UPLOADCARE_PUBLIC_KEY = "your uploadcare public key";
</script>
<?php

$token = uniqid();
$output = null;

$myipd = get_client_ip(); 
$url = 'http://www.geoplugin.net/json.gp?ip='.$myipd; 
$details    =   ip_details($url); 
$v = json_decode($details);
$mycountry = $v->geoplugin_countryName;
$mycode = $v->geoplugin_countryCode;

function countrySelector($defaultCountry =""){
    global $countryArray; // Assuming the array is placed above this function
	$telcode=$country["code"];
	foreach($countryArray as $code => $country){
		if ( $code==strtoupper($defaultCountry) ){
		$countryName = ucwords(strtolower($country["name"])); // Making it look good
		$output .= "<input type='text' id='tel-code' name='tel-code' class='input-part tel-code' ".(($code==strtoupper($defaultCountry))?"value='(".$code.") +".$country["code"]."' ":"")."readonly>";
	}
	}
	return $output; // or echo $output; to print directly
}
$output .='
<form id="uc_form" action="'.plugin_dir_url(__FILE__).'mailer.php'.'" method="post" enctype="multipart/form-data">
<input type="hidden" name="token" value="'.$token.'">
<input type="hidden" name="country" value="'.$mycountry.'">
	<div class="my-trial">
		<div class="sec-trial">
			<a href="#" class="close-btn"> X</a>
			<div class="form-head">
				<p class="form-title">Try Us Free</p>
				<p class="form-txt">Let&apos;s upload maximum <strong> 3 images/files </strong>here to submit the trial.  </p>
				<div id="ie-uploader" class="upload-area"><input type="hidden" id="uploader" name="uploader" role="uploadcare-uploader" data-multiple="true" data-system-dialog="true" data-multiple-max="3" title="Click here to upload your file or Drag and drop your files" required></div>
			</div>
			<div class="form-show pop-trial">
				<p class="form-title">Upload Done!</p>
				<p class="form-txt">Now fill this form to complete your Free Trial Request.  </p>
				<ul>
					<li class="trial-form-item">
					<label class="trial-form-lbl" for="fname">Full Name <span class="trial-must">*</span></label>
					<input type="text" name="fname" id="fname" class="trial-form-input" placeholder="Enter your full name" required>
					</li>
					</li>
					<li class="trial-form-item">
					<label class="trial-form-lbl" for="email">Email <span class="trial-must">*</span></label>
					<input type="email" name="email" id="email" class="trial-form-input" placeholder="Enter your email address" required>
					</li>
					<li class="trial-form-item">
					<label class="trial-form-lbl" for="phone">Telephone <span class="trial-must">*</span></label>
					<div class="trial-form-input no-pad">'.countrySelector($mycode).'
					<input type="tel" name="phone" id="telephone" class="input-part tel-input" placeholder="Enter your contact number" required></div>
					</li>
					<div class="clear"></div>
					<li class="trial-form-item trial-form-item-full">
					<label class="trial-form-lbl" for="description">Brief Instruction <span class="trial-must">*</span></label>
					<textarea id="description" name="description" class="trial-form-txtbox trial-form-input" pattern="[a-zA-Z0-9-]" placeholder="Enter brief instruction here" required></textarea>
					</li>
					<li class="trial-form-item trial-form-item-full">
					<div class="g-recaptcha" data-sitekey="google recaptcha site key"></div></li>
					<li>
					<input type="submit" id="bow" class="bow-trial" value="Send My Free Trial Order">
					<p>By submitting this Free Trial you agree to our <a href="https://example.com/terms-of-use/" target="_blank"><span class="theme-txt-color">Terms of Use</span></a>.</span> </a> </p>
					</li>
				</ul>
				<ul class="thumb_list"></ul>
			</div>
		</div>
	</div>
</form>';
return $output;
}
add_shortcode('trial_form', 'trial_form');
?>
