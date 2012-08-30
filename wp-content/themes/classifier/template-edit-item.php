<?php
/*
 * Template Name: User Edit Item
 *
 * This template must be assigned to the edit-item page
 * in order for it to work correctly
 *
*/
$debugOn = array();
$the_msg = '';
auth_redirect_login(); // if not logged in, redirect to login page
nocache_headers(); // don't cache anything

global $wpdb;
$current_user = wp_get_current_user(); // grabs the user info and puts into vars

// needed for image uploading and deleting to work
if (defined('ABSPATH')) {
    include_once (ABSPATH . 'wp-admin/includes/file.php');
    include_once (ABSPATH . 'wp-admin/includes/image.php');
} else {
    include_once ('../wp-admin/includes/file.php');
    include_once ('../wp-admin/includes/image.php');
}


// check to see if the form has been posted.
// if so then update the ad and images
if((!empty($_POST['submit'])) && current_user_can('edit_posts')) {
	
    // delete any images checked
    if (!empty($_POST['image'])) 
        colabs_delete_image();

	// update the image alt text
	if (!empty($_POST['attachments'])) 
	    colabs_update_alt_text();

    // check to see if an image needs to be uploaded
    // hack since we just check if array keys are empty for 6 keys
    if(!empty($_FILES['image']['tmp_name'][0]) || !empty($_FILES['image']['tmp_name'][1]) || !empty($_FILES['image']['tmp_name'][2]) || !empty($_FILES['image']['tmp_name'][3]) || !empty($_FILES['image']['tmp_name'][4]) || !empty($_FILES['image']['tmp_name'][5])) {

        // check for valid the image extensions and sizes
        $error_msg = colabs_validate_image();

        // images are valid
        if(!$error_msg) {

            $imagecount = colabs_count_ad_images($_POST['ad_id']); //1
            $maximages = get_option('colabs_num_images'); //2

            // only allow the max number of images to each ad. prevents page reloads adding more
            if ($maximages > $imagecount) {
                // create the array that will hold all the post values
                $postvals = array();

                // now upload the new image
                $postvals = colabs_process_new_image($_POST['ad_id']);

                // associate the already uploaded images to the ad and create multiple image sizes
                $attach_id = colabs_associate_images($_POST['ad_id'], $postvals['attachment']);
            }

        } else {

            // images didn't upload
            $the_msg = colabsthemes_error_msg($error_msg);

        }

    }

    // update the ad content
    $the_msg .= colabs_update_listing();

}


// MAIN PAGE STARTS HERE -->

// get the ad id from the querystring.
$aid = colabsthemes_numbers_only($_GET['aid']);

// make sure the ad id is legit otherwise set it to zero which will return no results
if (!empty($aid)) $aid = $aid; else $aid = '0';

// select post information and also category with joins.
// filtering based off current user id which prevents people from trying to hack other peoples ads
$sql = $wpdb->prepare("SELECT wposts.*, $wpdb->term_taxonomy.term_id "
     . "FROM $wpdb->posts wposts "
     . "LEFT JOIN $wpdb->term_relationships ON($aid = $wpdb->term_relationships.object_id) "
     . "LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id) "
     . "LEFT JOIN $wpdb->terms ON($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id) "
     . "WHERE ID = %s AND $wpdb->term_taxonomy.taxonomy = '".COLABS_TAX_CAT."' "
     //. "AND post_status <> 'draft' "// turned off to allow "paused" ads to be editable, uncomment to disable editing of paused ads
     . "AND post_author = %s",
     $aid,
     $current_user->ID);

// pull ad fields from db
$getad = $wpdb->get_row($sql);

// add js files to wp_head. tiny_mce and validate
// this function is in /includes/theme-enqueue.php
add_action('wp_print_scripts', 'colabs_load_form_scripts');
?>


<?php get_header(); ?>

<script type='text/javascript'>
// <![CDATA[
jQuery(document).ready(function(){

	/* setup the form validation */
	jQuery("#mainform").validate({
		errorClass: 'invalid',
		errorPlacement: function(error, element) {
			if (element.attr('type') == 'checkbox' || element.attr('type') == 'radio') {
				element.closest('ol').before(error);
			} else {
				error.insertBefore(element);
			}		
        }	
	});

	/* setup the tooltip */
    jQuery("#mainform a").easyTooltip();

});


/* General Trim Function Based on Fastest Executable Trim */
function trim (str) {
    var	str = str.replace(/^\s\s*/, ''),
            ws = /\s/,
            i = str.length;
    while (ws.test(str.charAt(--i)));
    return str.slice(0, i + 1);
}

/* Used for enabling the image for uploads */
function enableNextImage($a, $i) {
    jQuery('#upload'+$i).removeAttr("disabled");
}



// ]]>
</script>

<style type="text/css">
.form_edit label.invalid {
    display: none;
}
</style>

<?php
// call tinymce init code if html is enabled
if (get_option('colabs_allow_html') == 'true')
    colabsthemes_tinymce($width=520, $height=400);
?>

<script type='text/javascript'>
// <![CDATA[
jQuery(document).ready(function(){

	/* setup the form validation */
	jQuery("#mainform").validate({
		errorClass: "invalid"
	});
	
});	
	
// ]]>
</script>	

<!-- #Main Starts -->
<?php colabs_main_before(); ?>
<div class="row main-container">

    <div class="main-content col9">

        <header class="entry-header">
            <h2><?php _e('Edit Your Ad', 'colabsthemes'); ?></h2>
        </header>
			
		<div <?php post_class(); ?>>
        
        <div class="shadowblock">

            <?php if ($getad && (get_option('colabs_ad_edit') == 'true') ): ?>

                <p><?php _e('Edit the fields below and click save to update your ad. Your changes will be updated instantly on the site.', 'colabsthemes');?></p>

                <?php echo $the_msg; ?>

                <form name="mainform" id="mainform" class="form_edit form-submit-listing" action="" method="post" enctype="multipart/form-data">

                    <ol>

                    <?php
                    // we first need to see if this ad is using a custom form
                    // so lets search for a catid match and return the id if found
                    $fid = colabs_get_form_id($getad->term_id);                


                    // if there's no form id it must mean the default form is being used so let's go grab those fields
                    if(!($fid)) {

                        // use this if there's no custom form being used and give us the default form
                        $sql = $wpdb->prepare("SELECT field_label, field_name, field_type, field_values, field_tooltip, field_req "
                             . "FROM ". $wpdb->prefix . "colabs_ad_fields "
                             . "WHERE field_core = '1' "
                             . "ORDER BY field_id asc");

                    } else {

                        // now we should have the formid so show the form layout based on the category selected
                        $sql = $wpdb->prepare("SELECT f.field_label, f.field_name, f.field_type, f.field_values, f.field_perm, f.field_tooltip, m.meta_id, m.field_pos, m.field_req, m.form_id "
                             . "FROM ". $wpdb->prefix . "colabs_ad_fields f "
                             . "INNER JOIN ". $wpdb->prefix . "colabs_ad_meta m "
                             . "ON f.field_id = m.field_id "
                             . "WHERE m.form_id = %s "
                             . "ORDER BY m.field_pos asc",
                             $fid);

                    }

                    $results = $wpdb->get_results($sql);

                    if($results) {
                        // build the edit ad form
                        colabs_edit_ad_formbuilder($results, $getad);
                    }

                    if(!get_post_meta($aid, 'images', true)) {

                        // check and make sure images are allowed
                        if(get_option('colabs_ad_images') == 'true') {
                            $imagecount = colabs_get_ad_images($getad->ID);

                            // print out image upload fields. pass in count of images allowed
                            echo colabs_ad_edit_image_input_fields($imagecount);
                        }

                    } else { ?>

                        <div class="pad10"></div>
                            <li>
								<div class="labelwrapper">
                                	<label><?php _e('Images', 'colabsthemes') ?>:</label><?php _e('Sorry, image editing is not supported for this ad.', 'colabsthemes') ?>
                                </div>
                            </li>
                        <div class="pad25"></div>

                    <?php
                    }
                    ?>


                    <p class="submit center">
                        <input type="button" class="btn btn-primary" onclick="window.location.href='<?php echo CL_DASHBOARD_URL ?>'" value="<?php _e('Cancel', 'colabsthemes')?>" />&nbsp;&nbsp;
                        <input type="submit" class="btn btn-primary" value="<?php _e('Update Ad &raquo;','colabsthemes') ?>" name="submit" />
                    </p>


                </ol>

                <input type="hidden" name="ad_id" value="<?php echo $getad->ID; ?>" />

            </form>


        <?php else : ?>

            <p class="text-center"><?php _e('You have entered an invalid ad id or do not have permission to edit that ad.', 'colabsthemes');?></p>

        <?php endif; ?>


    </div><!-- /shadowblock -->
    
    </div><!-- /post_class -->
                
    </div><!-- /.main-content -->  
		
	<?php get_sidebar('user'); ?>
		
</div><!-- /.main-container -->
	
<?php colabs_main_after(); ?>
  
<?php get_footer(); ?>

