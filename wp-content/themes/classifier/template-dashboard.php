<?php
/*
 * Template Name: User Dashboard
 *
 * This template must be assigned to a page
 * in order for it to work correctly
 *
*/

auth_redirect_login(); // if not logged in, redirect to login page
nocache_headers();

$current_user = wp_get_current_user(); // grabs the user info and puts into vars

$display_user_name = $current_user->display_name;


// include the payment gateway code
include_once (TEMPLATEPATH . '/includes/gateways/paypal/paypal.php');


// check to see if we want to pause or restart the ad
if(!empty($_GET['action'])) :
    $d = trim($_GET['action']);
    $aid = trim($_GET['aid']);

    // make sure author matches ad. Prevents people from trying to hack other peoples ads
    $sql = $wpdb->prepare("SELECT wposts.post_author "
       . "FROM $wpdb->posts wposts "
       . "WHERE ID = %s "
       . "AND post_author = %s",
       $aid,
       $current_user->ID);

    $checkauthor = $wpdb->get_row($sql);

    if($checkauthor != null) { // author check is ok. now update ad status

        if ($d == 'pause') {
            $my_ad = array();
            $my_ad['ID'] = $aid;
            $my_ad['post_status'] = 'draft';
            wp_update_post($my_ad);

        } elseif ($d == 'restart') {
            $my_ad = array();
            $my_ad['ID'] = $aid;
            $my_ad['post_status'] = 'publish';
            wp_update_post($my_ad);
		} elseif ($d == 'freerenew') { colabs_renew_ad_listing($aid);
		} elseif ($d == 'delete') { colabs_delete_ad($aid);
		} elseif ($d == 'setSold') { update_post_meta($aid, 'colabs_ad_sold', 'true'); 
		} elseif ($d == 'unsetSold') { update_post_meta($aid, 'colabs_ad_sold', 'false'); 
        } else { //echo "nothing here";
        }

    }

endif;
?>

<?php get_header(); ?>

<!-- #Main Starts -->
<?php colabs_main_before(); ?>
    <div class="row main-container">

        <div class="main-content col9">

            
            <header class="entry-header">
			  <h2><?php printf(__("%s's Dashboard", 'colabsthemes'), $display_user_name); ?></h2>
			</header>
			
			<div <?php post_class(); ?>>
				<p><?php _e('Below you will find a listing of all your classified ads. Click on one of the options to perform a specific task. If you have any questions, please contact the site administrator.','colabsthemes');?></p>
				<table border="0" cellpadding="4" cellspacing="1" class="table-my-ads">
                    <thead>
                        <tr>
                           
                            <th class="text-center" colspan="2"><?php _e('Title','colabsthemes');?></th>							
							<th width="40px"><?php _e('Views','colabsthemes');?></th>
                            <th width="120px"><?php _e('Status','colabsthemes');?></th>
                            <th width="90px"><div style="text-align: center;"><?php _e('Options','colabsthemes');?></div></th>
                        </tr>
                    </thead>
                    <tbody>

					<?php 
						// setup the pagination and query
						$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
						query_posts(array('posts_per_page' => 10, 'post_type' => COLABS_POST_TYPE, 'post_status' => 'publish, pending, draft', 'author' => $current_user->ID, 'paged' => $paged));

						// build the row counter depending on what page we're on
						if($paged == 1) $i = 0; else $i = $paged * 10 - 10;
					?>

					<?php if(have_posts()) : ?>

						<?php while(have_posts()) : the_post(); $i++; ?>

                        <?php 
                            // check to see if ad is legacy or not and then format date based on WP options
                            if(get_post_meta($post->ID, 'expires', true))
                                $expire_date = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime(get_post_meta($post->ID, 'expires', true)));
                            else
                                $expire_date = date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime(get_post_meta($post->ID, 'colabs_sys_expire_date', true)));

                            
                            // get the ad total cost and legacy check
                            if (get_post_meta($post->ID, 'colabs_totalcost', true))
                                $total_cost = get_post_meta($post->ID, 'colabs_totalcost', true);
                            else
                                $total_cost = get_post_meta($post->ID, 'colabs_sys_total_ad_cost', true);

                            // get the prune period and legacy check
                            //  if (get_post_meta($post->ID, 'colabs_sys_ad_duration', true))
                            //      $prun_period = get_post_meta($post->ID, 'colabs_sys_ad_duration', true);
                            //  else
                            //      $prun_period = get_option('colabs_prun_period');

							if (get_post_meta($post->ID, 'colabs_total_count', true))
								$ad_views = number_format(get_post_meta($post->ID, 'colabs_total_count', true));
							else
								$ad_views = '-';


                            // now let's figure out what the ad status and options should be
                            // it's a live and published ad
                            if ($post->post_status == 'publish') {
								
								$poststatus = '<h4 class="ad-status">';
                                $poststatus .= __('Online','colabsthemes');
								$poststatus .= ' ' . __('Until','colabsthemes') . '<br/><p class="small">(' . $expire_date . ')</p>';
								$poststatus .= '</h4>';
								
                                $postimage = 'pause.png';
                                $postalt =  __('pause ad','colabsthemes');
                                $postaction = 'pause';

                            // it's a pending ad which gives us several possibilities
                            } elseif ($post->post_status == 'pending') {


                                // ad is free and waiting to be approved
                                if ($total_cost == 0) {
									$poststatus = '<h4 class="ad-status">';
                                    $poststatus .= __('Awaiting approval','colabsthemes');
									$poststatus .= '</h4>';
                                    $postimage = '';
                                    $postalt = '';
                                    $postaction = 'pending';

                                // ad hasn't been paid for yet
                                } else {
									$poststatus = '<h4 class="ad-status">';
                                    $poststatus .= __('Awaiting payment','colabsthemes');
									$poststatus .= '</h4>';
                                    $postimage = '';
                                    $postalt = '';
                                    $postaction = 'pending';
                                }

                                

                            } elseif ($post->post_status == 'draft') {
							
							//handling issue where date format needs to be unified
                            if(get_post_meta($post->ID, 'expires', true))
                                $expire_date = get_post_meta($post->ID, 'expires', true);
                            else
                                $expire_date = get_post_meta($post->ID, 'colabs_sys_expire_date', true);

                                // current date is past the expires date so mark ad ended
                                if (strtotime(date('Y-m-d H:i:s')) > (strtotime($expire_date))) {
									$poststatus = '<h4 class="ad-status">';
                                    $poststatus .= __('Ended','colabsthemes') . '<br/><p class="small">(' . $expire_date . ')</p>';
									$poststatus .= '</h4>';
                                    $postimage = '';
                                    $postalt = '';
                                    $postaction = 'ended';

                                // ad has been paused by ad owner
                                } else {
									$poststatus = '<h4 class="ad-status">';
                                    $poststatus .= __('Offline','colabsthemes');
									$poststatus .= '</h4>';
                                    $postimage = 'start-blue.png';
                                    $postalt = __('restart ad','colabsthemes');
                                    $postaction = 'restart';
                                }

                            } else {
                                    $poststatus = '&mdash;';
                            }
                        ?>


                        <tr class="even">
                            
							<td class="text-center"><?php colabs_image('width=50&height=50'); ?></td>
							
                            <td><h3>
                                <?php if ($post->post_status == 'pending' || $post->post_status == 'draft' || $poststatus == 'ended' || $poststatus == 'offline') { ?>

                                    <?php the_title(); ?>

                                <?php } else { ?>

                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

                                <?php } ?>    
                                </h3>

                                <div class="meta"><span class="folder"><?php echo get_the_term_list(get_the_id(), COLABS_TAX_CAT, '', ', ', ''); ?></span> | <span class="clock"><span><?php the_time(get_option('date_format'))?></span></span></div>

                            </td>
							
							
							
							<td class="text-center"><?php echo $ad_views; ?></td>

                            <td class="text-center"><?php echo ucfirst($poststatus) ?></td>

                            <td class="text-center">
                                <?php 

                                if ( $post->post_status == 'pending' && $postaction != 'ended' ) {

                                    // show the paypal button if the ad has not been paid for yet
                                    if ( ($total_cost != 0) && (get_option('colabs_enable_paypal') != 'false') ) {
                                        echo colabs_dashboard_paypal_button( $post->ID );
                                    } else {
                                        echo '&mdash;';
                                    }
									echo '<a onclick="return confirm_before_delete();" href="' . CL_DASHBOARD_URL . '?aid=' . $post->ID . '&amp;action=delete" style="display: block;">' . __('Delete Ad', 'colabsthemes') . '</a>';

                                } elseif ( $post->post_status == 'draft' && $postaction == 'ended' ) {

                                    if ( get_option('colabs_allow_relist') == 'true' ) {
                                    
                                        // show the paypal button so they can relist their ad only
                                        // if it's not a legacy ad and they originally paid to list
                                        if ( ($total_cost != 0) && get_post_meta($post->ID, 'colabs_totalcost', true) == '' ) {
                                            if ( get_option('colabs_enable_paypal') != 'false' ) 
                                                echo colabs_dashboard_paypal_button( $post->ID );
											else 
											    _e('Contact us to relist ad', 'colabsthemes');
                                        } else {
                                            echo '<a href="' . CL_DASHBOARD_URL . '?aid=' . $post->ID . '&amp;action=freerenew">' . __('Relist Ad', 'colabsthemes') . '</a>';
                                        }
                                        
                                    } else {
                                        echo '&mdash;';
                                    }


                                } else { ?>

                              <?php if ( get_option('colabs_ad_edit') == true ) : ?><a href="<?php echo CL_EDIT_URL; ?>?aid=<?php the_id(); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/pencil.png" title="" alt="" border="0" /></a>&nbsp;&nbsp;<?php endif; ?>
                              <a onclick="return confirm_before_delete();" href="<?php echo CL_DASHBOARD_URL; ?>?aid=<?php the_id(); ?>&amp;action=delete" title="<?php _e('Delete Ad', 'colabsthemes'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/close.png" title="<?php _e('Delete Ad', 'colabsthemes'); ?>" alt="<?php _e('Delete Ad', 'colabsthemes'); ?>" border="0" /></a>&nbsp;&nbsp;
							  <a href="<?php echo CL_DASHBOARD_URL; ?>?aid=<?php the_id(); ?>&amp;action=<?php echo $postaction; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/<?php echo $postimage; ?>" title="" alt="" border="0" /></a><br />
                              <?php if ( get_post_meta(get_the_id(), 'colabs_ad_sold', true) != 'true' ) : ?>
							 <a href="<?php echo CL_DASHBOARD_URL; ?>?aid=<?php the_id(); ?>&amp;action=setSold"><?php _e('Mark Sold', 'colabsthemes'); ?></a>
                             <?php else : ?>
							 <a href="<?php echo CL_DASHBOARD_URL; ?>?aid=<?php the_id(); ?>&amp;action=unsetSold"><?php _e('Unmark Sold', 'colabsthemes'); ?></a>
							 <?php endif; ?>
                          <?php } ?>


                            </td>
                        </tr>

                        <?php endwhile; ?>
						
							
							
						<script type="text/javascript">
							/* <![CDATA[ */
							  function confirm_before_delete() { return confirm("<?php _e('Are you sure you want to delete this ad?', 'colabsthemes'); ?>"); }
							/* ]]> */
						  </script>	

                    <?php else : ?>

                        <tr class="even">
                            <td colspan="5">

                                <div class="pad10"></div>

								<p class="text-center"><?php _e('You currently have no classified ads.','colabsthemes');?></p>

								<div class="pad25"></div>

							</td>
                        </tr>

                    <?php endif; ?>

					<?php //wp_reset_query(); ?>

                    </tbody>
                </table>
				<?php if(function_exists('colabs_pagination')) colabs_pagination(); ?>
			
			</div>
		</div><!-- /.main-content -->  
		
		<?php get_sidebar('user'); ?>
		
    </div><!-- /.main-container -->
	
<?php colabs_main_after(); ?>
  
<?php get_footer(); ?>

