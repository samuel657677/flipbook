<?php
/*
Template Name: photobook
 */

get_header(); 

// jQuery
wp_enqueue_script('jquery');
// This will enqueue the Media Uploader script
wp_enqueue_media();


	global $current_user;
	get_currentuserinfo();

	$user_upload_path = get_template_directory() . "/uploader/files/" . $current_user->ID;


	$directory = $user_upload_path;
    $array_items = array();
	$image_count = 0;
	$file_name_array = array();

	// read upload files
    if ($handle = opendir($directory)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (!is_dir($directory. "/" . $file)) {
					$baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
					$array_items[] = $baseurl.$file;
					$file_name_array[] = $file;   
					$image_count++;
                }
            }
        }
        closedir($handle);
    }

	
	//read page count from file
	$_fp = fopen($directory."/pages/info.txt", "r") or die("Unable to open file!");
	$page_count = intval(fgets($_fp));
	$photobook_image_content = fgets($_fp);
	fclose($_fp);
    $real_page_count = $page_count;

	// set page count exactly
	if($page_count % 2 == 0) {
		$page_count /= 2;
	}else{
		$page_count = ($page_count+1)/2;
	}
	$page_count++;
	$selected_theme_index = 0;
    
?>
<!-- Liquidcarousel -->
<link type='text/css' rel='stylesheet' href='<?php echo get_template_directory_uri() ?>/css/liquidcarousel.css' />
<!-- Flip Book -->
<link rel="stylesheet" type="text/css" href='<?php echo get_template_directory_uri() ?>/bookblock_css/bookblock.css' /> 
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/photoselect_style.css">
<!-- Font Awesome -->
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" media="all" rel="stylesheet" type="text/css">
<style type="text/css">
.chip_image_div:active {cursor:move;}
</style>
<script src="<?php echo get_template_directory_uri() ?>/js/jquery.liquidcarousel.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/bookblock_js/modernizr.custom.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/bookblock_js/jquerypp.custom.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/bookblock_js/jquery.bookblock.js"></script>

<script type="text/javascript" >
	var ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
	var page_index = 0;
	var page_count = <?php echo $page_count; ?>;
    var real_page_count = <?php echo $real_page_count; ?>;
	var selected_obj_index = -1;
	var selected_obj_div = "";
	var selected_obj_dir = 0;
    var down_obj_div,down_obj_index = -1,down_obj_dir=-1,down_obj,down_obj_url,down_obj_name;
    var downX = -1,downY = -1, upX = -1, upY = -1;
    var text_dir = -1;
	jQuery(document).ready(function($) {    
        var objBookblock = $('#bb-bookblock');
        objBookblock.bookblock( {
            speed : 800,
            shadowSides : 0.8,
            shadowFlip : 0.7
        } );                                    
        $('#create_page_btn').removeAttr("disabled");
        $('#delete_page_btn').removeAttr("disabled");
        if(page_count<=13){               
            $('#delete_page_btn').attr("disabled","disabled");
        }
        else if(page_count>=41){             
            $('#create_page_btn').attr("disabled","disabled");    
        }
                                              
    //CREATE A PAGE button is clicked...
    
        $('#create_page_btn').click(function(){  
            down_obj_dir = -1;
            $('.search_overlay').show();
            var data = {
                'action': 'create_page'
            };

            
            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajax_url, data, function(response) {
                if(real_page_count%2==0){
//                    $('.main_photobook_content').append(response);
                    $('.bb-item:last').remove();
//                    $('.bb-item').each(function(){
//                        $(this).hide();
//                    });
                    $('#bb-bookblock').append(response);
                    $('#bb-bookblock').append('<div class="bb-item" style="display: none;"><div class="photobook_image_back"></div></div>');
                }else{                          
//                    $('.bb-item').each(function(){
//                        $(this).hide();
//                    });
//                    $('.bb-item:last').prev().show();
                    $('.bb-item:last').prev().find(".main_photobook_content").append(response);
                }                      
                real_page_count++; 
                page_count = real_page_count;   
                // set page count exactly
                if(page_count % 2 == 0) {
                    page_count /= 2;
                }else{
                    page_count = (page_count+1)/2;
                }
                page_count++;          
                page_index = page_count-1;     
                objBookblock = $('#bb-bookblock');
                objBookblock.bookblock( {
                    speed : 800,
                    shadowSides : 0.8,
                    shadowFlip : 0.7
                } ); 
                objBookblock.bookblock( 'prev_last'); 
                $('#change_txt').html("Change Layout");                     
                                                    
                $('#create_page_btn').removeAttr("disabled");
                $('#delete_page_btn').removeAttr("disabled");
                if(page_count<=13){               
                    $('#delete_page_btn').attr("disabled","disabled");
                }
                else if(page_count>=41){             
                    $('#create_page_btn').attr("disabled","disabled");    
                }
                $('.search_overlay').hide();
            });
        });
    
    //Page Arrange Button is clicked...
        $('.page_arrange_btn').click(function(){
            location.href = "<?php echo get_bloginfo('siteurl') ?>/temaselect";
        });
    //Change Theme Button is clicked...
        $('#change_theme_btn').click(function(){
            location.href = "<?php echo get_bloginfo('siteurl') ?>/temaselect";
        });
    //Upload More Photos Button is clicked...
        $('#upload_more_photos_btn').click(function(){
            location.href = "<?php echo get_bloginfo('siteurl') ?>/photoselect?back=1";
        });
    
    //Add Text Button is clicked...
        $('#add-text').click(function(){
            if(page_index == page_count){
                $('#text_warning_modal').modal("show");
            }else if(page_index == 0 || down_obj_dir != -1){
                text_dir = down_obj_dir;
                var add_text_flag = 1;
                if(page_index == 0){
                    if($('.main_photobook .chip_text_div h5').html() != "") add_text_flag = 0;
                }else if(text_dir == 1){
                    var main_photobook1;
                    var i=1;
                    $(".main_photobook1").each(function(){
                        if(i==page_index){
                            main_photobook1 = $(this);
                        }
                        i++;
                    });
                    if(main_photobook1.find(".chip_text_div h5").html() != "") add_text_flag = 0;         
                }else if(text_dir == 0){
                    var main_photobook2;
                    var i=1;
                    $(".main_photobook2").each(function(){
                        if(i==page_index){
                            main_photobook2 = $(this);
                        }
                        i++;
                    });
                    if(main_photobook2.find(".chip_text_div h5").html() != "") add_text_flag = 0;                                            
                }
                
                if(add_text_flag == 1){
                    $('#delete_text_btn').hide();
                    $('#add_textarea').val("");
                    $('#text_modal').modal("show");
                }else{
                    $('#already_modal').modal("show");
                }                               
            }else{
                $('#warning_modal').modal("show");
            }
            $(".chip_image").css("border","none");
        });
        
    //Delete Page Button is clicked...
    
        $('#delete_page_btn').click(function(){
            if(page_index == 0 || page_index == page_count){
                $('#delete_warning_modal').modal("show");
            }else if(down_obj_dir != -1){            
                $('#delete_modal').modal("show");
            }else{
                $('#warning_modal').modal("show");
            }
            $(".chip_image").css("border","none");
        });
        
    // add navigation events
    
        //when next button is clicked
        $('#next-btn').on( 'click touchstart', function() {
            $('.rmd_button').hide();
            if (!$('.photobook_image_cover').hasClass('moveright')){
                $('.photobook_image_cover').addClass('moveright');
            }
            down_obj_dir = -1;
            $('.more_options_div').slideUp(); 
            objBookblock.bookblock( 'next' );
            selected_obj_index = -1;
            if(page_index < page_count){
                page_index++;
                $('#change_txt').html("Change Layout");   
                $('.photobook_image_content').css("background-image",'url('+'<?php echo get_template_directory_uri()."/images/".$photobook_image_content; ?>'+')');
            }                           
            return false;
        } );

        //when prev button is clicked
        $('#prev-btn').on( 'click touchstart', function() {
            $('.rmd_button').hide();
            down_obj_dir = -1;
            $('.more_options_div').slideUp(); 
            objBookblock.bookblock( 'prev' );
            selected_obj_index = -1;
            if(page_index > 0){
                page_index--;
                if(page_index == 0){                    
                    $('#change_txt').html("Change Cover");
                    $('.photobook_image_content').css("background-image",'url('+'<?php echo get_template_directory_uri()."/images/".$photobook_image_content; ?>'+')');
                    if ($('.photobook_image_cover').hasClass('moveright')){
                        $('.photobook_image_cover').removeClass('moveright');
                    }
                }                                                           
            }
            return false;
        } );

        //when first button is clicked
        $('#first-btn').on( 'click touchstart', function() {
            $('.rmd_button').hide();
            down_obj_dir = -1;
            $('.more_options_div').slideUp(); 
            objBookblock.bookblock( 'first' );
            selected_obj_index = -1;
            page_index = 0;                           
            $('#change_txt').html("Change Cover");
            return false;
        } );

        //when last button is clicked
        $('#bb-nav-last').on( 'click touchstart', function() {
            $('.rmd_button').hide();
            down_obj_dir = -1;
            $('.more_options_div').slideUp(); 
            objBookblock.bookblock( 'last' );
            return false;
        } );
        
		$('#liquid1').liquidcarousel({height:129, duration:100, hidearrows:false});

		// click functions...
		$('body').on('click', function(e) {
			var obj = $(e.target);
            
            if (obj.closest('.chip_image').length) {}
            else if(obj.closest('#add-text')){}
            else{
                console.log('asdfasd');
                down_obj_dir = -1;
            }
            
            //when Delete yes button is clicked...
            
            if(obj.closest('#delete_yes_btn').length){                               
                var page_dir = down_obj_dir;
                    
                $('.search_overlay').show();
                var data = {
                    'action': 'delete_page',
                    'page_index' : page_index,    
                    'page_dir' : page_dir           
                };                                                        
                jQuery.post(ajax_url, data, function(response) {
                    $('#bb-bookblock').html(response);
                    $('.search_overlay').hide();
                    objBookblock = $('#bb-bookblock');
                    objBookblock.bookblock( {
                        speed : 800,
                        shadowSides : 0.8,
                        shadowFlip : 0.7
                    } ); 
                    objBookblock.bookblock( 'first'); 
                    real_page_count--; 
                    page_count = real_page_count;   
                    // set page count exactly
                    if(page_count % 2 == 0) {
                        page_count /= 2;
                    }else{
                        page_count = (page_count+1)/2;
                    }
                    page_count++;           
                    page_index = 0;                       
                    $('#delete_modal').modal("hide"); 
                });                                       
            }
            
            //when more options button is clicked
            if(obj.closest('#more-options').length){
                $('.more_options_div').slideToggle();
            }else{
                $('.more_options_div').slideUp();    
            }
            
            //when the text is clicked on the certain page
            if(obj.closest('.chip_text_div').length){
                down_obj_dir = -1;
                var chip_text_div = obj.closest('.chip_text_div');
                text_dir = chip_text_div.data("text_dir");
                var chip_text = chip_text_div.find("h5").html();
                $('#delete_text_btn').show();
                $('#add_textarea').val(chip_text);
                $('#text_modal').modal("show");
            }
            
            //when delete button is clicked on Add Text Modal
            if(obj.closest('#delete_text_btn').length){                                    
                
                $('.search_overlay').show();
                var data = {
                    'action': 'text_delete',
                    'page_index' : page_index,    
                    'text_dir' : text_dir
                };
                
                if(page_index == 0){
                    jQuery.post(ajax_url, data, function(response) {
                        $('.main_photobook .chip_text_div').css("display","none");
                        $('.main_photobook .chip_text_div h5').html("");
                        $('.search_overlay').hide();
                    });
                }else if(text_dir == 1){
                    var main_photobook1;
                    var i=1;
                    $(".main_photobook1").each(function(){
                        if(i==page_index){
                            main_photobook1 = $(this);
                        }
                        i++;
                    });                          
                    jQuery.post(ajax_url, data, function(response) {
                        main_photobook1.find(".chip_text_div").css("display","none");
                        main_photobook1.find(".chip_text_div h5").html("");
                        $('.search_overlay').hide();
                    });
                }else if(text_dir == 0){
                    var main_photobook2;
                    var i=1;
                    $(".main_photobook2").each(function(){
                        if(i==page_index){
                            main_photobook2 = $(this);
                        }
                        i++;
                    });                                                         
                    jQuery.post(ajax_url, data, function(response) {
                        main_photobook2.find(".chip_text_div").css("display","none");
                        main_photobook2.find(".chip_text_div h5").html("");
                        $('.search_overlay').hide();
                    });                                      
                }
                
                $('#text_modal').modal("hide");
                text_dir = -1;
            }
            
            
            
            //when Save changes button is clicked (after Add Text Button)
            
            if(obj.closest('#Save_changes_btn').length){                     
                var add_text = $('#add_textarea').val();
                if(add_text != ""){
                    if(page_index == 0) text_dir = 0;                                
                    
                    $('.search_overlay').show();
                    var data = {
                        'action': 'text_create',
                        'page_index' : page_index,    
                        'text_dir' : text_dir,
                        'add_text' : add_text
                    };
                    
                    if(page_index == 0){
                        jQuery.post(ajax_url, data, function(response) {
                                $('.main_photobook .chip_text_div').css("display","table");
                                $('.main_photobook .chip_text_div h5').html(add_text);                
                            $('.search_overlay').hide();
                        });
                    }else if(text_dir == 1){
                        var main_photobook1;
                        var i=1;
                        $(".main_photobook1").each(function(){
                            if(i==page_index){
                                main_photobook1 = $(this);
                            }
                            i++;
                        });                          
                        jQuery.post(ajax_url, data, function(response) {
                            main_photobook1.find(".chip_text_div").css("display","table");
                            main_photobook1.find(".chip_text_div h5").html(add_text);
                            $('.search_overlay').hide();
                        });
                    }else if(text_dir == 0){
                        var main_photobook2;
                        var i=1;
                        $(".main_photobook2").each(function(){
                            if(i==page_index){
                                main_photobook2 = $(this);
                            }
                            i++;
                        });                                                         
                        jQuery.post(ajax_url, data, function(response) {
                            main_photobook2.find(".chip_text_div").css("display","table");
                            main_photobook2.find(".chip_text_div h5").html(add_text);
                            $('.search_overlay').hide();
                        });                                      
                    }                                    
                }else{
                    alert("You have to write more than 1 character!");
                }
                $('#text_modal').modal("hide");
                                                 
                text_dir = -1;        
            }
            
			//when change_layout button is clicked ...
			if(obj.closest('#change_layout').length){    
                down_obj_dir = -1;
				selected_obj_index = -1; 
				if(page_index == 0){
                    $('.search_overlay').show();
					var data = {
						'action': 'change_cover',
						'page_index' : page_index
					};

					// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
					jQuery.post(ajax_url, data, function(response) {
						$( ".main_photobook" ).html(response);
                        $('.search_overlay').hide();
					});
				}else if(page_index<page_count){
                    console.log(page_index);
                    $('.search_overlay').show();
					var data = {
						'action': 'change_layout',
						'page_index' : (page_index*2-1)
					};

                    var main_photobook1;
                    var i=1;
                    $(".main_photobook1").each(function(){
                        if(i==page_index){
                            main_photobook1 = $(this);
                        }
                        i++;
                    });           
                    console.log(main_photobook1.html());
					// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
					jQuery.post(ajax_url, data, function(response) {
						main_photobook1.html(response);
                        $('.search_overlay').hide();
					});
					
					if(page_index*2 <= real_page_count){
                        var main_photobook2;
                        var i=1;
                        $(".main_photobook2").each(function(){
                            if(i==page_index){
                                main_photobook2 = $(this);
                            }
                            i++;
                        });
						var data = {
							'action': 'change_layout',
							'page_index' : (page_index*2)
						};

						// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
						jQuery.post(ajax_url, data, function(response) {
							main_photobook2.html(response);
                            $('.search_overlay').hide();
						});
					}
				}                                  
			}
			
			//when flipbook image is clicked ...
			else if (obj.closest('.chip_image').length ) {
				$('.chip_image').css('border','none');
				obj.css("border","5px solid red");
				url = obj.attr('src');
				selected_obj_div = obj.closest('.chip_image').closest('.chip_image_div');
				selected_obj_index = obj.data("image_index");
				selected_obj_dir = obj.data("image_dir");
			}
			
			//when rotate button is clicked ...
			else if (obj.closest('#rotate-btn').length ) {   
                down_obj_dir = -1;
				if(selected_obj_index != -1){
                    $('.search_overlay').show();
					var data = {
						'action': 'image_rotate',
						'page_index' : page_index,
						'selected_obj_index' : selected_obj_index,
						'selected_obj_dir' : selected_obj_dir
					};

					
					// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
					jQuery.post(ajax_url, data, function(response) {
						selected_obj_div.html(response);
                        $('.search_overlay').hide();
					});
				}
			}
            
            //when move button is clicked ...
            else if (obj.closest('#move-btn').length ) { 
                down_obj_dir = -1;
                if(selected_obj_index != -1){
                    $('.search_overlay').show();
                    var data = {
                        'action': 'image_move',
                        'page_index' : page_index,
                        'selected_obj_index' : selected_obj_index,
                        'selected_obj_dir' : selected_obj_dir
                    };

                    
                    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                    jQuery.post(ajax_url, data, function(response) {
                        selected_obj_div.html(response);
                        $('.search_overlay').hide();
                    });
                }
            }
			
			//when delete button is clicked...
			else if (obj.closest('#delete-btn').length ) {
                $('.rmd_button').hide();
                down_obj_dir = -1;
				if(selected_obj_index != -1){
                    $('.search_overlay').show();
					var data = {
						'action': 'image_delete',
						'page_index' : page_index,
						'selected_obj_index' : selected_obj_index,
						'selected_obj_dir' : selected_obj_dir
					};

					// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
					if(page_index == 0){
						jQuery.post(ajax_url, data, function(response) {
							if(response!=""){
								$( ".main_photobook" ).html(response);
							}else{
								alert("We cannot remove the last photo from this spread.");
							}
                            $('.search_overlay').hide();
						});
					}else if(selected_obj_dir == 1){
                        var main_photobook1;
                        var i=1;
                        $(".main_photobook1").each(function(){
                            if(i==page_index){
                                main_photobook1 = $(this);
                            }
                            i++;
                        });  
						jQuery.post(ajax_url, data, function(response) {
							if(response!=""){
								main_photobook1.html(response);
							}else{
								alert("We cannot remove the last photo from this spread.");
							}
                            $('.search_overlay').hide();
						});
					}else if(selected_obj_dir == 0){
                        var main_photobook2;
                        var i=1;
                        $(".main_photobook2").each(function(){
                            if(i==page_index){
                                main_photobook2 = $(this);
                            }
                            i++;
                        });
						jQuery.post(ajax_url, data, function(response) {
							if(response!=""){
								main_photobook2.html(response);
							}else{
								alert("We cannot remove the last photo from this spread.");
							}
                            $('.search_overlay').hide();
						});
					}
				}
			}
		});                                             

		//mousedown event
		$('body').on('mousedown', function(e) {
			var obj = $(e.target);

			//when you down flipbook image using mouse...
			if (obj.closest('.chip_image').length) {
				$('.chip_image').css('border','none');
				obj.css("border","5px solid red");
				selected_obj_div = obj.closest('.chip_image').closest('.chip_image_div');
				selected_obj_index = obj.data("image_index");
				selected_obj_dir = obj.data("image_dir");

				down_obj = obj.closest('.chip_image');
				down_obj_div = obj.closest('.chip_image').closest('.chip_image_div');
				down_obj_index = obj.data("image_index");
				down_obj_dir = obj.data("image_dir");
				down_obj_url = down_obj.css("background-image");
                $('#move_image').css("display","none");
                $('#move_image').css("left","-100px");
                $('#move_image').css("top","-100px");

				$('#move_image').css("background-image",down_obj_url);                
				downX = e.pageX;
				downY = e.pageY;
			}
			
			//when you down bottom image...
			else if (obj.closest('.bottom_image').length) {

				down_obj = obj.closest('.bottom_image');
				down_obj_url = down_obj.css("background-image");
				down_obj_name = down_obj_url;                                      
                var cleanup = /\"|\'|\)/g;
                down_obj_name = down_obj_name.replace(/"/g,"").replace(/url\(|\)$/ig, "");
                down_obj_name = down_obj_name.substring(down_obj_name.lastIndexOf("/") + 1);
                if(down_obj_name.slice(-1) == "'"){                                 
                    down_obj_name = down_obj_name.substring(0,down_obj_name.length-1);
                }                          
				down_obj_index = 1000;

				$('#move_image').css("background-image",down_obj_url);
                $('#move_image').css("display","none");
                $('#move_image').css("left","-100px");
                $('#move_image').css("top","-100px");                               
				downX = e.pageX;
				downY = e.pageY;
			}
		});
		
		//mouseup event
		$('body').on('mouseup', function(e) {

			upX = e.pageX;
			upY = e.pageY;

			var obj = $(e.target);
			var up_obj_div;

			//if you down flipbook image...
			if(down_obj_index != -1){
				if(downX == upX && downY == upY && down_obj_index!=1000){
					down_obj.css("border","5px solid red");
				}else{
					$('.chip_image_div').css("cursor","pointer");
					down_obj.css("border","none");
					selected_obj_index = -1;
				}
                //for your flipbook image...
                $('.chip_image').each(function(){    

                    //getting certain flipbook image angle
                    var matrix = $(this).css("-webkit-transform") ||
                                $(this).css("-moz-transform")    ||
                                $(this).css("-ms-transform")     ||
                                $(this).css("-o-transform")      ||
                                $(this).css("transform");
                    var angle;
                    if(matrix !== 'none') {
                        var values = matrix.split('(')[1].split(')')[0].split(',');
                        var a = values[0];
                        var b = values[1];
                        angle = Math.round(Math.atan2(b, a) * (180/Math.PI));
                    } else { angle = 0; }
                    angle = (angle+360)%360;
                    var offset_width = $(this).width();
                    var offset_height = $(this).height();
                    if(angle % 180 == 90){
                        offset_width = $(this).height();
                        offset_height = $(this).width();
                    }                                            
                    //if your mouse_pos on certain flipbook image...
                    if($(this).offset().left!=0 && upX >= $(this).offset().left && upY >= $(this).offset().top && upX <= $(this).offset().left + offset_width && upY <= $(this).offset().top + offset_height){
                                                                                                                                                                                                    
                        up_obj_dir = $(this).data('image_dir');
                        up_obj_index = $(this).data('image_index');            
                        up_obj_div = $(this).closest('.chip_image_div');
                        up_obj = $(this).closest('.chip_image');   
                        
//                        console.log($(this).offset.left);
//                        console.log(offset_width+":"+offset_height);
//                        console.log(angle+" "+upX+":"+upY+" "+$(this).offset().left+":"+$(this).offset().top+" "+($(this).offset().left + offset_width)+":"+($(this).offset().top + offset_height));
//                        console.log(up_obj_dir+" "+up_obj_index+" "+up_obj_div+" "+up_obj);                          
//                        console.log(up_obj_div.html());        

                        //if you select flipbook image...
                        if(down_obj_dir == up_obj_dir && down_obj_index != up_obj_index && down_obj_index != 1000){
                            $('.search_overlay').show();
                            var data = {
                                'action': 'image_change',
                                'page_index' : page_index,
                                'down_obj_index' : down_obj_index,
                                'down_obj_dir' : down_obj_dir,
                                'up_obj_index' : up_obj_index
                            };                    

                            if(page_index == 0){
                                jQuery.post(ajax_url, data, function(response) {
                                    $('.main_photobook').html(response);
                                    $('.search_overlay').hide();
                                });
                            }else if(down_obj_dir == 1){
                                var main_photobook1;
                                var i=1;
                                $(".main_photobook1").each(function(){
                                    if(i==page_index){
                                        main_photobook1 = $(this);
                                    }
                                    i++;
                                });                          
                                jQuery.post(ajax_url, data, function(response) {
                                    main_photobook1.html(response);
                                    $('.search_overlay').hide();
                                });
                            }else if(down_obj_dir == 0){
                                var main_photobook2;
                                var i=1;
                                $(".main_photobook2").each(function(){
                                    if(i==page_index){
                                        main_photobook2 = $(this);
                                    }
                                    i++;
                                });                                                         
                                jQuery.post(ajax_url, data, function(response) {
                                    main_photobook2.html(response);
                                    $('.search_overlay').hide();
                                });                                      
                            }
                            down_obj_dir = -1;
                        }else if(down_obj_dir != up_obj_dir && down_obj_index != 1000){                            
                            $('.search_overlay').show();
                            var data = {
                                'action': 'image_exchange',
                                'page_index' : page_index,
                                'down_obj_index' : down_obj_index,
                                'down_obj_dir' : down_obj_dir,
                                'up_obj_index' : up_obj_index,
                                'up_obj_dir' : up_obj_dir
                            };                                       

                            if(page_index != 0){
                                var main_photobook_content;
                                var i=1;
                                $(".main_photobook_content").each(function(){
                                    if(i==page_index){
                                        main_photobook_content = $(this);
                                    }
                                    i++;
                                });    
                                                      
                                jQuery.post(ajax_url, data, function(response) {
                                    main_photobook_content.html(response);
                                    $('.search_overlay').hide();
                                });
                            }
                            down_obj_dir = -1;
                        }
                        //if you select bottom image...         
                        else if(down_obj_index == 1000){                                           
                            $('.search_overlay').show();                 
    //                        var replace_pos = down_obj_name.search("%20");
    //                        tmp_str = down_obj_name;
    //                        console.log(down_obj_name);
    //                        down_obj_name = tmp_str.substring(0,replace_pos);
    //                        down_obj_name += " ";
    //                        down_obj_name += tmp_str.substring(replace_pos+3,tmp_str.length);
    //                        console.log(down_obj_name); 
//                            console.log(page_index+" "+up_obj_dir+" "+up_obj_index+" "+down_obj_name);
                            var data = {
                                'action': 'image_create',
                                'page_index' : page_index,
                                'up_obj_dir' : up_obj_dir,
                                'up_obj_index' : up_obj_index,
                                'image_name' : down_obj_name
                            };
                            jQuery.post(ajax_url, data, function(response) {
                                up_obj_div.html(response);
                                $('.search_overlay').hide();
                            });
                            down_obj_dir = -1;
                        }                       
                    }
                });
                
			}
			
			downX = -1;
			downY = -1;
			down_obj_index = -1;
//			down_obj_dir=-1;
			$('#move_image').css("display","none");
            $('#move_image').css("left","-100px");
            $('#move_image').css("top","-100px");
		});

		//mousemove event...
		$('body').on('mousemove', function(e) {
			var obj = $(e.target);
			if(down_obj_index != -1){
				//if you select flipbook image...
				if(down_obj_index != 1000){                        
//                    if(e.pageX != downX && e.pageY != downY){
                        obj.closest('.chip_image_div').css("cursor","move");
                        down_obj.css("border","5px solid green");
                        $('#move_image').css("display","block");
                        $('#move_image').css("left",e.pageX-$('#move_image').width()/2);
                        $('#move_image').css("top",e.pageY-$('#move_image').height()/2);
//                        console.log(e.pageX+":"+downX);
//                    }                                                                    
				}
				//if you select bottom image...
				else{                       
//                    if(e.pageX != downX && e.pageY != downY){
					    down_obj.css("border","5px solid green");
					    $('#move_image').css("display","block");
					    $('#move_image').css("left",e.pageX-$('#move_image').width()/2);
					    $('#move_image').css("top",e.pageY-$('#move_image').height()/2);
//                    }
				}
			}else{
				obj.closest('.chip_image_div').css("cursor","pointer");
			}
		});
	});
</script>
<div id="no_selection">       
<!--Already Text Modal-->
<div class="modal fade" id="text_warning_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Warning</h4>
      </div>
      <div class="modal-body">
        <h3>You can't add the text on this page!</h3>
      </div>
      <div class="modal-footer">                                                                               
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>              
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
 <!--Already Text Modal-->
 <!--Already Text Modal-->
<div class="modal fade" id="delete_warning_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Warning</h4>
      </div>
      <div class="modal-body">
        <h3>You can't delete this page!</h3>
      </div>
      <div class="modal-footer">                                                                               
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>              
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
 <!--Already Text Modal-->
<div class="modal fade" id="delete_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Warning</h4>
      </div>
      <div class="modal-body">
        <h3>Do you really delete this page?</h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="delete_yes_btn" data-dismiss="modal">Yes</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>              
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--Already Text Modal-->
<div class="modal fade" id="already_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <h3>You have already added the text!<br/> Select the text please...</h3>
      </div>
      <div class="modal-footer">                                                                                  
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>            
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--Select the page Modal-->
<div class="modal fade" id="warning_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <h2>Select the page please!</h2>
      </div>
      <div class="modal-footer">                                                                                  
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>            
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--Add Text Modal-->
<div class="modal fade" id="text_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Enter your text</h4>
      </div>
      <div class="modal-body">
        <textarea cols="78" rows="10" id="add_textarea"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="delete_text_btn" data-dismiss="modal">Delete</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="Save_changes_btn">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="search_overlay"><i class="fa fa-spinner fa-spin"></i></div>
<div id="move_image" style="position:absolute;width:100px;height:100px;background-size:cover;z-index:1000;">
</div>
<div id="child_header1" class="col-md-12">
	<div class="col-md-3">
        <center>
            <a style="cursor:pointer;">
                <button class="btn btn-success page_arrange_btn" style="width:200px;height:50px;">                
                    <span style="font-size:15px;">Page Arrange</span>
                </button>  
            </a>
        </center>
    </div>
	<div class="col-md-5"><center><a href="<?php echo get_bloginfo('siteurl') ?>"><img class="btn_image" src="<?php echo get_template_directory_uri() ?>/images/header_title.png" class="header_title"/></a></center></div>
	<div class="col-md-2">
        <center>
            <a style="cursor:pointer;">
                <button class="btn btn-success saving_box_btn" style="width:180px;height:50px;">                
                    <span style="font-size:15px;">Saving Box</span>
                </button>  
            </a>
        </center>
    </div>
	<div class="col-md-2">
        <center>
            <a style="cursor:pointer;">
                <button class="btn btn-primary cofirm_btn" style="width:120px;height:50px;">                
                    <span style="font-size:15px;">Confirm</span>
                </button> 
            </a>
        </center>
    </div>
</div>

<div class="rmd_button">
    <a href="#" id="rotate-btn"><i class="fa fa-refresh"></i></a>
    <a href="#" id="delete-btn"><i class="fa fa-trash-o"></i></a>    
    <!--<div id="rotate-btn">         
        <button style="padding:0;border-color:#e8e8e8;">
            <img src="<?php echo get_template_directory_uri() ?>/images/rotate.png"/>
        </button>
    </div>
    <div id="move-btn">              
        <button style="padding:0;border-color:#e8e8e8;">
            <img src="<?php echo get_template_directory_uri() ?>/images/move.png"/>         
        </button>
    </div>
    <div id="delete-btn">              
        <button style="padding:0;border-color:#e8e8e8;">
            <img src="<?php echo get_template_directory_uri() ?>/images/delete.png"/>           
        </button>
    </div>-->
</div>
<div class="photobook">
	<div class="photobook_function">
        <div id="bb-bookblock" class="bb-bookblock">
            <!-- Cover Page -->   
            <div class="bb-item">                    
                <div class="photobook_image_cover">
                    <div class="main_photobook">
                        <?php 

                        //display flipbook image...

                            $theme_width = FLIPBOOK_WIDTH*80/100;
                            $theme_height = FLIPBOOK_HEIGHT;
                            $img_interval = $theme_height/20;
                            $border_radius = $theme_height/10;
                            $border_size = $theme_height/20;
                            $marginLeft = $theme_height/2;
                            $marginBottom = $theme_height/2;
                            $_fp = fopen($directory."/pages/".$selected_theme_index.".txt", "r") or die("Unable to open file!");
                            $count = intval(fgets($_fp));
                            for($i=0; $i<$count; $i++){
                                $line = fgets($_fp);
                                $numbers = explode(" ", $line);
                                $baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
                                $img_left = $theme_width*floatval($numbers[0])/100;
                                $img_top = $theme_height*floatval($numbers[1])/100+$img_interval;
                                $img_width = $theme_width*floatval($numbers[2])/100;
                                $img_height = $theme_height*floatval($numbers[3])/100;
                                $backgroud_postion  = " background-position-x:".$numbers[5]."px";

                                if($numbers[4] % 180 == 90){
                                    $img_width = $theme_height*floatval($numbers[3])/100;
                                    $img_height = $theme_width*floatval($numbers[2])/100;
                                    $img_left = $theme_width*floatval($numbers[0])/100 - ($img_width - $img_height)/2;
                                    $img_top = $theme_height*floatval($numbers[1])/100 + ($img_width - $img_height)/2 + $img_interval;
                                    $backgroud_postion  = " background-position-y:".$numbers[5]."px;";
                                }
                                
                                $chip_file_name = $numbers[6];
                                $back_index = 7;
                                if(strlen($numbers[$back_index])){
                                    $chip_file_name .= " ".$numbers[$back_index];
                                    $back_index++;
                                }

                                $html = '<a style="cursor:pointer;" class="chip_image_div"><div class="chip_image" ';
                                $html .=  'style="position:absolute;background-image:url(\''.$baseurl.substr($chip_file_name,0,-1).'\'); background-size:cover; left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px; transform:rotate('.floatval($numbers[4]).'deg);'.$backgroud_postion.'" data-image_index="'.$i.'" data-image_dir="'.'0'.'"';
                                $html .='></div></a>';
                                echo $html;
                            }
                            fclose($_fp);
                        
                        //Display Flipbook Text...
                            $_fp = fopen($directory."/pages/text/".$selected_theme_index.".txt", "r") or die("Unable to open file!");
                            $count = intval(fgets($_fp)); 
                            $line = fgets($_fp);
                            $numbers = explode(" ", $line);
                            $baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
                            $img_left = $theme_width*floatval($numbers[0])/100;
                            $img_top = $theme_height*floatval($numbers[1])/100+$img_interval;
                            $img_width = $theme_width*floatval($numbers[2])/100;
                            $img_height = $theme_height*floatval($numbers[3])/100;               
                            
                            $chip_text = "";
                            
                            while(!feof($_fp)) {
                                $chip_text .= fgets($_fp);
                            }
                               
                            $html = '<table class="chip_text_div" style="';
                            if($count == 0){
                                $html .='display:none;';
                            }
                            $html .='left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px;" data-text_dir="'.'0'.'">';
                            $html .= '<td><h5>';
                            $html .= $chip_text;
                            $html .='</h5></td></table>';
                            echo $html;
                            fclose($_fp);
                        ?>
                    </div>
                </div>
            </div>     
            
            
            <!-- Main Content Page -->
                
            <?php
                for($page_for_index=1; $page_for_index<$page_count; $page_for_index++){   
                    $selected_theme_index = $page_for_index*2-1;
            ?>   
            <div class="bb-item">                    
                <div class="photobook_image_content">
                    <div class="main_photobook_content">
                        <div class="main_photobook1">
                            <?php 
                                $theme_width = FLIPBOOK_WIDTH*80/100;
                                $theme_height = FLIPBOOK_HEIGHT;
                                $img_interval = $theme_height/20;
                                $border_radius = $theme_height/10;
                                $border_size = $theme_height/20;
                                $marginLeft = $theme_height/2;
                                $marginBottom = $theme_height/2;
                                $_fp = fopen($directory."/pages/".$selected_theme_index.".txt", "r") or die("Unable to open file!");
                                $count = intval(fgets($_fp));
                                for($i=0; $i<$count; $i++){
                                    $line = fgets($_fp);
                                    $numbers = explode(" ", $line);
                                    $baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
                                    $img_left = $theme_width*floatval($numbers[0])/100;
                                    $img_top = $theme_height*floatval($numbers[1])/100+$img_interval;
                                    $img_width = $theme_width*floatval($numbers[2])/100;
                                    $img_height = $theme_height*floatval($numbers[3])/100;
                                    $backgroud_postion  = " background-position-x:".$numbers[5]."px";

                                    if($numbers[4] % 180 == 90){
                                        $img_width = $theme_height*floatval($numbers[3])/100;
                                        $img_height = $theme_width*floatval($numbers[2])/100;
                                        $img_left = $theme_width*floatval($numbers[0])/100 - ($img_width - $img_height)/2;
                                        $img_top = $theme_height*floatval($numbers[1])/100 + ($img_width - $img_height)/2 + $img_interval;
                                        $backgroud_postion  = " background-position-y:".$numbers[5]."px;";
                                    }  
                                    
                                    $chip_file_name = $numbers[6];

                                    $back_index = 7;
                                    if(strlen($numbers[$back_index])){
                                        $chip_file_name .= " ".$numbers[$back_index];
                                        $back_index++;
                                    } 
                                              
                                    $html = '<a style="cursor:pointer;" class="chip_image_div"><div class="chip_image" ';
                                    $html .=  'style="position:absolute;background-image:url(\''.$baseurl.substr($chip_file_name,0,-1).'\'); background-size:cover; left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px; transform:rotate('.floatval($numbers[4]).'deg);'.$backgroud_postion.'" data-image_index="'.$i.'" data-image_dir="'.'1'.'"';
                                    $html .='></div></a>';
                                        echo $html;
                                    }
                                fclose($_fp);
                                
                                //Display Flipbook Text...
                                $_fp = fopen($directory."/pages/text/".$selected_theme_index.".txt", "r") or die("Unable to open file!");
                                $count = intval(fgets($_fp));
                                $line = fgets($_fp);
                                $numbers = explode(" ", $line);
                                $baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
                                $img_left = $theme_width*floatval($numbers[0])/100;
                                $img_top = $theme_height*floatval($numbers[1])/100+$img_interval;
                                $img_width = $theme_width*floatval($numbers[2])/100;
                                $img_height = $theme_height*floatval($numbers[3])/100;               
                                
                                $chip_text = "";
                                
                                while(!feof($_fp)) {
                                    $chip_text .= fgets($_fp);
                                }
                                   
                                $html = '<table class="chip_text_div" style="';
                                if($count == 0){
                                    $html .='display:none;';
                                }
                                $html .='left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px;" data-text_dir="'.'1'.'">';
                                $html .= '<td><h5>';
                                $html .= $chip_text;
                                $html .='</h5></td></table>';
                                echo $html;
                                fclose($_fp);  
                            ?>
                        </div>
                        <?php        if($page_for_index*2 <= $real_page_count){?>
                        <div class="main_photobook2">
                            <?php                                          
                                    $selected_theme_index = $page_for_index*2;
                                    $theme_width = FLIPBOOK_WIDTH*80/100;
                                    $theme_height = FLIPBOOK_HEIGHT;
                                    $img_interval = $theme_height/20;
                                    $border_radius = $theme_height/10;
                                    $border_size = $theme_height/20;
                                    $marginLeft = $theme_height/2;
                                    $marginBottom = $theme_height/2;
                                    $_fp = fopen($directory."/pages/".$selected_theme_index.".txt", "r") or die("Unable to open file!");
                                    $count = intval(fgets($_fp));
                                    for($i=0; $i<$count; $i++){
                                        $line = fgets($_fp);
                                        $numbers = explode(" ", $line);
                                        $baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
                                        $img_left = $theme_width*floatval($numbers[0])/100;
                                        $img_top = $theme_height*floatval($numbers[1])/100+$img_interval;
                                        $img_width = $theme_width*floatval($numbers[2])/100;
                                        $img_height = $theme_height*floatval($numbers[3])/100;
                                        $backgroud_postion  = " background-position-x:".$numbers[5]."px";
                                        

                                        if($numbers[4] % 180 == 90){
                                            $img_width = $theme_height*floatval($numbers[3])/100;
                                            $img_height = $theme_width*floatval($numbers[2])/100;
                                            $img_left = $theme_width*floatval($numbers[0])/100 - ($img_width - $img_height)/2;
                                            $img_top = $theme_height*floatval($numbers[1])/100 + ($img_width - $img_height)/2 + $img_interval;
                                            $backgroud_postion  = " background-position-y:".$numbers[5]."px;";
                                        }  
                                    
                                        $chip_file_name = $numbers[6];

                                        $back_index = 7;
                                        if(strlen($numbers[$back_index])){
                                            $chip_file_name .= " ".$numbers[$back_index];
                                            $back_index++;
                                        }                        
                                        $html = '<a style="cursor:pointer;" class="chip_image_div"><div class="chip_image" ';
                                        $html .=  'style="position:absolute;background-image:url(\''.$baseurl.substr($chip_file_name,0,-1).'\'); background-size:cover; left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px; transform:rotate('.floatval($numbers[4]).'deg);'.$backgroud_postion.'" data-image_index="'.$i.'" data-image_dir="'.'0'.'"';
                                        $html .='></div></a>';
                                        echo $html;
                                    }
                                    fclose($_fp);
                                    
                                    //Display Flipbook Text...
                                    $_fp = fopen($directory."/pages/text/".$selected_theme_index.".txt", "r") or die("Unable to open file!");
                                    $count = intval(fgets($_fp));
                                    $line = fgets($_fp);
                                    $numbers = explode(" ", $line);
                                    $baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
                                    $img_left = $theme_width*floatval($numbers[0])/100;
                                    $img_top = $theme_height*floatval($numbers[1])/100+$img_interval;
                                    $img_width = $theme_width*floatval($numbers[2])/100;
                                    $img_height = $theme_height*floatval($numbers[3])/100;               
                                    
                                    $chip_text = "";
                                    
                                    while(!feof($_fp)) {
                                        $chip_text .= fgets($_fp);
                                    }
                                       
                                    $html = '<table class="chip_text_div" style="';
                                    if($count == 0){
                                        $html .='display:none;';
                                    }
                                    $html .='left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px;" data-text_dir="'.'0'.'">';
                                    $html .= '<td><h5>';
                                    $html .= $chip_text;
                                    $html .='</h5></td></table>';
                                    echo $html;
                                    fclose($_fp);
                            ?>
                        </div>  <?php } ?>
                    </div>
                </div> 
            </div>
            <?php
                }
            ?> 
            
            <!-- Back Page -->                 
            <div class="bb-item">                 
                <div class="photobook_image_back">
                </div>
            </div>
            
        </div>
                    
		<div class="photobook_button">
            <div class="more_options_div">
                <button type="button" class="btn btn-default more_options_internal" id="delete_page_btn">DELETE PAGE</button>
                <button type="button" class="btn btn-default more_options_internal" id="change_theme_btn">CHANGE THEME</button>
                <button type="button" class="btn btn-default more_options_internal" id="create_page_btn">CREATE A NEW PAGE</button>
                <button type="button" class="btn btn-default more_options_internal" id="upload_more_photos_btn">UPLOAD MORE PHOTOS</button>
            </div>
			<div id="more-options" style="position:absolute;left:30px;"> 
				<button data-toggle="tooltip" data-placement="left" title="More Options" style="padding:0;border-color:#e8e8e8;border-radius:5px;">
                    <img src="<?php echo get_template_directory_uri() ?>/images/more_options.png" width="50px;"/>
                </button>
			</div>
			<div data-toggle="tooltip" data-placement="left" title="Add Text" id="add-text" style="position:absolute;left:100px;">
                <button style="padding:0;border-color:#e8e8e8;border-radius:5px;">
                    <img src="<?php echo get_template_directory_uri() ?>/images/add_text.png" width="50px;"/>
                </button>
			</div>
 			<div id="first-btn" style="position:absolute;left:170px;">
                <button style="padding:0;border-color:#e8e8e8;border-radius:5px;">
                    <img src="<?php echo get_template_directory_uri() ?>/images/photo_first.png" width="50px;"/>
                </button>
			</div>
			<div id="prev-btn" style="position:absolute;right:90px;">               
                <button style="padding:0;border-color:#e8e8e8;border-radius:5px;">
                    <img src="<?php echo get_template_directory_uri() ?>/images/photo_prev.png" width="50px;"/>
                </button>
			</div>
			<div id="next-btn" style="position:absolute;right:20px;">     
                <button style="padding:0;border-color:#e8e8e8;border-radius:5px;">
                    <img src="<?php echo get_template_directory_uri() ?>/images/photo_next.png" width="50px;"/>
                </button>
			</div>
			<center>                                                                                          
                <button id="change_layout" class="btn btn-danger" style="width:200px;height:55px;">                
                    <span style="font-size:17px;" id="change_txt">Change Cover</span>
                </button>
			</center>
		</div>
	</div>                  
</div>
<div id="uploaded_image" style="width:100%;float:left;margin-top:80px;">
	<center>
		<div style="width:70%;">
			<div id="liquid1" class="liquid">
				<span class="previous"></span>
				<div class="wrapper">
					<ul>
						<?php
						//display bottom image...

							for($i=0; $i<$image_count; $i++){
								$html = '<li class="bottom_image" style="cursor:pointer;background-size:cover;margin-left:20px;width:112px;height:112px;background-image:url(\''.$array_items[$i].'\');" >';
								$html .='</li>';
								echo $html;
							}
						?>
					</ul>
				</div>
				<span class="next"></span>
			</div>
		</div>
	</center>
</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($){
   $('.chip_image_div').click(function(e){
      e.preventDefault();
      $('.rmd_button').hide();
      var position = $('.chip_image', $(this)).offset();      
      $('.rmd_button').css({'left':position.left + 'px','top':position.top + 'px'}).show(); 
   });
});
</script>
<?php get_footer(); ?>