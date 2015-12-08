<?php 
	add_action( 'wp_ajax_image_rotate', 'flipbook_image_rotate' );
	function flipbook_image_rotate() {
		
		$page_index = $_POST['page_index'];
		$selected_obj_index = $_POST['selected_obj_index'];
		$selected_obj_dir = $_POST['selected_obj_dir'];
		$selected_theme_index = $page_index*2-$selected_obj_dir;

		global $current_user;
		get_currentuserinfo();

		$user_upload_path = get_template_directory() . "/uploader/files/" . $current_user->ID;

		$directory = $user_upload_path;
		
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
				if($i == $selected_obj_index){
					$numbers = explode(" ", $line);
					$baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
					$img_left = $theme_width*floatval($numbers[0])/100;
					$img_top = $theme_height*floatval($numbers[1])/100+$img_interval;
					$img_width = $theme_width*floatval($numbers[2])/100;
					$img_height = $theme_height*floatval($numbers[3])/100;
					$numbers[4] = (intval($numbers[4])+90)%360;

                    $backgroud_postion  = " background-position-x:".$numbers[5]."px";
					if($numbers[4] % 180 == 90){
						$img_width = $theme_height*floatval($numbers[3])/100;
						$img_height = $theme_width*floatval($numbers[2])/100;
						$img_left = $theme_width*floatval($numbers[0])/100 - ($img_width - $img_height)/2;
						$img_top = $theme_height*floatval($numbers[1])/100 + ($img_width - $img_height)/2 + $img_interval;
                        $backgroud_postion  = " background-position-y:".$numbers[5]."px";
					}                              
                    $chip_file_name = $numbers[6];
                    $back_index = 7;
                    if(strlen($numbers[$back_index])){
                        $chip_file_name .= " ".$numbers[$back_index];
                        $back_index++;
                    }
					$html = '<div class="chip_image" ';
					$html .=  'style="position:absolute;background-image:url(\''.$baseurl.substr($chip_file_name,0,-1).'\');border:5px solid red; background-size:cover; left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px; transform:rotate('.floatval($numbers[4]).'deg);'.$backgroud_postion.'" data-image_index="'.$i.'" data-image_dir="'.$selected_obj_dir.'"';
					$html .='></div>';
					echo $html;
					$line = "";
					for($j=0; $j<6; $j++){
						$line .= $numbers[$j]." ";
					}
                    $line .= $chip_file_name;
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