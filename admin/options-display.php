<?php
function franz_options_display() { 
    
    global $franz_settings;
    ?>
        
    <input type="hidden" name="franz_display" value="true" />
        
        <?php /* Posts Display */ ?>
        <div class="postbox non-essential-option">
            <div class="head-wrap">
                <div title="<?php _e( 'Click to toggle', 'franz-josef' ); ?>" class="handlediv"><br /></div>
                <?php franz_docs_link( 'Posts_Display_Options' ); ?>
        		<h3 class="hndle"><?php _e( 'Posts Display', 'franz-josef' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                	<tr>
                        <th scope="row">
                            <label for="tiled_posts"><?php _e( 'Use tiled layout in posts listing pages', 'franz-josef' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="franz_settings[tiled_posts]" id="tiled_posts" <?php checked( $franz_settings['tiled_posts'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="hide_post_cat"><?php _e( 'Hide post categories', 'franz-josef' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="franz_settings[hide_post_cat]" id="hide_post_cat" <?php checked( $franz_settings['hide_post_cat'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="hide_post_tags"><?php _e( 'Hide post tags', 'franz-josef' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="franz_settings[hide_post_tags]" id="hide_post_tags" <?php checked( $franz_settings['hide_post_tags'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="hide_post_author"><?php _e( 'Hide post author', 'franz-josef' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="franz_settings[hide_post_author]" id="hide_post_author" <?php checked( $franz_settings['hide_post_author'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="hide_author_avatar"><?php _e("Hide author's profile image", 'franz-josef' ); ?></label></th>
                        <td><input type="checkbox" name="franz_settings[hide_author_avatar]" id="hide_author_avatar" <?php checked( $franz_settings['hide_author_avatar'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="hide_featured_image"><?php _e("Hide featured image", 'franz-josef' ); ?></label></th>
                        <td><input type="checkbox" name="franz_settings[hide_featured_image]" id="hide_featured_image" <?php checked( $franz_settings['hide_featured_image'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="hide_author_bio"><?php _e("Hide author's bio", 'franz-josef' ); ?></label></th>
                        <td><input type="checkbox" name="franz_settings[hide_author_bio]" id="hide_author_bio" <?php checked( $franz_settings['hide_author_bio'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="disable_responsive_tables"><?php _e( 'Disable responsive tables', 'franz-josef' ); ?></label></th>
                        <td><input type="checkbox" name="franz_settings[disable_responsive_tables]" id="disable_responsive_tables" <?php checked( $franz_settings['disable_responsive_tables'] ); ?> value="true" /> <?php _e( 'You can also disable responsive tables individually by adding <code>non-responsive</code> class to the tables.', 'franz-josef' ); ?></td>
                    </tr>
                </table>
                
                <p class="submit clearfix"><input type="submit" class="button" value="<?php esc_attr_e( 'Save All Options', 'franz-josef' ); ?>" /></p>
            </div>
        </div>
        
        
        <?php /* Excerpts Display */ ?>
        <div class="postbox non-essential-option">
            <div class="head-wrap">
                <div title="<?php _e( 'Click to toggle', 'franz-josef' ); ?>" class="handlediv"><br /></div>
                <?php franz_docs_link( 'Excerpts_Display_Options' ); ?>
        		<h3 class="hndle"><?php _e( 'Excerpts Display', 'franz-josef' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	<table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="archive_full_content"><?php _e( 'Show full content in archive pages', 'franz-josef' ); ?></label>
                        </th>
                        <td>
                        	<input type="checkbox" name="franz_settings[archive_full_content]" id="archive_full_content" <?php checked( $franz_settings['archive_full_content'] ); ?> value="true" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="excerpt_html_tags"><?php _e("Retain these HTML tags in excerpts", 'franz-josef' ); ?></label></th>
                        <td>
                        	<input type="text" class="widefat code" name="franz_settings[excerpt_html_tags]" id="excerpt_html_tags" value="<?php echo esc_attr( $franz_settings['excerpt_html_tags'] ); ?>" /><br />
                        	<span class="description"><?php _e("Enter the HTML tags you'd like to retain in excerpts. For example, enter <code>&lt;p&gt;&lt;ul&gt;&lt;li&gt;</code> to retain <code>&lt;p&gt;</code>, <code>&lt;ul&gt;</code>, and <code>&lt;li&gt;</code> HTML tags.", 'franz-josef' ); ?></span>
                        </td>
                    </tr>
                </table>
                
                <p class="submit clearfix"><input type="submit" class="button" value="<?php esc_attr_e( 'Save All Options', 'franz-josef' ); ?>" /></p>
            </div>
        </div>
		
        
        <?php /* Footer Widget Display */ ?>
        <div class="postbox non-essential-option">
            <div class="head-wrap">
                <div title="<?php _e( 'Click to toggle', 'franz-josef' ); ?>" class="handlediv"><br /></div>
                <?php franz_docs_link( 'Footer_Widget_Display_Options' ); ?>
        		<h3 class="hndle"><?php _e( 'Footer Widget Display', 'franz-josef' ); ?></h3>
            </div>
            <div class="panel-wrap inside">        
                <table class="form-table">
                    <tr>
                        <th scope="row" style="width:260px;">
                            <label for="footerwidget_column"><?php _e( 'Number of columns to display', 'franz-josef' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="franz_settings[footerwidget_column]" id="footerwidget_column" value="<?php echo esc_attr( $franz_settings['footerwidget_column'] ); ?>" maxlength="2" size="3" /></td>
                    </tr>
                </table>
                
                <p class="submit clearfix"><input type="submit" class="button" value="<?php esc_attr_e( 'Save All Options', 'franz-josef' ); ?>" /></p>
            </div>
        </div>
        
            
        <?php /* Miscellaneous Display */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="<?php _e( 'Click to toggle', 'franz-josef' ); ?>" class="handlediv"><br /></div>
                <?php franz_docs_link( 'Miscellaneous_Display_Options' ); ?>
        		<h3 class="hndle"><?php _e( 'Miscellaneous Display', 'franz-josef' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
	            <h4><?php _e( 'Sidebar options', 'franz-josef' ); ?></h4>
                <table class="form-table">
                    <tr>
                        <th scope="row" style="width:250px;">
                        	<label for="disable_search_widget"><?php _e( 'Disable search form', 'franz-josef' ); ?></label>
                        </th>
                        <td>
                        	<input type="checkbox" name="franz_settings[disable_search_widget]" id="disable_search_widget" <?php checked( $franz_settings['disable_search_widget'] ); ?> value="true" />
                        </td>
                    </tr>
                </table>
                
                <h4><?php _e( 'Favicon options', 'franz-josef' ); ?></h4>
                <table class="form-table">
                    <tr>
                        <th scope="row" style="width:250px;">
                        	<label for="favicon_url"><?php _e( 'Favicon URL', 'franz-josef' ); ?></label>
                        </th>
                        <td>
                        	<input type="text" class="code" size="60" value="<?php echo esc_url( $franz_settings['favicon_url'] ); ?>" name="franz_settings[favicon_url]" id="favicon_url" />
                            <a data-field="favicon_url" data-title="<?php esc_attr_e( 'Select Favicon', 'franz-josef' ); ?>" data-button="<?php esc_attr_e( 'Set as favicon', 'franz-josef' ); ?>" href="#" class="media-upload button"><?php _e( 'Select favicon', 'franz-josef' );?></a>
                        </td>
                    </tr>
                </table>
                
                <h4><?php _e( 'WordPress Editor options', 'franz-josef' ); ?></h4>
                <table class="form-table">
                    <tr>
                        <th scope="row" style="width:250px;">
                        	<label for="disable_editor_style"><?php _e( 'Disable custom editor styles', 'franz-josef' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="franz_settings[disable_editor_style]" id="disable_editor_style" <?php checked( $franz_settings['disable_editor_style'] ); ?> value="true" /></td>
                    </tr>
                </table>
                
                <p class="submit clearfix"><input type="submit" class="button" value="<?php esc_attr_e( 'Save All Options', 'franz-josef' ); ?>" /></p>
            </div>
        </div>
                    
                    
        <?php /* Custom CSS */ ?>
        <div class="postbox non-essential-option">
            <div class="head-wrap">
            	<div title="<?php _e( 'Click to toggle', 'franz-josef' ); ?>" class="handlediv"><br /></div>
                <?php franz_docs_link( 'Custom_CSS' ); ?>
            	<h3 class="hndle"><?php _e( 'Custom CSS', 'franz-josef' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="custom_css"><?php _e( 'Custom CSS styles', 'franz-josef' ); ?></label></th>
                        <td>
                        	<span class="description"><?php _e("You can enter your own CSS codes below to modify any other aspects of the theme's appearance that is not included in the options.", 'franz-josef' ); ?></span>
                        	<textarea name="franz_settings[custom_css]" id="custom_css" cols="60" rows="20" class="widefat code"><?php echo stripslashes( $franz_settings['custom_css'] ); ?></textarea>
                            <script type="text/javascript">
								var customCSS = CodeMirror.fromTextArea(document.getElementById("custom_css"), {
									mode			: 'css',
									lineNumbers		: true,
									lineWrapping	: true,
									indentUnit		: 4,
									styleActiveLine	: true
								});
							</script>
                        </td>
                    </tr>
                </table>
                
                <p class="submit clearfix"><input type="submit" class="button" value="<?php esc_attr_e( 'Save All Options', 'franz-josef' ); ?>" /></p>
            </div>
        </div>                  
        
<?php } // Closes the franz_options_display() function definition 