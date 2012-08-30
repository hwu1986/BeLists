<div class="sidebar col3">
	<div class="listing-info listing-details">	
		<h4 class="widget-title"><?php _e('User Options', 'colabstemes');?></h4>
		<ul class="users-listing">
		<?php if ( is_user_logged_in() ) : 
		$logout_url = wp_logout_url( home_url() );
		?>
			
			<li><a href="<?php echo CL_DASHBOARD_URL ?>"><?php _e('My Dashboard','colabsthemes')?></a></li>
			<li><a href="<?php echo CL_PROFILE_URL ?>"><?php _e('Edit Profile','colabsthemes')?></a></li>
			<?php if (current_user_can('edit_others_posts')) { ?><li><a href="<?php echo get_option('siteurl'); ?>/wp-admin/"><?php _e('WordPress Admin','colabsthemes')?></a></li><?php } ?>
			<li><a href="<?php echo $logout_url; ?>"><?php _e('Log Out','colabsthemes')?></a></li>
			
		<?php else: ?>
			<li><?php _e('Welcome,','colabsthemes'); ?> <strong><?php _e('visitor!','colabsthemes'); ?></strong></li>
            <li><a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=register"><?php _e('Register','colabsthemes'); ?></a></li>
            <li><a href="<?php echo get_option('siteurl'); ?>/wp-login.php"><?php _e('Log in','colabsthemes'); ?></a></li>
		<?php endif; ?>
		</ul>
	</div>
	<?php 
        $current_user = wp_get_current_user();
        $display_user_name = $current_user->display_name;

		// calculate the total count of live ads for current user
		$rows = $wpdb->get_results( $wpdb->prepare( "
			SELECT post_status, COUNT(ID) as count
			FROM $wpdb->posts
			WHERE post_author = %d
			AND post_type = '".COLABS_POST_TYPE."'
			GROUP BY post_status", $current_user->ID
		) );

		$stats = array();
		foreach ( $rows as $row )
			$stats[ $row->post_status ] = $row->count;

		$post_count_live = (int) @$stats['publish'];
		$post_count_pending = (int) @$stats['pending'];
		$post_count_offline = (int) @$stats['draft'];
		$post_count_total = $post_count_live + $post_count_pending + $post_count_offline;

    ?>
        

    <div class="widget widget_welcome">

        <?php if ( !is_user_logged_in() ) : ?>

              <?php echo get_option('colabs_ads_welcome_msg'); ?>          
              <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=register" class="btn btn-primary"><?php _e('Join Now', 'colabsthemes') ?></a>
              <span>or</span>
              <a href="<?php echo get_option('siteurl'); ?>/wp-login.php" class="btn btn-primary"><?php _e('Log In', 'colabsthemes') ?></a>
              
          <?php else: ?>
              <h4 class="widget-title"><?php _e('Your Profile', 'colabstemes');?></h4>
              <div class="avatar"><?php colabsthemes_get_profile_pic( $current_user->ID, $current_user->user_email, 60 ) ?></div>

              <div class="user">

                  <p class="welcome-back"><?php _e('Welcome back,','colabsthemes'); ?> <a href="<?php echo get_author_posts_url($current_user->ID); ?>"><strong><?php echo $display_user_name; ?></strong></a></p>
                  <p class="last-login"><?php _e('You last logged in at:','colabsthemes'); ?> <?php echo colabsthemes_get_last_login($current_user->ID); ?></p>
                  
	             </div><!-- /user -->
               

	    <?php endif; ?>

    </div><!-- /.widget_welcome -->
	
	<?php if ( is_user_logged_in() ) : ?>
	<div class="widget widget_membership">
		<ul class="user-info">	
			<li><strong><?php _e('Member Since:','colabsthemes')?></strong> <?php echo colabsthemes_get_reg_date($current_user->user_registered); ?></li>
			<li><strong><?php _e('Total Listing:','colabsthemes')?></strong> <?php echo $post_count_total; ?></li>
			<?php $membership = get_pack($current_user->active_membership_pack); ?>
			<?php if(get_option('colabs_enable_membership_packs') == 'yes') : ?>
			<?php if($membership) : ?>
					<li><strong><?php _e('Membership Pack','colabsthemes')?>:</strong> <?php echo stripslashes($membership->pack_name); ?></li>
					<li><strong><?php _e('Membership Expires','colabsthemes')?>:</strong> <?php echo date_i18n( get_option('date_format').' '.get_option('time_format'),strtotime($current_user->membership_expires), get_option('gmt_offset') ); ?></li>
					<li><a href="<?php echo COLABS_MEMBERSHIP_BUY_URL; ?>"><?php _e('Renew or Extend Your Membership Pack','colabsthemes'); ?></a></li>
			<?php else : ?>
					<li><a href="<?php echo COLABS_MEMBERSHIP_BUY_URL; ?>"><?php _e('Purchase a Membership Pack','colabsthemes'); ?></a></li>
			<?php endif; //end if $membership exists ?>
			<?php endif; //end if cp_enable_membership_packs ?>
		</ul>
	</div>
	
	<div class="widget user-details">
		<ul >
			<li><div class="email"></div><a href="mailto:<?php echo $current_user->user_email; ?>"><?php _e('Email','colabsthemes')?></a></li>
			<li><div class="twitter"></div><?php if($current_user->twitter_id) { ?><a href="http://twitter.com/<?php echo esc_attr( $current_user->twitter_id ); ?>" target="_blank"><?php _e('Twitter','colabsthemes')?></a><?php } else { _e('N/A','colabsthemes'); } ?></li>
			<li><div class="facebook"></div><?php if($current_user->facebook_id) { ?><a href="http://facebook.com/<?php echo esc_attr( $current_user->facebook_id ); ?>" target="_blank"><?php _e('Facebook','colabsthemes')?></a><?php } else { _e('N/A','colabsthemes'); } ?></li>
			<li><div class="site"></div><?php if($current_user->user_url) { ?><a href="<?php echo esc_attr( $current_user->user_url ); ?>" target="_blank"><?php echo esc_html( $current_user->user_url ); ?></a><?php } else { _e('N/A','colabsthemes'); } ?></li>
		</ul>
	</div>	
	
	<div class="widget account_statistic">
		<h4 class="widget-title"><?php _e('Account Statistic', 'colabstemes');?></h4>
		<ul>

			<li><?php _e('Live Listings:','colabsthemes')?> <strong><?php echo $post_count_live; ?></strong></li>
			<li><?php _e('Pending Listings:','colabsthemes')?> <strong><?php echo $post_count_pending; ?></strong></li>
			<li><?php _e('Offline Listings:','colabsthemes')?> <strong><?php echo $post_count_offline; ?></strong></li>
			<li><?php _e('Total Listings:','colabsthemes')?> <strong><?php echo $post_count_total; ?></strong></li>

		</ul>
	</div>		
	<?php endif; ?>

</div>
<!-- .sidebar -->