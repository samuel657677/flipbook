<?php 
	
	add_action( 'wp_ajax_create_page', 'flipbook_create_page');
	function flipbook_create_page() {
		                                        
		global $current_user;
		get_currentuserinfo();

		$user_upload_path = get_template_directory() . "/uploader/files/" . $current_user->ID;

		$directory = $user_upload_path;     
        $theme_index = rand()%4+1;
        
    //Read first image file
                               
        $image_name = "";
        $file_name_array = array();
        if ($handle = opendir($directory)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (!is_dir($directory. "/" . $file)) {
                        $baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
                        $image_name = $file;
                        break;
                    }
                }
            }
            closedir($handle);
        }    
        
    //Read info.txt for getting old page_count ...
        $_fp = fopen($directory ."/pages/info.txt", "r") or die("Unable to open file!");
        $page_count = intval(fgets($_fp));
        $tema = fgets($_fp);
        $page_dir = $page_count%2;
        fclose($_fp);
        $page_count++;
        
    //Write info.txt for writing new page_count ...
        $_fp = fopen($directory ."/pages/info.txt", "w") or die("Unable to open file!");
        fwrite($_fp,$page_count);
        fwrite($_fp,"\n");
        fwrite($_fp,$tema);
        fclose($_fp);
        
    //Get themes from theme files...
        $image_theme_array = array();
		$_fp = fopen(get_template_directory() ."/image_theme".$theme_index.".txt", "r") or die("Unable to open file!");
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
        
    //Getting random theme
		$selected_theme_index = rand() % (count($image_theme_array));
		$theme_width = FLIPBOOK_WIDTH*80/100;
		$theme_height = FLIPBOOK_HEIGHT;
		$img_interval = $theme_height/20;
		$border_radius = $theme_height/10;
		$border_size = $theme_height/20;
		$marginLeft = $theme_height/2;
		$marginBottom = $theme_height/2;
		$selected_theme = $image_theme_array[$selected_theme_index];
        
        if($page_count%2==1){?>
            <div class="bb-item">                    
                <div class="photobook_image_content">
                    <div class="main_photobook_content">
                        <div class="main_photobook1">
            
<?php        }else{ ?>
        <div class="main_photobook2">
<?php }?>

<?php    
    //Creating a new page ...
		$_fp = fopen($directory."/pages/".$page_count.".txt", "w") or die("Unable to open file!");
		$count = count($selected_theme);
		$str = (string)$count;
		$str .= "\n";
		for($i=0; $i<$count; $i++){                       
			$numbers = $selected_theme[$i];

			$sstr = "";
			for($j = 0; $j < 4; $j++){
				$sstr .= intval($numbers[$j])." ";
			}
			$sstr .= "0 0 ".$image_name."\n";
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
            $baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";                                                                                                                                                                                                                                                              
			$html .=  'style="position:absolute;background-image:url(\''.$baseurl.$image_name.'\'); background-size:cover; left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px; transform:rotate('.floatval($numbers[4]).'deg);'.$backgroud_postion.'" data-image_index="'.$i.'" data-image_dir="'.($page_count%2).'"';
			$html .='></div></a>';

			$image_index++;
			echo $html;
		}

		fwrite($_fp,$str);
		fclose($_fp);
        
        $_fp = fopen($directory."/pages/text/".$page_count.".txt", "w") or die("Unable to open file!");
        $str = "0\n";
        $str .="40 40 20 20\n";
        fwrite($_fp,$str);
        fclose($_fp);
        
        $_fp = fopen($directory."/pages/text/".$page_count.".txt", "r") or die("Unable to open file!");
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
        $html .='left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px;" data-text_dir="'.($page_count%2).'">';
        $html .= '<td><h5>';
        $html .= $chip_text;
        $html .='</h5></td></table>';
        echo $html;
        fclose($_fp);
        if($page_count % 2 == 1){?>
        </div></div></div></div>       
<?php        }else{?>
      </div>
<?php }
        
		wp_die(); // this is required to terminate immediately and return a proper response
	}
?>