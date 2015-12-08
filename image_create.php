<?php 
	add_action( 'wp_ajax_image_create', 'flipbook_image_create' );
	function flipbook_image_create() {
		
		$page_index = $_POST['page_index'];
		$up_obj_dir = $_POST['up_obj_dir'];
		$up_obj_index = $_POST['up_obj_index'];
		$image_name = $_POST['image_name'];
		$selected_theme_index = $page_index*2-$up_obj_dir;       

		global $current_user;
		get_currentuserinfo();

		$user_upload_path = get_template_directory() . "/uploader/files/" . $current_user->ID;

		$directory = $user_upload_path;
	         
    
    //Display FlipBook Image	
		$theme_width = FLIPBOOK_WIDTH*80/100;
		$theme_height = FLIPBOOK_HEIGHT;
		$img_interval = $theme_height/20;
		$border_radius = $theme_height/10;
		$border_size = $theme_height/20;
		$marginLeft = $theme_height/2;
		$marginBottom = $theme_height/2;
		$_fp = fopen($directory."/pages/".$selected_theme_index.".txt", "r") or die("Unable to open file!");
		$count = intval(fgets($_fp));
		$read_line = array();
		for($i=0; $i<$count; $i++){
			$line = fgets($_fp);
			if($i == $up_obj_index){
				$numbers = explode(" ", $line);
				$baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
				$img_left = $theme_width*floatval($numbers[0])/100;
				$img_top = $theme_height*floatval($numbers[1])/100+$img_interval;
				$img_width = $theme_width*floatval($numbers[2])/100;
				$img_height = $theme_height*floatval($numbers[3])/100;
                $backgroud_postion  = " background-position-x:0px";

				if($numbers[4] % 180 == 90){
					$img_width = $theme_height*floatval($numbers[3])/100;
					$img_height = $theme_width*floatval($numbers[2])/100;
					$img_left = $theme_width*floatval($numbers[0])/100 - ($img_width - $img_height)/2;
					$img_top = $theme_height*floatval($numbers[1])/100 + ($img_width - $img_height)/2 + $img_interval;
                    $backgroud_postion  = " background-position-y:0px;";
				}                 
				$numbers[6] = $image_name."\n";
				$html = '<div class="chip_image" ';
				$html .=  'style="position:absolute;background-image:url(\''.$baseurl.substr($numbers[6],0,-1).'\');background-size:cover; left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px; transform:rotate('.floatval($numbers[4]).'deg);'.$backgroud_postion.'" data-image_index="'.$i.'" data-image_dir="'.$up_obj_dir.'"';
				$html .='></div>';
				echo $html;
				$line = "";
				for($j=0; $j<7; $j++){
					$line .= $numbers[$j];
					if($j < 6) $line .= " ";
				}
			}
			$read_line[] = $line;
		}
		fclose($_fp);
		$_fp = fopen($directory."/pages/".$selected_theme_index.".txt", "w") or die("Unable to open file!");
		fwrite($_fp,$count);
		fwrite($_fp,"\n");
		foreach($read_line as $line){
			fwrite($_fp,$line);
		}
		fclose($_fp);                  
    
		wp_die(); // this is required to terminate immediately and return a proper response
	}
?>