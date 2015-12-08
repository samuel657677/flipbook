<?php 
	add_action( 'wp_ajax_set_theme', 'flipbook_set_theme' );
	function flipbook_set_theme() {
		
		global $wpdb; // this is how you get access to the database

		$user_upload_path = get_template_directory() . "/uploader/files/" . $current_user->ID;

		$directory = $user_upload_path;
		$array_items = array();
		$image_count = 0;
		if ($handle = opendir($directory)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if (!is_dir($directory. "/" . $file)) {
						$baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
						$array_items[] = $baseurl.$file;
						$image_count++;
					}
				}
			}
			closedir($handle);
		}

		$selected_theme_index = 2;
		$_fp = fopen(get_template_directory() ."/image_theme.txt", "r") or die("Unable to open file!");
		// Output one line until end-of-file
		$image_index = 0;
		$image_theme_array = array();
		$theme_width = intval(fgets($_fp));
		$theme_height = intval(fgets($_fp));
		while(!feof($_fp)) {
			$count = fgets($_fp);
			$count = intval($count);
			$image_theme = array();
			for($i=0; $i<$count; $i++){
				$baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
				$html .= '<img src="'.$baseurl . $array_items[$image_index].'" ';
				$line = fgets($_fp);
				$numbers = explode(" ", $line);
				$image_theme[] = $numbers;
			}
			$image_theme_array[] = $image_theme;
		}
		fclose($_fp);

		$theme_width = 695*80/100;
		$theme_height = 650;
		$img_interval = $theme_height/20;
		$border_radius = $theme_height/10;
		$border_size = $theme_height/20;
		$marginLeft = $theme_height/2;
		$marginBottom = $theme_height/2;
		$selected_theme = $image_theme_array[$selected_theme_index];
		$count = count($selected_theme);
		for($i=0; $i<$count; $i++){
			$html = '<img src="'. $array_items[$image_index].'" ';
			$numbers = $selected_theme[$i];
			$img_left = $theme_width*floatval($numbers[0])/100;
			$img_top = $theme_height*floatval($numbers[1])/100+$img_interval;
			$img_width = $theme_width*floatval($numbers[2])/100;
			$img_height = $theme_height*floatval($numbers[3])/100;
			$html .=  'style="position:absolute;left:'.$img_left.'px;top:'.$img_top.'px;width:'.$img_width.'px;height:'.$img_height.'px;"';
			$html .='/>';
			$image_index++;
			if($image_index == $image_count) break;
			echo $html;
		}
		
		wp_die(); // this is required to terminate immediately and return a proper response
	}
?>