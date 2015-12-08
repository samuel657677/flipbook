<?php 
	add_action( 'wp_ajax_text_create', 'flipbook_text_create' );
	function flipbook_text_create() {
		
		$page_index = $_POST['page_index'];
		$text_dir = $_POST['text_dir'];             
		$add_text = $_POST['add_text'];
		$selected_theme_index = $page_index*2-$text_dir;

		global $current_user;
		get_currentuserinfo();

		$user_upload_path = get_template_directory() . "/uploader/files/" . $current_user->ID;

		$directory = $user_upload_path;
		
		$_fp = fopen($directory."/pages/text/".$selected_theme_index.".txt", "r") or die("Unable to open file!");
		$count = intval(fgets($_fp));
        $count = 1;
        $text = "1\n";
		for($i=0; $i<$count; $i++){
			$text .= fgets($_fp);
            $text .= $add_text;      
		}
		fclose($_fp);
		$_fp = fopen($directory."/pages/text/".$selected_theme_index.".txt", "w") or die("Unable to open file!");       
		fwrite($_fp,$text);
		fclose($_fp);
		wp_die(); // this is required to terminate immediately and return a proper response
	}
?>