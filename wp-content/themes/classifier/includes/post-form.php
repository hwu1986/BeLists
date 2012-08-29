<?php
/**
 *
 * New Post Form for Custom Post Types for the Frontend of Your Site
 * By ColorLabs
 *
 * Last Updated: 10/25/2011
 */
 
// Check if the form was submitted
if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] )) {
	
	// Do some minor form validation to make sure there is content
	if (isset ($_POST['title'])) { 
		$title =  $_POST['title']; 
	} else { 
		echo 'Please enter a title';
	}
	if (isset ($_POST['description'])) { 
		$description = $_POST['description']; 
	} else { 
		echo 'Please enter the content'; 
	}
	
	$poststatus=get_option('colabs_post_status');
	if($poststatus=='')$poststatus='pending';

	// Add the content of the form to $post as an array
	$post = array(
		'post_title'	=> $title,
		'post_content'	=> $description,
		'post_category'	=>array( (int)$_POST['cat'] ),  // Usable for custom taxonomies too
		'post_status'	=> $poststatus,			// Choose: publish, preview, future, etc.
		'post_type'		=> $_POST['post_type']  // Use a custom post type if you want to
	);
	//wp_insert_post($post);  // Pass  the value of $post to WordPress the insert function
							// http://codex.wordpress.org/Function_Reference/wp_insert_post
	//update_post_meta($post, $_POST['price'], true);		
	$newPost = wp_insert_post($post);
	
	//insert image
	require_once(ABSPATH . 'wp-admin/includes/admin.php');  
    $attachmentId = media_handle_upload('async-upload', 1199); //post id of Client Files page 
	add_post_meta($newPost, 'image', wp_get_attachment_url($attachmentId));
	add_post_meta($newPost, 'colabs_single_top', 'single_image');
	//set post meta
	add_post_meta($newPost, 'email', $_POST['email']);
	add_post_meta($newPost, 'price', $_POST['price']);
	add_post_meta($newPost, 'expired', $_POST['expired']);
	add_post_meta($newPost, 'phone', $_POST['phone']);
	add_post_meta($newPost, 'location', $_POST['location']);
	add_post_meta($newPost, 'website', $_POST['website']);
	add_post_meta($newPost, 'map', $_POST['map']);
	//set the custom post type categories
	wp_set_post_terms( $newPost, $_POST['cat'], 'ad_category');
	
	wp_redirect( home_url() ); // redirect to home page after submit
	// Do the wp_insert_post action to insert it
	do_action('wp_insert_post', 'wp_insert_post');
	
} // end IF

?>

<!-- New Post Form -->
<div id="postbox">
	<form id="new_post" name="new_post" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
	<h2><?php _e('Submit Post','colabsthemes');?></h2>
	<div class="left">
		<p><label for="title"><?php _e('Title', 'colabsthemes'); ?> *</label><br />
		<input class="required" type="text" id="title" value="" tabindex="1" size="20" name="title" />
		</p>
		<p><label for="async-upload"><?php _e('Image', 'colabsthemes'); ?> *</label><br />
		<input class="required" type="file" id="async-upload" value="" tabindex="1" size="20" name="async-upload" />
		</p>
		<p><label for="description"><?php _e('Description', 'colabsthemes'); ?> *</label><br />
		<textarea class="required" id="description" tabindex="3" name="description" cols="35" rows="6"></textarea>
		</p>
		<p><label for="cat"><?php _e('Ad Category', 'colabsthemes'); ?></label><br />
		<?php wp_dropdown_categories( 'show_option_none=Category&tab_index=4&taxonomy=ad_category' ); ?>
		</p>
		<p><label for="email"><?php _e('Email', 'colabsthemes'); ?> *</label><br />
		<input class="required" type="text" id="email" value="" tabindex="1" size="20" name="email" />
		</p>
		<p><label for="price"><?php _e('Price', 'colabsthemes'); ?></label><br />
		<input type="text" id="price" value="" tabindex="1" size="20" name="price" />
		</p>	
		
	</div>

	<div class="right">
		<p><label for="expired"><?php _e('Expired in', 'colabsthemes'); ?></label><br />
		<input type="text" id="expired" value="" tabindex="1" size="20" name="expired" />
		<span class="submit_description"><?php _e('Enter the expired of ad e.g. October 30,2011', 'colabsthemes'); ?></span>
		</p>
		<p><label for="phone"><?php _e('Phone', 'colabsthemes'); ?></label><br />
		<input type="text" id="phone" value="" tabindex="1" size="20" name="phone" />
		</p>
		<p><label for="location"><?php _e('Location', 'colabsthemes'); ?></label><br />
		<input type="text" id="location" value="" tabindex="1" size="20" name="location" />
		</p>
		<p><label for="website"><?php _e('Website', 'colabsthemes'); ?></label><br />
		<input type="text" id="website" value="" tabindex="1" size="20" name="website" />
		<span class="submit_description"><?php _e('Enter the website of ad e.g. http://colorlabsproject.com', 'colabsthemes'); ?></span>
		</p>
		<p><label for="map"<?php _e('Google map', 'colabsthemes'); ?>></label><br />
		<input type="text" id="map" value="" tabindex="1" size="20" name="map" />
		<span class="submit_description"><?php _e('The map should ly using previously entered data.', 'colabsthemes'); ?></span>
		</p>
	</div>

	<p><input type="submit" value="Publish" tabindex="6" /></p>
	
	<input type="hidden" name="post_type" id="post_type" value="ad" />
	<input type="hidden" name="action" value="post" />
	<?php wp_nonce_field( 'new-post' ); ?>
	</form>
</div>
<!--// New Post Form -->