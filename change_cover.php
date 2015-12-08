<?php 
	
	add_action( 'wp_ajax_change_cover', 'flipbook_change_cover' );
	function flipbook_change_cover() {
		
		$page_index = $_POST['page_index'];

		global $current_user;
		get_currentuserinfo();

		$user_upload_path = get_template_directory() . "/uploader/files/" . $current_user->ID;

		$directory = $user_upload_path;
		$array_items = array();
		$image_count = 0;
		$file_name_array = array();
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

		$_fp = fopen(get_template_directory() ."/image_theme.txt", "r") or die("Unable to open file!");
		// Output one line until end-of-file
		$image_index = rand() % $image_count;
		$image_theme_array = array();
		$theme_width = intval(fgets($_fp));
		$theme_height = intval(fgets($_fp));
		while(!feof($_fp)) {
			$count = fgets($_fp);
			$count = intval($count);
			$image_theme = array();
			for($i=0; $i<$count; $i++){
				$baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
				$line = fgets($_fp);
				$numbers = explode(" ", $line);
				$image_theme[] = $numbers;
			}
			$image_theme_array[] = $image_theme;
		}
		fclose($_fp);

		$selected_theme_index = rand() % (count($image_theme_array));
		$theme_width = FLIPBOOK_WIDTH*80/100;
		$theme_height = FLIPBOOK_HEIGHT;
		$img_interval = $theme_height/20;
		$border_radius = $theme_height/10;
		$border_size = $theme_height/20;
		$marginLeft = $theme_height/2;
		$marginBottom = $theme_height/2;
		$selected_theme = $image_theme_array[$selected_theme_index];
		$_fp = fopen($directory."/pages/".$page_index.".txt", "w") or die("Unable to open file!");
		$count = count($selected_theme);
		$str = (string)$count;
		$str .= "\n";
		for($i=0; $i<$count; $i++){
			$image_index = $image_index % $image_count;
			$numbers = $selected_theme[$i];

			$sstr = "";
			for($j = 0; $j < 4; $j++){
				$sstr .= intval($numbers[$j])." ";
			}
			$sstr .= "0 0 ".$file_name_array[$image_index]."\n";
			$str .= $sstr;
			
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
			                       
			$html = '<a style="cursor:pointer;" class="chip_image_div"><div class="chip_image" ';                                                                                                                                                                                                                                                                  
			$html .=  'style="position:absolute;background-image:url(\''.$array_items[$image_index].'\'); background-size:cover; left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px; transform:rotate('.floatval($numbers[4]).'deg);'.$backgroud_postion.'" data-image_index="'.$i.'" data-image_dir="'.'0'.'"';
			$html .='></div></a>';

			$image_index++;
			echo $html;
		}

		fwrite($_fp,$str);
		fclose($_fp);
        
        $_fp = fopen($directory."/pages/text/0.txt", "r") or die("Unable to open file!");
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

		wp_die(); // this is required to terminate immediately and return a proper response
	}
?>