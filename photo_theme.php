<?php 
	add_action( 'wp_ajax_photo_theme', 'flipbook_photo_theme');
	function flipbook_photo_theme() {
		
		$photobook_image_content = $_POST['photobook_image_content'];

		global $current_user;
		get_currentuserinfo();

		$user_upload_path = get_template_directory() . "/uploader/files/" . $current_user->ID;

		$directory = $user_upload_path;
		
		//read info from certain file

		$_fp = fopen($directory."/pages/info.txt", "r") or die("Unable to open file!");

		$page_count = intval(fgets($_fp));

		fclose($_fp);

		
		//write photobook_image_content on certain file
		$_fp = fopen($directory."/pages/info.txt", "w") or die("Unable to open file!");

		fwrite($_fp , $page_count);
		fwrite($_fp,"\n");
		fwrite($_fp,$photobook_image_content);
		echo $photobook_image_content;

		fclose($_fp);
		wp_die(); // this is required to terminate immediately and return a proper response
	}
?>