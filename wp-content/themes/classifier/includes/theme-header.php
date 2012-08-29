<?php

/**
 * Add header elements via the colabs_header hook
 *
 * Anything you add to this file will be dynamically
 * inserted in the header of your theme
 *
 * @since 1.0.0
 * @uses colabs_header_before
 *
 */

// add the main header
function colabs_cl_header() {

?>
   
    <section class="container section-topbar">
      <div class="row">
    
        <ul class="account-bar">
        
        <?php echo colabs_login_head(); ?>
        
        </ul>
        <!-- /.account-bar -->
    
        <?php if(get_option('colabs_ad_location_filter')=='true' ){ ?>
        <div class="location-changer">
          <span class="label"><?php _e('Select Your Location','colabsthemes'); ?></span>
          <?php colabs_dropdown_location('show_option_all='.__('All Location', 'colabsthemes').'&title_li=&use_desc_for_title=1&tab_index=2&name=location&selected=&class=custom-select&taxonomy='.COLABS_TAX_LOC.'&echo=false'); ?>
        </div>
		<script type="text/javascript">
			var dropdown = document.getElementById("location");
			function onCatChange() {
        location.href = "<?php echo get_option('home');?>/?ad_location="+dropdown.options[dropdown.selectedIndex].value;
			}
			dropdown.onchange = onCatChange;
	   </script>
        <!-- /.location-changer -->
        <?php } ?>
        
      </div>
    </section>
    <!-- /.section-topbar -->

<?php
}
// hook into the correct action
add_action('colabs_header_before', 'colabs_cl_header');


// display the login message in the header
if (!function_exists('colabs_login_head')) {
    function colabs_login_head() {
        if (is_user_logged_in()) :
			global $current_user;
			$current_user = wp_get_current_user();	
			$logout_url = wp_logout_url( home_url() );
			$display_user_name = $current_user->display_name;
			?>
			<li><?php _e('Welcome,','colabsthemes'); ?> <strong><?php echo $display_user_name; ?></strong></li>
            <li><a href="<?php echo CL_DASHBOARD_URL ?>"><?php _e('Manage My Ads','colabsthemes'); ?></a></li>
            <li><a href="<?php echo $logout_url; ?>"><?php _e('Log out','colabsthemes'); ?></a></li>
		<?php else : ?>
			<li><?php _e('Welcome,','colabsthemes'); ?> <strong><?php _e('visitor!','colabsthemes'); ?></strong></li>
            <li><a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=register"><?php _e('Register','colabsthemes'); ?></a></li>
            <li><a href="<?php echo get_option('siteurl'); ?>/wp-login.php"><?php _e('Log in','colabsthemes'); ?></a></li>
        <?php endif; ?>
            <li><a href="<?php echo CL_ADD_NEW_URL ?>"><?php _e('Post an Ad','colabsthemes'); ?></a></li>
            <?php
    }
}

?>