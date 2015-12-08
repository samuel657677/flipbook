<?php
/*
Template Name: photoselect
 */

if ( !is_user_logged_in() ) {
	wp_redirect( get_bloginfo('siteurl') );
	exit;
}

	global $current_user;
	get_currentuserinfo();

	//$user_upload_path = get_template_directory() . "/uploader/" . $current_user->ID;

	/*
	if ( !is_dir($user_upload_path) )
		wp_mkdir_p($user_upload_path);
	*/

	$upload_handler = get_template_directory_uri() . '/uploader/index.php?user_id=' . $current_user->ID;
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<!-- Force latest IE rendering engine or ChromeFrame if installed -->
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<![endif]-->
<meta charset="utf-8">
<title>photoselect | Flip Book</title>
<meta name="description" content="File Upload widget with multiple file selection, drag&amp;drop support, progress bars, validation and preview images, audio and video for jQuery. Supports cross-domain, chunked and resumable file uploads and client-side image resizing. Works with any server-side platform (PHP, Python, Ruby on Rails, Java, Node.js, Go etc.) that supports standard HTML form file uploads.">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap styles -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/photoselect_style.css">
<!-- Generic page styles -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.css">
<!-- blueimp Gallery styles -->
<link rel="stylesheet" href="//blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/jquery.fileupload.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/jquery.fileupload-ui.css">
<!-- <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css"> -->
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/jquery.fileupload-ui-noscript.css"></noscript>
<script type="text/javascript">
	var upload_handler = "<?php echo $upload_handler ?>";
	var upload_dir = "<?php echo $user_upload_path ?>";
</script>
</head>
<body class="photo_body">
<div id="child_header" class="col-md-12">
	<?php //echo do_shortcode('[ezy_upload uploadtype="s" multiple_image_upload = "0"]'); ?>
	<div class="col-md-2"><center><a class="radius button success obtn button_form" id="import_photo-btn" style="font-size: 15px;"></a></center></div>
	<div class="col-md-8"><center><a href="<?php echo get_bloginfo('siteurl') ?>"><img src="<?php echo get_template_directory_uri() ?>/images/header_title.png" class="header_title"/></a></center></div>
	<div class="col-md-2" style="z-index:1000;">
        <center>                                                                                                    
            <button class="btn btn-warning make_btn" style="width:100px;">                
                <span style="font-size:20px;">Make</span>
            </button>  
        </center>
    </div>
</div>
<div>
    <h3 style="margin-left:10%;margin-top:108px;"> You have to click "Start" or "Start upload" button after "Add files..." and must import more than 30 photos! </h3>
</div>
<div class="import_photo_function_div">
    <!-- The file upload form used as target for the file upload widget -->
    <form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7" style="width:250px;">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Add files...</span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload" style="display:none;"></i>
                    <span>Start upload</span>
                </button>
                <button type="reset" class="btn btn-warning cancel" style="display:none;">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel upload</span>
                </button>
                <button type="button" class="btn btn-danger delete" style="display:none;">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" class="toggle" style="display:none;">
                <!-- The global file processing state -->
                <span class="fileupload-process"></span>
            </div>
            <!-- The global progress state -->
            <div class="col-lg-5 fileupload-progress fade" style="margin-top:135px;">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <div role="presentation" class="table table-striped"><div class="files"></div></div>
    </form>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <div class="template-upload fade" style="float:left;position:relative">
        <div style="width:150px;height:150px;margin-left:10px;margin-top:10px;">
            <span class="preview"></span>
        </div>
        <td>
            <p class="name" style="display:none;">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size" style="display:none;">Processing...</p>
            <div style="display:none;" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <div style="position:absolute;left:95px;top:15px;">
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload" style="display:none;"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel" style="display:none;">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </div>
    </div>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <div class="template-download fade import_img_div" style="float:left;position:relative;margin-left:10px;margin-top:10px;">
        <div>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.url%}" style="width:150px;height:150px;"></a>
                {% } %}
            </span>
        </div>
        <td>
            <p class="name" style="display:none;">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size" style="display:none;">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <div style="position:absolute;left:80px;top:2px;" class="delete_img_div">
            {% if (file.deleteUrl) { %}
                <button style="display:none" class="btn btn-danger delete delete_import_img" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"  style="display:none;"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="0" style="display:none;" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </div>
    </div>
{% } %}
</script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo get_template_directory_uri(); ?>/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="//blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!-- blueimp Gallery script -->
<script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="<?php echo get_template_directory_uri(); ?>/js/main1.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="js/cors/jquery.xdr-transport.js"></script>
<![endif]-->
<script type="text/javascript">
	$(document).ready(function(){
        
        var cnt = 0;       
		$("body").on("mouseover",function(e){
			var obj = $(e.target);
            cnt = 0;
			
			$('.import_img_div').find(".delete_img_div .delete_import_img").css("display","none");
			if (obj.closest('.import_img_div').length ) {
				obj.closest('.import_img_div').find(".delete_img_div .delete_import_img").css("display","block");
			}                 
            $(".import_img_div").each(function(){
                    cnt++;
            });     
            if(cnt<30 || cnt>320){
                $('.make_btn').attr("disabled","disabled");
            }else{
                  $('.make_btn').removeAttr('disabled');
            }
		});        

		$(".make_btn").click(function(){
            <?php if (isset($_GET['back']) && $_GET['back'] == '1'): ?>
            location.href = "<?php echo get_bloginfo('siteurl') ?>/photobook";
            <?php else: ?>
		    location.href = "<?php echo get_bloginfo('siteurl') ?>/temaselect";
            <?php endif; ?>
		});
	});
</script>
</body>
</html>
