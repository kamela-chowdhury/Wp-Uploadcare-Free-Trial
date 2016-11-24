<?php 
/**
 * Plugin Name: ImageEditing Free Trial
 * Plugin URI: https://github.com/kamela-peppylish/ImageEditing-Free-Trial
 * Description: This plugin adds a free trial form which allows user to upload images and submit to try services provided. The image uploading and handling is done using Uploadcare. Enjoy this plugin! 
 * Version: 1.0.0
 * Author: Kamela Chowdhury
 * Author URI: peppylish.com
 * License: GPL2
 */
require_once 'vendor/autoload.php';

function trial_form(){
?>
<script src="https://ucarecdn.com/widget/2.10.0/uploadcare/uploadcare.full.min.js" charset="utf-8">
</script>
    <script>
      // Widget settings
      UPLOADCARE_LOCALE = "en";
      UPLOADCARE_TABS = "file";
      UPLOADCARE_PUBLIC_KEY = "your_public_key";
      // Uploadcare script start
      $ = uploadcare.jQuery;
      // Create uploaded image list and append additional form fields to each item
      
      function installWidgetPreviewMultiple(widget, list) {
        widget.onChange(function(fileGroup) {

          var groupPromise = fileGroup.promise();
          groupPromise.done(function(fileGroupInfo) {
          // Upload successfully completed and all files in the group are ready.
          });
          list.empty();
          if (fileGroup) {
            $.when.apply(null, fileGroup.files()).done(function() {

              $.each(arguments, function(i, fileInfo) {
                
                // display file preview
                var $filename = fileInfo.name;// display file name
                var $fileurl = fileInfo.cdnUrl;// get file url
                var $src = fileInfo.cdnUrl + '-/resize/100x100/filename.jpg';// preview image source, resize to 100X100px and jpeg file type
                // append preview and name and form fields to each file uploaded inside thumb_list 
                list.append(
                  $('<li class="thumb_list_item"><img src="' + $src+ '" alt="File Preview" class="preview-img">' + '<h4 class="filename">' + $filename + '</h4></li>').appendTo(".thumb_list")
                  );
              });
            });
          }
        });
      }
    $(function() {
      $('.upload-area').each(function() {
        installWidgetPreviewMultiple(
          uploadcare.MultipleWidget($(this).children('input')),
          $(this).children('.thumb_list')
        );
      });
    });
    $( "#submit" ).click(function() {
      $( ".thumb_list" ).empty();
    });
    
    

    </script>
   

<link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(__FILE__);?>/trial-styles.css">
<!-- <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(__FILE__);?>/GeoLite2-Country.mmdb"> -->
<?php
$token = uniqid();
$output .='<div class="trial-form-wraper">     
            <div class="form-body">
                <form method="POST" action="'.plugin_dir_url(__FILE__).'mailer.php'.'" id="uc_form">
                <input type="hidden" name="token" value="'.$token.'">
                 <ul class="trial-form">
    <li class="trial-form-item trial-form-item-sm">
      <label class="trial-form-lbl">Full Name <span class="trial-must">*</span></label>
      <input type="text" name="fname" id="fname" class="trial-form-input" placeholder="Enter your full name" required>
    </li>
    </li>
    <li class="trial-form-item trial-form-item-sm">
      <label class="trial-form-lbl">Email <span class="trial-must">*</span></label>
      <input type="email" name="email" id="email" class="trial-form-input" placeholder="Enter your email address" required>
    </li>
    <li class="trial-form-item trial-form-item-sm">
      <label class="trial-form-lbl">Telephone </label>
      <input type="tel" name="phone" id="telephone" class="trial-form-input" placeholder="Enter your contact number">
    </li>
    <li class="trial-form-item trial-form-item-sm">
      <label class="trial-form-lbl">Country </label>
      <select class="trial-form-input" id="client_country" name="client_country">
              </select>
    </li>
    <li class="trial-form-item trial-form-item-full">
      <label class="trial-form-lbl">Brief Instruction <span class="trial-must">*</span></label>
      <textarea id="description" name="description" class="trial-form-txtbox trial-form-input" placeholder="Briefly write your instructions for this free order" required></textarea>
    </li>
    <li class="trial-form-item trial-form-item-full">
      <label class="trial-form-lbl">Upload files/images here (Maximum: 3). <span class="trial-must">*</span></label>
      <input type="hidden" id="uploader" name="uploader" role="uploadcare-uploader" data-multiple="true" data-multiple-max="3" title="Click here to upload your file or Drag and drop your files" required>

    </li>
    
    <li class="trial-form-item">
      <input type="submit" id="submit" class="submit-trial" value="Send My Free Trial Order"><!-- submit form -->
    </li>
  </ul>

                  <ul class="thumb_list">
                      
                  </ul>                   
                </form>
            </div>
    </div> ';

return $output;
}
add_shortcode('trial_form', 'trial_form');
?>
