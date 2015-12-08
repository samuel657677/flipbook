<?php
/*
Template Name: temaselect
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

	
	//read selected theme
	$_fp = fopen(get_template_directory() ."/image_theme.txt", "r") or die("Unable to open file!");
	// Output one line until end-of-file
	$image_theme_array = array();
	$theme_width = intval(fgets($_fp));
	$theme_height = intval(fgets($_fp));
	while(!feof($_fp)) {
		$count = fgets($_fp);
		$count = intval($count);
		$image_theme = array();
		for($i=0; $i<$count; $i++){
			$line = fgets($_fp);
			$numbers = explode(" ", $line);
			$image_theme[] = $numbers;
		}
		$image_theme_array[] = $image_theme;
	}
	fclose($_fp);


	//write each page on file

	$theme_count = count($image_theme_array);
	$page_count = intval($image_count/2);
	if($page_count < 24) $page_count = 24;
	if($page_count > 80) $page_count = intval($image_cont /4);
	if(!is_dir($directory."/pages")){
		mkdir($directory."/pages");
	}   
    if(!is_dir($directory."/pages/text")){
        mkdir($directory."/pages/text");
    }   
	$tmp_image_count = $image_count;
	$tmp_page_count = $page_count;
	$file_name_index = 0;
	$page_index = 0;
	while($page_count > 0){

		//Set Theme on Header Page
		if($file_name_index == 0){
			$_fp = fopen($directory."/pages/0.txt", "w") or die("Unable to open file!");

			$one_theme = $image_theme_array[1];
			$one_theme_image_count = count($one_theme);

			$str = (string)$one_theme_image_count;
			$str .= "\n";
			
			for($j = 0; $j < $one_theme_image_count; $j++){
				$sstr = "";
				for($k = 0; $k < 4; $k++){
					$sstr .= intval($one_theme[$j][$k])." ";
				}
				$sstr .= "0 0 ".$file_name_array[$file_name_index]."\n";
				$file_name_index++;

				$str .= $sstr;
			}
			fwrite($_fp,$str);

			fclose($_fp);
			$file_name_index++;
			$file_name_index = 0;
		}
		
		//Set Theme on Main Page

		$chip_count = 1;
		if($image_count < $page_count+4){
			$chip_count = 1;
		}else if($image_count > $page_count*4-10){
			$chip_count = 4;
		}else if($image_count > $page_count){
			$chip_count = rand()%4+1;
			while($image_count - $chip_count < $page_count){
				$chip_count = rand()%4+1;
			}
		}

		$_fp = fopen(get_template_directory() ."/image_theme".$chip_count.".txt", "r") or die("Unable to open file!");

		$chip_image_theme = array();
		while(!feof($_fp)) {
			$count = fgets($_fp);
			$count = intval($count);
			$image_theme = array();
			for($i=0; $i<$count; $i++){
				$line = fgets($_fp);
				$numbers = explode(" ", $line);
				$image_theme[] = $numbers;
			}
			$chip_image_theme[] = $image_theme;
		}
		
		fclose($_fp);
		
		$chip_image_theme_count = count($chip_image_theme);
		

		$_fp = fopen($directory."/pages/".($page_index+1).".txt", "w") or die("Unable to open file!");

		$one_theme = $chip_image_theme[rand() % $chip_image_theme_count];
		$one_theme_image_count = count($one_theme);

		$str = (string)$one_theme_image_count;
		$str .= "\n";
		
		for($j = 0; $j < $one_theme_image_count; $j++){
			$sstr = "";
			for($k = 0; $k < 4; $k++){
				$sstr .= intval($one_theme[$j][$k])." ";
			}
			$sstr .= "0 0 ".$file_name_array[$file_name_index]."\n";
			$file_name_index++;

			$str .= $sstr;
		}
		fwrite($_fp,$str);

		fclose($_fp);
		$image_count -= $chip_count;
		$page_count--;
		$page_index++;
	}
    
    for($page_index = 0; $page_index <= $tmp_page_count; $page_index++){
    //Set Text on Each Page 
        $_fp = fopen($directory."/pages/text/".$page_index.".txt", "w") or die("Unable to open file!");
        
        $str = "0\n";
        $str .="40 40 20 20\n";
        fwrite($_fp,$str);

        fclose($_fp);           
    }
   
	$page_count = $tmp_page_count;
	$image_count = $tmp_image_count;
	$_fp = fopen($directory."/pages/info.txt", "w") or die("Unable to open file!");
	fwrite($_fp , $page_count);
	fwrite($_fp,"\n");
	fwrite($_fp,"photobook1.png");
	fclose($_fp);

?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/photoselect_style.css">
<div id="child_header" class="col-md-12">	
	<div class="col-md-2"></div>
	<div class="col-md-8"><center><a href="<?php echo get_bloginfo('siteurl') ?>"><img src="<?php echo get_template_directory_uri() ?>/images/header_title.png" class="header_title"/></a></center></div>
	<div class="col-md-2">
        <center>
            <a class="radius button success obtn button_form" id="next-btn" href="<?php echo get_bloginfo('siteurl') ?>/photobook">
                <button class="btn btn-warning make_btn" style="width:100px;">                
                    <span style="font-size:15px;">Next</span>
                </button>  
            </a>
        </center>
    </div>
</div>
<div class="clear" style="clear: both;"></div> 
<div id="uploaded_image" style="margin-top:50px;overflow:hidden;">
	<?php 
		$_fp = fopen(get_template_directory() ."/image_theme.txt", "r") or die("Unable to open file!");
		// Output one line until end-of-file
		$image_index = 0;
		$theme_width = 300;
		$theme_height = 150;
		$img_interval = $theme_height/20;
		$border_radius = $theme_height/10;
		$border_size = $theme_height/20;
		$marginLeft = $theme_height/2;
		$marginBottom = $theme_height/2;
		for($i = 0; $i < $page_count; $i++){
			$_fp = fopen($directory."/pages/".($i+1).".txt", "r") or die("Unable to open file!");
			$count = fgets($_fp);
			$count = intval($count);
			$html = '<div class="image_div" style="background-color:white;border-radius:'.$border_radius.'px;border:'.$border_size.'px solid #fff;margin-left:'.$marginLeft.'px;margin-bottom:'.$marginBottom.'px;float:left;position:relative;width:'.$theme_width.'px;height:'.$theme_height.'px;">';
			for($j=0; $j<$count; $j++){
				$line = fgets($_fp);
				$numbers = explode(" ", $line);
				$baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
                $chip_file_name = $numbers[6];
                $back_index = 7;
                if(strlen($numbers[$back_index])){
                    $chip_file_name .= " ".$numbers[$back_index];
                    $back_index++;
                }
                
                $img_left   = $theme_width * floatval($numbers[0]) / 100;
                $img_top    = $theme_height * floatval($numbers[1]) / 100;
                $img_width  = $theme_width * floatval($numbers[2]) / 100;
                $img_height = $theme_height * floatval($numbers[3]) / 100;
                
                $chip_file_name = str_replace(" ", "%20", $chip_file_name);
                $html .= "<div style='background:url(".$baseurl . $chip_file_name.");background-size:cover;position:absolute;left:".$img_left."px;top:".$img_top."px;width:".$img_width."px;height:".$img_height."px;'></div>";
                
				/*$html .= '<img src="'.$baseurl . $chip_file_name.'" ';
				$html .= 'style="position:absolute;left:'.$img_left.'px;top:'.$img_top.'px;width:'.$img_width.'px;height:'.$img_height.'px;"';
				$html .= '/>';*/
			}
			$html .= '</div>';
			echo $html;
			fclose($_fp);
		}
	?>
</div>
<div id="tema_image" style="position:absolute;padding-bottom:20px;width:100%;">
	<center>
		<img id="white_tema" src="<?php echo get_template_directory_uri() ?>/images/tema/white.png" class="col-mid-1 tema_img"/>
		<img id="black_tema" src="<?php echo get_template_directory_uri() ?>/images/tema/black.png" class="col-mid-1 tema_img"/>
		<img id="tema01" src="<?php echo get_template_directory_uri() ?>/images/tema/tema01.png" class="col-mid-1 tema_img"/>
<!--		<img id="tema02" src="<?php echo get_template_directory_uri() ?>/images/tema/tema02.png" class="col-mid-1 tema_img"/>
		<img id="tema03" src="<?php echo get_template_directory_uri() ?>/images/tema/tema03.png" class="col-mid-1 tema_img"/>
		<img id="tema04" src="<?php echo get_template_directory_uri() ?>/images/tema/tema04.png" class="col-mid-1 tema_img"/>
		<img id="tema05" src="<?php echo get_template_directory_uri() ?>/images/tema/tema05.png" class="col-mid-1 tema_img"/>
		<img id="tema06" src="<?php echo get_template_directory_uri() ?>/images/tema/tema06.png" class="col-mid-1 tema_img"/>
		<img id="tema07" src="<?php echo get_template_directory_uri() ?>/images/tema/tema07.png" class="col-mid-1 tema_img"/>
		<img id="tema08" src="<?php echo get_template_directory_uri() ?>/images/tema/tema08.png" class="col-mid-1 tema_img"/>
		<img id="tema09" src="<?php echo get_template_directory_uri() ?>/images/tema/tema09.png" class="col-mid-1 tema_img"/>  -->
	</center>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		var ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
		$('#white_tema').click(function(){                                                                                                        
            $('.image_div').css('background-image','none');
			$('.image_div').css('background-color','white');
			//$('.image_div').css('border-color','#ee6215');

			var data = {
				'action': 'photo_theme',
				'photobook_image_content': 'photobook1.png'
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajax_url, data, function(response) {
			});
		});
		$('#black_tema').click(function(){
            $('.image_div').css('background-image','none');
			$('.image_div').css('background-color','black');
			//$('.image_div').css('border-color','white');

			var data = {
				'action': 'photo_theme',
				'photobook_image_content': 'photobook2.png'
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajax_url, data, function(response) {
			});
		});            
        $('#tema01').click(function(){
            $('.image_div').css('background-image','url('+'<?php echo get_template_directory_uri()."/images/tema/tema01_back.png"; ?>'+')');
            //$('.image_div').css('border-color','white');

            var data = {
                'action': 'photo_theme',
                'photobook_image_content': 'photobook3.png'
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajax_url, data, function(response) {
            });
        });
	});
</script>
<?php get_footer(); ?>