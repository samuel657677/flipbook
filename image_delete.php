<?php 
	add_action( 'wp_ajax_image_delete', 'flipbook_image_delete');
	function flipbook_image_delete() {
		
		$page_index = $_POST['page_index'];
		$selected_obj_index = $_POST['selected_obj_index'];
		$selected_obj_dir = $_POST['selected_obj_dir'];
		$selected_theme_index = $page_index*2-$selected_obj_dir;

		global $current_user;
		get_currentuserinfo();

		$user_upload_path = get_template_directory() . "/uploader/files/" . $current_user->ID;

		$directory = $user_upload_path;
		
		$_fp = fopen($directory."/pages/".$selected_theme_index.".txt", "r") or die("Unable to open file!");
		
		$file_name_array = array();
		$file_rotate_array = array();
        $file_pos_array = array();
		$chip_count = intval(fgets($_fp));
		for($i=0; $i<$chip_count; $i++){
			$line = fgets($_fp);
			$numbers = explode(" ", $line);
			if($i != $selected_obj_index) {                        
                $chip_file_name = $numbers[6];
                $back_index = 7;
                if(strlen($numbers[$back_index])){
                    $chip_file_name .= " ".$numbers[$back_index];
                    $back_index++;
                }    
				$file_name_array[] = $chip_file_name;
				$file_rotate_array[] = intval($numbers[4]);
                $file_pos_array[] = intval($numbers[5]);
			}
		}
		fclose($_fp);

		if($chip_count>1){
			$chip_count--;
			//random get image theme

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


			// change layout
			$selected_theme = $chip_image_theme[rand() % (count($chip_image_theme))];
			$count = count($selected_theme);
			$theme_width = FLIPBOOK_WIDTH*80/100;
			$theme_height = FLIPBOOK_HEIGHT;
			$img_interval = $theme_height/20;
			$border_radius = $theme_height/10;
			$border_size = $theme_height/20;
			$marginLeft = $theme_height/2;
			$marginBottom = $theme_height/2;
			$_fp = fopen($directory."/pages/".$selected_theme_index.".txt", "w") or die("Unable to open file!");
			$str = (string)$count;
			$str .= "\n";
			$baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
            
        //Delete and Display FlipBook Image
			for($i=0; $i<$count; $i++){
				$numbers = $selected_theme[$i];

				$sstr = "";
				for($j = 0; $j < 4; $j++){
					$sstr .= intval($numbers[$j])." ";
				}
				$sstr .= $file_rotate_array[$i]." ".$file_pos_array[$i]." ".$file_name_array[$i];
				$str .= $sstr;
				
				$img_left = $theme_width*floatval($numbers[0])/100;
				$img_top = $theme_height*floatval($numbers[1])/100+$img_interval;
				$img_width = $theme_width*floatval($numbers[2])/100;
				$img_height = $theme_height*floatval($numbers[3])/100;
				$numbers[4] = $file_rotate_array[$i];
                $numbers[5] = $file_pos_array[$i];
                $backgroud_postion  = " background-position-x:".$numbers[5]."px";  
                
				if($numbers[4] % 180 == 90){
					$img_width = $theme_height*floatval($numbers[3])/100;
					$img_height = $theme_width*floatval($numbers[2])/100;
					$img_left = $theme_width*floatval($numbers[0])/100 - ($img_width - $img_height)/2;
					$img_top = $theme_height*floatval($numbers[1])/100 + ($img_width - $img_height)/2 + $img_interval;
                    $backgroud_postion  = " background-position-y:".$numbers[5]."px";       
				}
				
				$html = '<a style="cursor:pointer;" class="chip_image_div"><div class="chip_image" ';
				
				$html .=  'style="position:absolute;background-image:url(\''.$baseurl.substr($file_name_array[$i],0,-1).'\'); background-size:cover; left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px; transform:rotate('.floatval($numbers[4]).'deg);'.$backgroud_postion.'" data-image_index="'.$i.'" data-image_dir="'.($selected_obj_dir).'"';
				$html .='></div></a>';

				echo $html;
			}

			fwrite($_fp,$str);
			fclose($_fp);
		}
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
        $html .='left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px;" data-text_dir="'.$selected_obj_dir.'">';
        $html .= '<td><h5>';
        $html .= $chip_text;
        $html .='</h5></td></table>';
        echo $html;
        fclose($_fp);
		wp_die(); // this is required to terminate immediately and return a proper response
	}
?>