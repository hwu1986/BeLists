<?php
/**
 * This is step 1 of 2 for the membership buy form
 */

global $current_user, $wpdb, $colabs_abbr;
?>

  <?php colabsthemes_before_submit_membership(); ?>

  <div id="step1"></div>

      <h3><?php _e('Purchase a Membership Pack','colabsthemes');?></h3>

            <?php 
                // display the custom message
                echo get_option('colabs_membership_form_msg');
				//use to debug step 1 post vars
				
				if(isset($_GET['membership']) && $_GET['membership'] == 'required'):
            ?>
            
					<p class="info"><?php _e('Membership is currently required','colabsthemes'); ?><?php if(!empty($_GET['cat']) && $_GET['cat'] != 'all') { _e(' in order to post to category ', 'colabsthemes');
					$theTerm = get_term_by('term_id ', $_GET['cat'], COLABS_TAX_CAT); 
					echo ' <a href="/'.get_option($colabs_abbr.'_ad_cat_tax_permalink').'/'.$theTerm->slug.'/">'.$theTerm->name.'</a>';} ?>.</p>
 			
            <?php endif; ?>
 
            <p class="dotted">&nbsp;</p>

                <form name="mainform" id="mainform" class="form_membership_step" action="" method="post" enctype="multipart/form-data">




		<?php 
            $sql = "SELECT * "
                 . "FROM " . $wpdb->prefix . "colabs_ad_packs "
                 . "ORDER BY pack_id desc";
            
            $results = $wpdb->get_results($sql);
        ?>

        <div id="membership-packs" class="wrap">

        <table id="memberships" class="widefat fixed">

            <thead style="text-align:left;">
                <tr>
                    <th scope="col"><?php _e('Name','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Membership Benefit','colabsthemes') ?></th>
                    <th scope="col"><?php _e('Subscription','colabsthemes') ?></th>
                    <th scope="col" style="width:75px;"></th>
                </tr>
            </thead>

            <?php
            if ($results) {
                $rowclass = '';
                $i=1;
            ?>

              <tbody id="list">

            <?php
                foreach( $results as $result ) {
					unset($rowclass, $requiredClass);
	                if($result->pack_status == 'active_membership') :
	                //$rowclass = 'even' == $rowclass ? 'alt' : 'even';
					$rowclass = 'even';
					$benefit = get_pack_benefit($result);
					if(stristr($result->pack_type, 'required')) {
						$requiredClass = 'required';
					}
              ?>

                <tr class="<?php echo $rowclass.' '.$requiredClass; ?>">
                    <?php $i++; ?>
                    <td><strong><?php echo stripslashes($result->pack_name); ?></strong><a class="tip" tip="<?php echo $result->pack_desc; ?>" tabindex="99"><div class="helpico"></div></a></td>
                    <td><?php echo $benefit; ?></td>
                    <td><?php echo colabs_pos_price($result->pack_membership_price).' / '.$result->pack_duration.' '.__('days','colabsthemes'); ?></td>
                    <td><input type="submit" name="step1" id="step1" class="btn_orange" onclick="document.getElementById('pack').value=<?php echo $result->pack_id; ?>;" value="<?php _e('Buy Now &rsaquo;&rsaquo;','colabsthemes'); ?>" style="margin-left: 5px; margin-bottom: 5px;" /></td>
                </tr>

              <?php
				endif; //end if('active_membership' || 'inactive_membership')
				
                } // end for each
				unset($i);
              ?>

              </tbody>

            <?php

            } else {

            ?>

                <tr>
                    <td colspan="7"><?php _e('No membership packs found.','colabsthemes') ?></td>
                </tr>

            <?php
            } // end $results
            ?>

            </table>


        </div><!-- end wrap for membership packs-->

                        <input type="hidden" id="oid" name="oid" value="<?php echo $order_id; ?>" />
                        <input type="hidden" id="pack" name="pack" value="<?php if(isset($_POST['pack'])) echo $_POST['pack']; ?>" />

                </form>

    <?php colabsthemes_after_submit_membership(); ?>