<?php 
	
	add_action( 'wp_ajax_delete_page', 'flipbook_delete_page' );
	function flipbook_delete_page() {
		
		$page_index = $_POST['page_index'];
        $page_dir = $_POST['page_dir'];
        $file_index = $page_index * 2 - $page_dir;

		global $current_user;
		get_currentuserinfo();

		$user_upload_path = get_template_directory() . "/uploader/files/" . $current_user->ID;

		$directory = $user_upload_path;     
        
    //Read info.txt for getting old page_count ...
        $_fp = fopen($directory ."/pages/info.txt", "r") or die("Unable to open file!");
        $page_count = intval(fgets($_fp));
        $tema = fgets($_fp);
        fclose($_fp);
        $page_count--;
        
    //Write info.txt for writing new page_count ...
        $_fp = fopen($directory ."/pages/info.txt", "w") or die("Unable to open file!");
        fwrite($_fp,$page_count);
        fwrite($_fp,"\n");
        fwrite($_fp,$tema);
        fclose($_fp);
    //Delete page...    
        for($i = $file_index ; $i <= $page_count; $i++){
            $write_fp = fopen($directory."/pages/".$i.".txt", "w") or die("Unable to open file!");
            $read_fp =  fopen($directory."/pages/".($i+1).".txt", "r") or die("Unable to open file!");
            
            while(!feof($read_fp)) {
                fwrite($write_fp,fgets($read_fp));
            }
            
            fclose($write_fp);
            fclose($read_fp);
        }
    
        for($i = $file_index ; $i <= $page_count; $i++){
            $write_fp = fopen($directory."/pages/text/".$i.".txt", "w") or die("Unable to open file!");
            $read_fp =  fopen($directory."/pages/text/".($i+1).".txt", "r") or die("Unable to open file!");
            
            while(!feof($read_fp)) {
                fwrite($write_fp,fgets($read_fp));
            }
            
            fclose($write_fp);
            fclose($read_fp);
        }
        
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
               
<?php	wp_die(); // this is required to terminate immediately and return a proper response
	}
?>