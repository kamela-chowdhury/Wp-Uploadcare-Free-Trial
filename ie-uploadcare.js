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
                $( "#reveal" ).css({ display: "block" });
                $('.form-show').css({ display: "block" });  
                $('.form-head').css({ display: "none" });
                $( "#reveal" ).css({ height: "100%" });
                $( ".upload-area" ).css({ display: "none" });
                // display file preview
                var $filename = fileInfo.name;// display file name
                var $fileurl = fileInfo.cdnUrl;// get file url
                var $src = fileInfo.cdnUrl + '-/resize/70x70/filename.jpg';// preview image source, resize to 100X100px and jpeg file type
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
    

$(document).ready(function() {
  
  $("form").keyup(function() {
    $("#bow").attr('disabled', 'disabled');
    var name = $("#fname").val();
    var email = $("#email").val();
    var phone = $("#phone").val();
    var description = $("#description").val();
    var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
    if (!(fname == "" || email == "" || phone == ""|| description == "")) {
      if (filter.test(email)) {
        $("#bow").removeAttr('disabled');
        $("#bow").css({
        "cursor": "pointer",
        "background": "#0d84c8",
        "color": "#ffffff"
        });
      }
    }      else if ((fname == "" || email == "" || phone == ""|| description == "")){$('#bow').attr('disabled', 'disabled');
    $("#bow").css({
  "cursor": "default",
  "background": "#f3f3f3"
  }); } 

});

});

$("form").submit(function(event) {

   var recaptcha = $("#g-recaptcha-response").val();
   if (recaptcha === "") {
      event.preventDefault();
      alert("Please check the I am human");
   }
   else{
        $("#bow").css({
          "cursor": "default",
          "background": "#0d84c8"
        });
        $( "#bow" ).click(function() {
            $( ".thumb_list" ).empty();
        });
        }
});
$('.close-btn').on('click touch',function () {
$( ".thumb_list" ).empty();
$('.pop-trial').css({ display: "none" });
$('.form-head').css({ display: "block" });
$( ".upload-area" ).css({ display: "block" });
});