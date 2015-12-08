<?php
function franz_options_general() { 
    
    global $franz_settings;
    ?>
        <input type="hidden" name="franz_general" value="true" />
        
        <?php /* Slider */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="<?php _e( 'Click to toggle', 'franz-josef' ); ?>" class="handlediv"><br /></div>
                <?php franz_docs_link( 'Slider_options' ); ?>
        		<h3 class="hndle"><?php _e( 'Slider', 'franz-josef' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="slider_disable"><?php _e( 'Disable slider', 'franz-josef' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="franz_settings[slider_disable]" id="slider_disable" <?php checked( $franz_settings['slider_disable'] ); ?> value="true" data-toggleOptions="true" /></td>
                    </tr>
                </table>
                <table class="form-table<?php if ( $franz_settings['slider_disable'] == true ) echo ' hide'; ?>">
                    <tr>
                        <th scope="row">
                            <label><?php _e( 'What do you want to show in the slider', 'franz-josef' ); ?></label><br />                            
                        </th>
                        <td>
                            <input type="radio" name="franz_settings[slider_type]" value="latest_posts" class="slider-type" id="slider_type_latest_posts" <?php checked( $franz_settings['slider_type'], 'latest_posts' ); ?>/>
                            <label for="slider_type_latest_posts"><?php _e( 'Show latest posts', 'franz-josef' ); ?></label>                            
                            <br />
                            <input type="radio" name="franz_settings[slider_type]" value="random" class="slider-type" id="slider_type_random" <?php checked( $franz_settings['slider_type'], 'random' ); ?>/>
                            <label for="slider_type_random"><?php _e( 'Show random posts', 'franz-josef' ); ?></label>
                            <br />
                            <input type="radio" name="franz_settings[slider_type]" value="posts_pages" class="slider-type" id="slider_type_posts_pages" <?php checked( $franz_settings['slider_type'], 'posts_pages' ); ?>/>
                            <label for="slider_type_posts_pages"><?php _e( 'Show specific posts/pages', 'franz-josef' ); ?></label>                            
                            <br />
                            <input type="radio" name="franz_settings[slider_type]" value="categories" class="slider-type" id="slider_type_categories" <?php checked( $franz_settings['slider_type'], 'categories' ); ?>/>
                            <label for="slider_type_categories"><?php _e( 'Show posts from categories', 'franz-josef' ); ?></label>                            
                        </td>
                    </tr>
                    <tr class="row_slider_type_posts_pages<?php if ( $franz_settings['slider_type'] != 'posts_pages' ) echo ' hide'; ?>">
                        <th scope="row">
                            <label for="slider_specific_posts"><?php _e( 'Posts and/or pages to display', 'franz-josef' ); ?></label>
                        </th>
                        <td>
                            <input type="text" name="franz_settings[slider_specific_posts]" id="slider_specific_posts" value="<?php echo esc_attr( $franz_settings['slider_specific_posts'] ); ?>" size="60" class="wide code" /><br />
                            <span class="description">
							<?php _e( 'Enter ID of posts and/or pages to be displayed, separated by comma. Example: <code>1,13,45,33</code>', 'franz-josef' ); ?><br />
							<?php _e( 'Applicable only if <strong>Show specific posts/pages</strong> is selected above.', 'franz-josef' ); ?>
                            </span>                        
                        </td>
                    </tr>
                    <tr class="row_slider_type_categories<?php if ( $franz_settings['slider_type'] != 'categories' ) echo ' hide'; ?>">
                        <th scope="row">
                            <label for="slider_specific_categories"><?php _e( 'Categories to display', 'franz-josef' ); ?></label>
                        </th>
                        <td>
                            <select name="franz_settings[slider_specific_categories][]" id="slider_specific_categories" multiple="multiple" class="select-multiple chzn-select" data-placeholder="<?php esc_attr_e( 'Click to select categories or type to search', 'franz-josef' ); ?>">
                               <?php /* Get the list of categories */ 
                                    $selected_cats = $franz_settings['slider_specific_categories'];
                                    $categories = get_categories( array( 'hide_empty' => false ) );
                                    foreach ( $categories as $category) :
                                ?>
                                <option value="<?php echo $category->cat_ID; ?>" <?php if ( $selected_cats && in_array( $category->cat_ID, $selected_cats ) ) { echo 'selected="selected"'; }?>><?php echo $category->cat_name; ?></option>
                                <?php endforeach; ?> 
                            </select><br />
                            <span class="description"><?php _e( 'All posts within the categories selected here will be displayed on the slider. Usage example: create a new category "Featured" and assign all posts to be displayed on the slider to that category, and then select that category here.', 'franz-josef' ); ?></span>
                        </td>
                    </tr>
                    <tr class="row_slider_type_categories<?php if ( $franz_settings['slider_type'] != 'categories' ) echo ' hide'; ?>">
                        <th scope="row">
                            <label for="slider_specific_categories"><?php _e( 'Exclude the categories from posts listing', 'franz-josef' ); ?></label>
                        </th>
                        <td>
                        	<select name="franz_settings[slider_exclude_categories]" class="chzn-select">
                        		<option type="radio" name="franz_settings[slider_exclude_categories]" id="slider_exclude_categories_disabled" <?php selected( $franz_settings['slider_exclude_categories'], 'disabled' ); ?> value="disabled" data-toggleOptions="true"><?php _e( 'Disabled', 'franz-josef' ); ?></option>
                                <option type="radio" name="franz_settings[slider_exclude_categories]" id="slider_exclude_categories_frontpage" <?php selected( $franz_settings['slider_exclude_categories'], 'frontpage' ); ?> value="homepage" data-toggleOptions="true"><?php _e( 'Home Page', 'franz-josef' ); ?></option>
                                <option type="radio" name="franz_settings[slider_exclude_categories]" id="slider_exclude_categories_everywhere" <?php selected( $franz_settings['slider_exclude_categories'], 'everywhere' ); ?> value="everywhere" data-toggleOptions="true"><?php _e( 'Everywhere', 'franz-josef' ); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr class="row_slider_type_categories<?php if ( $franz_settings['slider_type'] != 'categories' ) echo ' hide'; ?>">
                        <th scope="row">
                            <label for="slider_random_category_posts"><?php _e( 'Show posts from categories in random order', 'franz-josef' ); ?></label>
                        </th>
                        <td>
                        	<input type="checkbox" name="franz_settings[slider_random_category_posts]" id="slider_random_category_posts" <?php checked( $franz_settings['slider_random_category_posts'] ); ?> value="true" data-toggleOptions="true" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php _e( 'Slider content', 'franz-josef' ); ?></label>
                        </th>
                        <td>
                            <input type="radio" name="franz_settings[slider_content]" value="excerpt" id="slider_content_excerpt" <?php checked( $franz_settings['slider_content'], 'excerpt' ); ?>/>
                            <label for="slider_content_excerpt"><?php _e( 'Excerpt', 'franz-josef' ); ?></label>
                            
                            <input type="radio" name="franz_settings[slider_content]" value="full_content" id="slider_content_full_content" <?php checked( $franz_settings['slider_content'], 'full_content' ); ?> style="margin-left: 25px" />
                            <label for="slider_content_full_content"><?php _e( 'Full content', 'franz-josef' ); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="slider_postcount"><?php _e( 'Number of posts to display', 'franz-josef' ); ?></label>
                        </th>
                        <td>
                            <input type="text" name="franz_settings[slider_postcount]" id="slider_postcount" value="<?php echo esc_attr( $franz_settings['slider_postcount'] ); ?>" size="3" />
                        </td>
                    </tr>
                    <tr class="non-essential-option">
                        <th scope="row">
                            <label for="slider_height"><?php _e( 'Slider height', 'franz-josef' ); ?></label>
                        </th>
                        <td>
                            <input type="text" name="franz_settings[slider_height]" id="slider_height" value="<?php echo esc_attr( $franz_settings['slider_height'] ); ?>" size="3" /> px                        
                        </td>
                    </tr>
                    <tr class="non-essential-option">
                        <th scope="row">
                            <label for="slider_interval"><?php _e( 'Slider interval', 'franz-josef' ); ?></label>
                        </th>
                        <td>
                            <input type="text" name="franz_settings[slider_interval]" id="slider_interval" value="<?php echo esc_attr( $franz_settings['slider_interval'] ); ?>" size="2" /> <?php _e( 'seconds', 'franz-josef' ); ?>
                        </td>
                    </tr>
                    <tr class="non-essential-option">
                        <th scope="row">
                            <label for="slider_trans_duration"><?php _e( 'Slider transition duration', 'franz-josef' ); ?></label>
                        </th>
                        <td>
                            <input type="text" name="franz_settings[slider_trans_duration]" id="slider_trans_duration" value="<?php echo esc_attr( $franz_settings['slider_trans_duration'] ); ?>" size="2" /> <?php _e( 'seconds', 'franz-josef' ); ?>
                        </td>
                    </tr>                
                </table>
                
                <p class="submit clearfix"><input type="submit" class="button" value="<?php esc_attr_e( 'Save All Options', 'franz-josef' ); ?>" /></p>
            </div>
        </div>
        
        
        <?php /* Front Page */ ?>
        <div class="postbox non-essential-option">
            <div class="head-wrap">
                <div title="<?php _e( 'Click to toggle', 'franz-josef' ); ?>" class="handlediv"><br /></div>
                <?php franz_docs_link( 'Front_page_options' ); ?>
        		<h3 class="hndle"><?php _e( 'Front Page', 'franz-josef' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	
                <table class="form-table">       	
                    <tr>
                        <th scope="row">
                            <label for="frontpage_posts_cats"><?php _e( 'Front page posts categories', 'franz-josef' ); ?></label>
                        </th>
                        <td>                        
                            <select name="franz_settings[frontpage_posts_cats][]" id="frontpage_posts_cats" multiple="multiple" class="select-multiple chzn-select" data-placeholder="<?php esc_attr_e( 'Click to select categories or type to search', 'franz-josef' ); ?>">
                                <?php /* Get the list of categories */ 
                                    $categories = get_categories( array( 'hide_empty' => false ) );
                                    foreach ( $categories as $category) :
                                ?>
                                <option value="<?php echo $category->cat_ID; ?>" <?php if ( in_array( $category->cat_ID, $franz_settings['frontpage_posts_cats'] ) ) {echo 'selected="selected"';}?>><?php echo $category->cat_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="description"><?php _e( 'Only posts that belong to the categories selected here will be displayed on the front page.', 'franz-josef' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="front_page_blog_columns"><?php _e( 'Blog posts columns', 'franz-josef' ); ?></label>
                        </th>
                        <td>
                        	<select name="franz_settings[front_page_blog_columns]" id="front_page_blog_columns" style="width:50px">
                            	<option value="2" <?php selected( 2, $franz_settings['front_page_blog_columns'] ); ?>>2</option>
                                <option value="3" <?php selected( 3, $franz_settings['front_page_blog_columns'] ); ?>>3</option>
                                <option value="4" <?php selected( 4, $franz_settings['front_page_blog_columns'] ); ?>>4</option>
                            </select>
                            <span class="description"></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="disable_full_width_post"><?php _e( 'Disable full-width first blog post', 'franz-josef' ); ?></label>
                        </th>
                        <td>                        
                        	<input type="checkbox" id="disable_full_width_post" name="franz_settings[disable_full_width_post]" <?php checked( $franz_settings['disable_full_width_post'] ); ?> value="true" />
                        </td>
                    </tr>
                </table>
                
				<?php if ( 'page' == get_option( 'show_on_front' ) ) : ?>
                <table class="form-table">       	
                    <tr>
                        <th scope="row">
                            <label for="disable_front_page_blog"><?php _e( "Don't show blog posts", 'franz-josef' ); ?></label>
                        </th>
                        <td>                        
                        	<input type="checkbox" id="disable_front_page_blog" name="franz_settings[disable_front_page_blog]" <?php checked( $franz_settings['disable_front_page_blog'] ); ?> value="true" /> <span class="description"><?php _e( 'Disable listing of blog posts on static front page', 'franz-josef' ); ?></span>
                        </td>
                    </tr>
                </table>
                <?php endif; ?>
                
                <p class="submit clearfix"><input type="submit" class="button" value="<?php esc_attr_e( 'Save All Options', 'franz-josef' ); ?>" /></p>
            </div>
        </div>
                
        
        <?php /* Social Profiles */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="<?php _e( 'Click to toggle', 'franz-josef' ); ?>" class="handlediv"><br /></div>
                <?php franz_docs_link( 'Social_Profiles_Options' ); ?>
        		<h3 class="hndle"><?php _e( 'Social Profiles', 'franz-josef' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                
                <h4 class="social-media-table"><?php _e( 'Social Media', 'franz-josef' ); ?></h4>
                <table class="form-table social-media-table">
                    <tr class="non-essential-option">
                        <th scope="row"><label for="social_media_new_window"><?php _e( 'Open social media links in new window', 'franz-josef' ); ?></label></th>
                        <td><input type="checkbox" name="franz_settings[social_media_new_window]" id="social_media_new_window" <?php checked( $franz_settings['social_media_new_window'] ); ?> value="true" /></td>    
                    </tr>
                    <tr class="non-essential-option">
                		<td colspan="2"><p><?php _e( 'Drag and drop to reorder.', 'franz-josef' ); ?></p></td>
                	</tr>
                    <tr class="non-essential-option">
                        <td colspan="2" id="social-profile-sortable">                            
                            <?php        
                                /*
								 * Available profiles according to the icons available in FontAwesome icon font
								 */
                                $available_profiles = array (
									'twitter'		=> 'Twitter',
									'facebook'		=> 'Facebook',
									'pinterest'		=> 'Pinterest',
									'linkedin'		=> 'LinkedIn',
									'youtube'		=> 'YouTube',
									'tumblr'		=> 'Tumblr',
									'dribble'		=> 'Dribble',
									'rss'			=> 'RSS',
									'github-alt'	=> 'Github',
									'google-plus'	=> 'Google Plus',
									'instagram'		=> 'Instagram'
								);
								sort( $available_profiles );
								$available_profiles = apply_filters( 'franz_social_profiles_list', array_merge( array( 'custom' => __( 'Custom', 'franz-josef' ) ), $available_profiles ) );
                                $social_profiles = ( ! empty( $franz_settings['social_profiles'] ) ) ? $franz_settings['social_profiles'] : array();
                            ?>
                            <?php 
								if ( ! in_array( false, $social_profiles) ) : 
								foreach ($social_profiles as $profile_key => $profile_data) :
									$profile_data['url'] = esc_url( $profile_data['url'] );
									if ( $profile_data['type'] == 'custom' ) 
										$profile_data['icon_url'] = esc_url( $profile_data['icon_url'] );
							?>
                                <table class="form-table social-profile-table">
                                    <tr>
                                        <th scope="row" rowspan="<?php echo $profile_data['type'] == 'custom' ? '4' : '2'; ?>" class="small-row social-profile-title">                            
                                            <?php if ( $profile_data['type'] == 'custom' ) _e( 'Custom', 'franz-josef' ); else echo $profile_data['name']; ?><br />
                                                <input type="hidden" name="franz_settings[social_profiles][<?php echo $profile_key; ?>][type]" value="<?php echo esc_attr( $profile_data['type'] ); ?>" />
                                                <input type="hidden" name="franz_settings[social_profiles][<?php echo $profile_key; ?>][name]" value="<?php echo esc_attr( $profile_data['name'] ); ?>" />
                                            <?php if ( $profile_data['type'] == 'custom' ) : if ( $profile_data['icon_fa'] ) : ?>
                                            	<i class="fa fa-<?php echo $profile_data['icon_fa']; ?>"></i>
                                            <?php else : ?>
	                                            <img class="mysocial-icon" src="<?php echo $profile_data['icon_url']; ?>" alt="" />
                                            <?php endif; else :  ?>
                                            	<i class="fa fa-<?php echo $profile_data['type']; ?>"></i>
                                            <?php endif; ?>
                                            <br /><span class="delete"><a href="#" class="socialprofile-del"><?php _e( 'Delete', 'franz-josef' ); ?></a></span>
                                        </th>
                                        <th class="small-row"><?php _e( 'Title attribute', 'franz-josef' ); ?></th>
                                        <td><input type="text" name="franz_settings[social_profiles][<?php echo $profile_key; ?>][title]" value="<?php echo esc_attr( $profile_data['title'] ); ?>" class="widefat code" /></td>
                                    </tr>
                                    <tr>
                                        <th class="small-row"><?php _e('URL', 'franz-josef'); ?></th>
                                        <td>
                                            <input type="text" name="franz_settings[social_profiles][<?php echo $profile_key; ?>][url]" value="<?php echo $profile_data['url']; ?>" class="widefat code"
                                            <?php if ( $profile_data['type'] == 'rss' ) echo 'placeholder="' . esc_attr__('Leave this field empty to use the default RSS URL.', 'franz-josef') . '"'; ?> />
                                    <?php if ( $profile_data['type'] == 'custom' ) : ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="small-row"><?php _e('FontAwesome icon', 'franz-josef'); ?></th>
                                        <td>
                                            <input type="text" name="franz_settings[social_profiles][<?php echo $profile_key; ?>][icon_fa]" value="<?php echo $profile_data['icon_fa']; ?>" class="widefat code" />                            
                                    <tr>
                                        <th class="small-row"><?php _e('Icon URL', 'franz-josef'); ?></th>
                                        <td>
                                            <input type="text" name="franz_settings[social_profiles][<?php echo $profile_key; ?>][icon_url]" value="<?php echo $profile_data['icon_url']; ?>" class="widefat code" />                            
                                    <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>            
							<?php endforeach; endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="social-profile-dragging">
                                <tr>
                                    <td colspan="2">
                                        <strong><?php _e( 'Add Social Media Profile', 'franz-josef' ); ?></strong>
                                        <input type="hidden" id="socialprofile-next-index" value="<?php echo count($social_profiles)+1; ?>" />
                                        <input type="hidden" id="new-socialprofile-data" 
                                                data-icon-url="<?php echo esc_attr( esc_url( FRANZ_ROOTURI ) . '/images/social/' ); ?>"
                                                data-custom-title="custom"
                                                data-text-fa-icon="<?php esc_attr_e('FontAwesome icon', 'franz-josef'); ?>"
                                                data-text-icon-url="<?php esc_attr_e('Icon URL', 'franz-josef'); ?>"
                                                data-text-title-attr="<?php esc_attr_e('Title attribute', 'franz-josef'); ?>"
                                                data-text-url="<?php esc_attr_e('URL', 'franz-josef'); ?>"
                                                data-text-delete="<?php esc_attr_e( 'Delete', 'franz-josef' ); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php _e( 'Type', 'franz-josef' ); ?></th>
                                    <td>
                                        <select id="new-socialprofile-type" data-placeholder="<?php esc_attr_e( 'Choose type', 'franz-josef' ); ?>" class="chzn-select">
                                            <option value=""></option>
                                            <?php foreach ( $available_profiles as $profile_type) : ?>                                
                                                <option value="<?php echo esc_attr( sanitize_title( $profile_type ) ); ?>"><?php echo $profile_type; ?></option>
                                            <?php endforeach; ?>
                                        </select>                            
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php _e('Title attribute', 'franz-josef'); ?></th>
                                    <td><input type="text" id="new-socialprofile-title" class="widefat code" /></td>
                                </tr>
                                <tr>
                                    <th><?php _e('URL', 'franz-josef'); ?></th>
                                    <td><input type="text" id="new-socialprofile-url" class="widefat code" />
                                        <span id="new-socialprofile-url-description" class="hide"><?php _e('Leave the URL empty to use the default RSS URL.', 'franz-josef'); ?></span>
                                    </td>
                                </tr>
                                <tr class="hide">
                                    <th><?php _e('FontAwesome icon', 'franz-josef'); ?> <a href="http://fortawesome.github.io/Font-Awesome/icons/#brand" title="<?php esc_attr_e( 'FontAwesome icons are great for retina displays. Click to see a list of FontAwesome icons.', 'franz-josef' ); ?>" target="_blank"><i class="fa fa-info-circle" style="font-size: 16px"></i></a></th>
                                    <td><input type="text" id="new-socialprofile-faicon" class="widefat code" value="" placeholder="<?php esc_attr_e( 'Example: soundcloud', 'franz-josef' ); ?>" /></td>
                                </tr>
                                <tr class="hide">
                                    <th><?php _e('Icon URL', 'franz-josef'); ?></th>
                                    <td><input type="text" id="new-socialprofile-iconurl" class="widefat code" value="" placeholder="<?php esc_attr_e( '(optional if using FontAwesome icon above)', 'franz-josef' ); ?>" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><a href="#" id="new-socialprofile-add" class="button"><?php _e( 'Add this social media profile', 'franz-josef' ); ?></a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                
                <p class="submit clearfix"><input type="submit" class="button" value="<?php esc_attr_e( 'Save All Options', 'franz-josef' ); ?>" /></p>
            </div>
        </div>
        
        
        <?php /* Mentions Bar */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="<?php _e( 'Click to toggle', 'franz-josef' ); ?>" class="handlediv"><br /></div>
                <?php franz_docs_link( 'Mentions_Bar' ); ?>
                <h3 class="hndle"><?php esc_html_e( 'Mentions Bar', 'franz-josef' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	<p><?php _e( 'Showcase the awards, reviews, or mentions you\'ve received from other organisations or those you are affiliated to.', 'franz-josef' ); ?></p>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                        	<label for="brand_icons"><?php esc_html_e( 'Brand Icons', 'franz-josef' ); ?></label>
                            <p><?php _e( 'To ensure sharp images on retina devices, use images with double the size that you\'d like them to appear.', 'franz' ); ?></p>
                        </th>
                        <td>
                        	<?php _e( 'Drag and drop to reorder.', 'franz-josef' ); ?>
                            <ul id="brand_icons">
                                <?php $i = 0; foreach ( $franz_settings['brand_icons'] as $brand_icon ) : ?>
                                    <li class="clearfix">
                                        <div class="left"><?php echo wp_get_attachment_image( $brand_icon['image_id'], 'full' ); ?></div>
                                        <input type="hidden" name="franz_settings[brand_icons][<?php echo $i; ?>][image_id]" value="<?php echo $brand_icon['image_id']; ?>" id="brand_icon_<?php echo $i; ?>" />
                                        <label for="brand_icon_link_<?php echo $i; ?>"><?php _e( 'Link', 'franz-josef' ); ?></label>
                                        <input id="brand_icon_link_<?php echo $i; ?>" type="text" name="franz_settings[brand_icons][<?php echo $i; ?>][link]" value="<?php echo $brand_icon['link']; ?>" class="code" placeholder="<?php esc_attr_e( '(optional)', 'franz-josef' ); ?>" size="60" />
                                        <a data-field="brand_icon_<?php echo $i; ?>" data-title="<?php esc_attr_e( 'Select Image', 'franz-josef' ); ?>" data-button="<?php esc_attr_e( 'Select image', 'franz-josef' ); ?>" href="#" class="media-upload button"><?php _e( 'Select image', 'franz-josef' );?></a>
                                        <span class="delete"><a href="#"><?php _e( 'Delete', 'franz-josef' ); ?></a></span>
                                    </li>
                                <?php $i++; endforeach; ?>
                                
                                <li class="clearfix">
                                    <div class="left"><span class="image-placeholder"></span></div>
                                    <input type="hidden" name="franz_settings[brand_icons][<?php echo $i; ?>][image_id]" value="" data-count="<?php echo $i; ?>" id="brand_icon_<?php echo $i; ?>" />
                                    <label for="brand_icon_link_<?php echo $i; ?>"><?php _e( 'Link', 'franz-josef' ); ?></label>
                                    <input id="brand_icon_link_<?php echo $i; ?>" type="text" name="franz_settings[brand_icons][<?php echo $i; ?>][link]" value="" class="code" placeholder="<?php esc_attr_e( '(optional)', 'franz-josef' ); ?>" size="60" />
                                    <a data-field="brand_icon_<?php echo $i; ?>" data-title="<?php esc_attr_e( 'Select Image', 'franz-josef' ); ?>" data-button="<?php esc_attr_e( 'Select image', 'franz-josef' ); ?>" href="#" class="media-upload button"><?php _e( 'Select image', 'franz-josef' );?></a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </table>
                
                <p class="submit clearfix"><input type="submit" class="button" value="<?php esc_attr_e( 'Save All Options', 'franz-josef' ); ?>" /></p>
            </div>
        </div>
        
        
        <?php /* Custom <head> tags */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="<?php _e( 'Click to toggle', 'franz-josef' ); ?>" class="handlediv"><br /></div>
                <?php franz_docs_link( 'Custom_Head_Tags' ); ?>
                <h3 class="hndle"><?php esc_html_e( 'Custom <head> Tags', 'franz-josef' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="head_tags"><?php esc_html_e( 'Code to insert into the <head> element', 'franz-josef' ); ?></label></th>
                        <td><textarea name="franz_settings[head_tags]" id="head_tags" cols="60" rows="7" class="widefat code"><?php echo htmlentities( stripslashes( $franz_settings['head_tags'] ) ); ?></textarea></td>
                    </tr>
                </table>
                <script type="text/javascript">
                    var customHeadTags = CodeMirror.fromTextArea(document.getElementById("head_tags"), {
						mode			: 'htmlmixed',
						lineNumbers		: true,
						lineWrapping	: true,
						indentUnit		: 4,
						styleActiveLine	: true
					});
                </script>
                
                <p class="submit clearfix"><input type="submit" class="button" value="<?php esc_attr_e( 'Save All Options', 'franz-josef' ); ?>" /></p>
            </div>
        </div>  
    
        
        <?php /* Footer */ ?>
        <div class="postbox non-essential-option">
            <div class="head-wrap">
                <div title="<?php _e( 'Click to toggle', 'franz-josef' ); ?>" class="handlediv"><br /></div>
                <?php franz_docs_link( 'Footer_Options' ); ?>
        		<h3 class="hndle"><?php _e( 'Footer', 'franz-josef' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">       	
                    <tr>
                        <th scope="row"><label for="copyright_text"><?php _e( "Copyright text (html allowed)", 'franz-josef' ); ?></label>
                        <br /><small><?php _e( 'If this field is empty, the following default copyright text will be displayed:', 'franz-josef' ); ?></small>
                        <?php /* translators: %1$s = date, %2$s = site name. */ ?>
                        <p style="background-color:#fff;padding:5px;border:1px solid #ddd;"><small><?php printf( __( '&copy; %1$s %2$s. All rights reserved.', 'franz-josef' ), date( 'Y' ), get_bloginfo( 'name' ) ); ?></small></p>
                        </th>
                        <td><textarea name="franz_settings[copyright_text]" id="copyright_text" cols="60" rows="4"><?php echo stripslashes( $franz_settings['copyright_text'] ); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="hide_copyright"><?php _e( 'Do not show copyright info', 'franz-josef' ); ?></label></th>
                        <td><input type="checkbox" name="franz_settings[hide_copyright]" id="hide_copyright" <?php checked( $franz_settings['hide_copyright'] ); ?> value="true" /></td>
                    </tr>
                </table>
                <script type="text/javascript">
                    var copyrightText = CodeMirror.fromTextArea(document.getElementById("copyright_text"), {
						mode			: 'htmlmixed',
						lineNumbers		: true,
						lineWrapping	: true,
						indentUnit		: 4,
						styleActiveLine	: true
					});
					copyrightText.setSize(null, 100);
                </script>
                
                <p class="submit clearfix"><input type="submit" class="button" value="<?php esc_attr_e( 'Save All Options', 'franz-josef' ); ?>" /></p>
            </div>
        </div> 
        
        
        <?php /* Print */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="<?php _e( 'Click to toggle', 'franz-josef' ); ?>" class="handlediv"><br /></div>
                <?php franz_docs_link( 'Print_Options' ); ?>
        		<h3 class="hndle"><?php _e( 'Print', 'franz-josef' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="print_css"><?php _e( 'Enable print CSS', 'franz-josef' ); ?></label></th>
                        <td><input type="checkbox" name="franz_settings[print_css]" id="print_css" <?php checked( $franz_settings['print_css'] ); ?> value="true" data-toggleOptions="true" /></td>
                    </tr> 
                </table>
                <table class="form-table<?php if ( $franz_settings['print_css'] == false ) echo ' hide'; ?>"> 
                    <tr>
                        <th scope="row"><label for="print_button"><?php _e( 'Show print button', 'franz-josef' ); ?></label></th>
                        <td><input type="checkbox" name="franz_settings[print_button]" id="print_button" <?php checked( $franz_settings['print_button'] ); ?> value="true" /></td>                        
                    </tr>
                </table>
                
                <p class="submit clearfix"><input type="submit" class="button" value="<?php esc_attr_e( 'Save All Options', 'franz-josef' ); ?>" /></p>
            </div>
        </div>

<?php } // Closes the franz_options_general() function definition