<?php 
	add_action( 'wp_ajax_text_delete', 'flipbook_text_delete' );
	function flipbook_text_delete() {
		
		$page_index = $_POST['page_index'];
		$text_dir = $_POST['text_dir'];   
		$selected_theme_index = $page_index*2-$text_dir;

		global $current_user;
		get_currentuserinfo();

		$user_upload_path = get_template_directory() . "/uploader/files/" . $current_user->ID;

		$directory = $user_upload_path;
		
		$_fp = fopen($directory."/pages/text/".$selected_theme_index.".txt", "r") or die("Unable to open file!");
		$count = intval(fgets($_fp));
        $text = "0\n";
		for($i=0; $i<$count; $i++){
			$text .= fgets($_fp);
		}
		fclose($_fp);
		$_fp = fopen($directory."/pages/text/".$selected_theme_index.".txt", "w") or die("Unable to open file!");       
		fwrite($_fp,$text);
		fclose($_fp);
		wp_die(); // this is required to terminate immediately and return a proper response
	}
?>