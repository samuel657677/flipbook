<?php 
	add_action( 'wp_ajax_image_exchange', 'flipbook_image_exchange' );
	function flipbook_image_exchange() {
		
		$page_index = $_POST['page_index'];
		$down_obj_index = $_POST['down_obj_index'];
		$down_obj_dir = $_POST['down_obj_dir'];
		$up_obj_index = $_POST['up_obj_index'];
        $up_obj_dir =   $_POST['up_obj_dir'];
		$down_theme_index = $page_index*2-$down_obj_dir;
        $up_theme_index = $page_index*2-$up_obj_dir;

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

			$down_obj_name = "";
			$up_obj_name = "";
			$down_obj_rotate = 0;
			$up_obj_rotate = 0;  
            $down_obj_pos = 0;
            $up_obj_pos = 0;

			$down_fp = fopen($directory."/pages/".$down_theme_index.".txt", "r") or die("Unable to open file!");
			$count = intval(fgets($down_fp));
			$read_line = array();
			for($i=0; $i<$count; $i++){
				$line = fgets($down_fp);
				$numbers = explode(" ", $line);
				if($i == $down_obj_index){
                    $chip_file_name = $numbers[6];
                    $back_index = 7;
                    if(strlen($numbers[$back_index])){
                        $chip_file_name .= " ".$numbers[$back_index];
                        $back_index++;
                    } 
                    $up_obj_name = $chip_file_name;
					$up_obj_pos = $numbers[5];
					$up_obj_rotate = $numbers[4];
				}
				$read_line[] = $line;
			}
			fclose($down_fp);
            
            $up_fp = fopen($directory."/pages/".$up_theme_index.".txt", "r") or die("Unable to open file!");
            $count = intval(fgets($up_fp));
            $read_line = array();
            for($i=0; $i<$count; $i++){
                $line = fgets($up_fp);
                $numbers = explode(" ", $line);
                 if($i == $up_obj_index){
                    $chip_file_name = $numbers[6];
                    $back_index = 7;
                    if(strlen($numbers[$back_index])){
                        $chip_file_name .= " ".$numbers[$back_index];
                        $back_index++;
                    } 
                    $down_obj_name = $chip_file_name;
                    $down_obj_pos = $numbers[5];
                    $down_obj_rotate = $numbers[4];
                }
                $read_line[] = $line;
            }
            fclose($up_fp);
?>   
        <div class="main_photobook1">
<?php		
        if($down_obj_dir == 1){
        
        // Display FlipBook Image
            $down_fp = fopen($directory."/pages/".$down_theme_index.".txt", "r") or die("Unable to open file!");
            $count = intval(fgets($down_fp));
            $read_line = array();
            for($i=0; $i<$count; $i++){
                $line = fgets($down_fp);
                $numbers = explode(" ", $line);
                $baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
                $img_left = $theme_width*floatval($numbers[0])/100;
                $img_top = $theme_height*floatval($numbers[1])/100+$img_interval;
                $img_width = $theme_width*floatval($numbers[2])/100;
                $img_height = $theme_height*floatval($numbers[3])/100;
                
                $chip_file_name = $numbers[6];
                $back_index = 7;
                if(strlen($numbers[$back_index])){
                    $chip_file_name .= " ".$numbers[$back_index];
                    $back_index++;
                } 
                if($i == $down_obj_index){
                    $numbers[4] = $down_obj_rotate;
                    $numbers[5] = $down_obj_pos;
                    $chip_file_name = $down_obj_name;
                }

                $backgroud_postion  = " background-position-x:".$numbers[5]."px";
                if($numbers[4] % 180 == 90){
                    $img_width = $theme_height*floatval($numbers[3])/100;
                    $img_height = $theme_width*floatval($numbers[2])/100;
                    $img_left = $theme_width*floatval($numbers[0])/100 - ($img_width - $img_height)/2;
                    $img_top = $theme_height*floatval($numbers[1])/100 + ($img_width - $img_height)/2 + $img_interval;
                    $backgroud_postion  = " background-position-y:".$numbers[5]."px";       
                }              
                
                $html = '<a style="cursor:pointer;" class="chip_image_div"><div class="chip_image" ';
                $html .=  'style="position:absolute;background-image:url(\''.$baseurl.substr($chip_file_name,0,-1).'\');background-size:cover; left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px; transform:rotate('.floatval($numbers[4]).'deg);'.$backgroud_postion.'" data-image_index="'.$i.'" data-image_dir="'.$down_obj_dir.'"';
                $html .='></div></a>';
                echo $html;
                $line = "";
                for($j=0; $j<6; $j++){
                    $line .= $numbers[$j]." "; 
                }               
                $line .= $chip_file_name;             
                $read_line[] = $line;
            }
            fclose($down_fp);
            $down_fp = fopen($directory."/pages/".$down_theme_index.".txt", "w") or die("Unable to open file!");
            fwrite($down_fp,$count);
            fwrite($down_fp,"\n");
            foreach($read_line as $line){
                fwrite($down_fp,$line);
            }
            fclose($down_fp);
            
        //Display Flipbook Text...
            $_fp = fopen($directory."/pages/text/".$down_theme_index.".txt", "r") or die("Unable to open file!");
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
            $html .='left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px;" data-text_dir="'.$down_obj_dir.'">';
            $html .= '<td><h5>';
            $html .= $chip_text;
            $html .='</h5></td></table>';
            echo $html;
            fclose($_fp);
            
        }else{
        
        // Display FlipBook Image   
            $up_fp = fopen($directory."/pages/".$up_theme_index.".txt", "r") or die("Unable to open file!");
            $count = intval(fgets($up_fp));
            $read_line = array();
            for($i=0; $i<$count; $i++){
                $line = fgets($up_fp);
                $numbers = explode(" ", $line);
                $baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
                $img_left = $theme_width*floatval($numbers[0])/100;
                $img_top = $theme_height*floatval($numbers[1])/100+$img_interval;
                $img_width = $theme_width*floatval($numbers[2])/100;
                $img_height = $theme_height*floatval($numbers[3])/100;
                
                $chip_file_name = $numbers[6];
                $back_index = 7;
                if(strlen($numbers[$back_index])){
                    $chip_file_name .= " ".$numbers[$back_index];
                    $back_index++;
                } 
                
                if($i == $up_obj_index){
                    $numbers[4] = $up_obj_rotate;
                    $numbers[5] = $up_obj_pos;
                    $chip_file_name = $up_obj_name;
                }
                
                $backgroud_postion  = " background-position-x:".$numbers[5]."px";      
                if($numbers[4] % 180 == 90){
                    $img_width = $theme_height*floatval($numbers[3])/100;
                    $img_height = $theme_width*floatval($numbers[2])/100;
                    $img_left = $theme_width*floatval($numbers[0])/100 - ($img_width - $img_height)/2;
                    $img_top = $theme_height*floatval($numbers[1])/100 + ($img_width - $img_height)/2 + $img_interval;
                    $backgroud_postion  = " background-position-y:".$numbers[5]."px";
                }              
                
                $html = '<a style="cursor:pointer;" class="chip_image_div"><div class="chip_image" ';
                $html .=  'style="position:absolute;background-image:url(\''.$baseurl.substr($chip_file_name,0,-1).'\');background-size:cover; left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px; transform:rotate('.floatval($numbers[4]).'deg);'.$backgroud_postion.'" data-image_index="'.$i.'" data-image_dir="'.$up_obj_dir.'"';
                $html .='></div></a>';
                echo $html;
                $line = "";
                for($j=0; $j<6; $j++){
                    $line .= $numbers[$j]." ";
                }
                $line .= $chip_file_name;
                $read_line[] = $line;
            }
            fclose($up_fp);
            $up_fp = fopen($directory."/pages/".$up_theme_index.".txt", "w") or die("Unable to open file!");
            fwrite($up_fp,$count);
            fwrite($up_fp,"\n");
            foreach($read_line as $line){
                fwrite($up_fp,$line);
            }
            fclose($up_fp);
        
        //Display Flipbook Text...
            $_fp = fopen($directory."/pages/text/".$up_theme_index.".txt", "r") or die("Unable to open file!");
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
            $html .='left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px;" data-text_dir="'.$up_obj_dir.'">';
            $html .= '<td><h5>';
            $html .= $chip_text;
            $html .='</h5></td></table>';
            echo $html;
            fclose($_fp);
               
        }         
?></div>
<div class="main_photobook2">
<?php
        if($down_obj_dir == 0){
        
        // Display FlipBook Image
            $down_fp = fopen($directory."/pages/".$down_theme_index.".txt", "r") or die("Unable to open file!");
            $count = intval(fgets($down_fp));
            $read_line = array();
            for($i=0; $i<$count; $i++){
                $line = fgets($down_fp);
                $numbers = explode(" ", $line);
                $baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
                $img_left = $theme_width*floatval($numbers[0])/100;
                $img_top = $theme_height*floatval($numbers[1])/100+$img_interval;
                $img_width = $theme_width*floatval($numbers[2])/100;
                $img_height = $theme_height*floatval($numbers[3])/100;
                
                $chip_file_name = $numbers[6];
                $back_index = 7;
                if(strlen($numbers[$back_index])){
                    $chip_file_name .= " ".$numbers[$back_index];
                    $back_index++;
                } 
                if($i == $down_obj_index){
                    $numbers[4] = $down_obj_rotate;
                    $numbers[5] = $down_obj_pos;
                    $chip_file_name = $down_obj_name;
                }

                $backgroud_postion  = " background-position-x:".$numbers[5]."px";
                if($numbers[4] % 180 == 90){
                    $img_width = $theme_height*floatval($numbers[3])/100;
                    $img_height = $theme_width*floatval($numbers[2])/100;
                    $img_left = $theme_width*floatval($numbers[0])/100 - ($img_width - $img_height)/2;
                    $img_top = $theme_height*floatval($numbers[1])/100 + ($img_width - $img_height)/2 + $img_interval;
                    $backgroud_postion  = " background-position-y:".$numbers[5]."px";
                }              
                
                $html = '<a style="cursor:pointer;" class="chip_image_div"><div class="chip_image" ';
                $html .=  'style="position:absolute;background-image:url(\''.$baseurl.substr($chip_file_name,0,-1).'\');background-size:cover; left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px; transform:rotate('.floatval($numbers[4]).'deg);'.$backgroud_postion.'" data-image_index="'.$i.'" data-image_dir="'.$down_obj_dir.'"';
                $html .='></div></a>';
                echo $html;
                $line = "";
                for($j=0; $j<6; $j++){
                    $line .= $numbers[$j]." "; 
                }               
                $line .= $chip_file_name;             
                $read_line[] = $line;
            }
            fclose($down_fp);
            $down_fp = fopen($directory."/pages/".$down_theme_index.".txt", "w") or die("Unable to open file!");
            fwrite($down_fp,$count);
            fwrite($down_fp,"\n");
            foreach($read_line as $line){
                fwrite($down_fp,$line);
            }
            fclose($down_fp);
        
        //Display Flipbook Text...
            $_fp = fopen($directory."/pages/text/".$down_theme_index.".txt", "r") or die("Unable to open file!");
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
            $html .='left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px;" data-text_dir="'.$down_obj_dir.'">';
            $html .= '<td><h5>';
            $html .= $chip_text;
            $html .='</h5></td></table>';
            echo $html;
            fclose($_fp);
            
        }else{
        
        // Display FlipBook Image   
            $up_fp = fopen($directory."/pages/".$up_theme_index.".txt", "r") or die("Unable to open file!");
            $count = intval(fgets($up_fp));
            $read_line = array();
            for($i=0; $i<$count; $i++){
                $line = fgets($up_fp);
                $numbers = explode(" ", $line);
                $baseurl = get_template_directory_uri() . "/uploader/files/" . $current_user->ID . "/";
                $img_left = $theme_width*floatval($numbers[0])/100;
                $img_top = $theme_height*floatval($numbers[1])/100+$img_interval;
                $img_width = $theme_width*floatval($numbers[2])/100;
                $img_height = $theme_height*floatval($numbers[3])/100;
                
                $chip_file_name = $numbers[6];
                $back_index = 7;
                if(strlen($numbers[$back_index])){
                    $chip_file_name .= " ".$numbers[$back_index];
                    $back_index++;
                } 
                
                if($i == $up_obj_index){
                    $numbers[4] = $up_obj_rotate;
                    $numbers[5] = $up_obj_pos;
                    $chip_file_name = $up_obj_name;
                }
                
                $backgroud_postion  = " background-position-x:".$numbers[5]."px";      
                if($numbers[4] % 180 == 90){
                    $img_width = $theme_height*floatval($numbers[3])/100;
                    $img_height = $theme_width*floatval($numbers[2])/100;
                    $img_left = $theme_width*floatval($numbers[0])/100 - ($img_width - $img_height)/2;
                    $img_top = $theme_height*floatval($numbers[1])/100 + ($img_width - $img_height)/2 + $img_interval;
                    $backgroud_postion  = " background-position-y:".$numbers[5]."px";
                }              
                
                $html = '<a style="cursor:pointer;" class="chip_image_div"><div class="chip_image" ';
                $html .=  'style="position:absolute;background-image:url(\''.$baseurl.substr($chip_file_name,0,-1).'\');background-size:cover; left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px; transform:rotate('.floatval($numbers[4]).'deg);'.$backgroud_postion.'" data-image_index="'.$i.'" data-image_dir="'.$up_obj_dir.'"';
                $html .='></div></a>';
                echo $html;
                $line = "";
                for($j=0; $j<6; $j++){
                    $line .= $numbers[$j]." ";
                }
                $line .= $chip_file_name;
                $read_line[] = $line;
            }
            fclose($up_fp);
            $up_fp = fopen($directory."/pages/".$up_theme_index.".txt", "w") or die("Unable to open file!");
            fwrite($up_fp,$count);
            fwrite($up_fp,"\n");
            foreach($read_line as $line){
                fwrite($up_fp,$line);
            }
            fclose($up_fp);  
            
        //Display Flipbook Text...
            $_fp = fopen($directory."/pages/text/".$up_theme_index.".txt", "r") or die("Unable to open file!");
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
            $html .='left:'.$img_left.'px; top:'.$img_top.'px; width:'.$img_width.'px; height:'.$img_height.'px;" data-text_dir="'.$up_obj_dir.'">';
            $html .= '<td><h5>';
            $html .= $chip_text;
            $html .='</h5></td></table>';
            echo $html;
            fclose($_fp);
            
        } 
?>
</div>
<?php
		wp_die(); // this is required to terminate immediately and return a proper response
	}
?>