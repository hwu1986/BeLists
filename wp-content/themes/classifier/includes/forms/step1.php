<?php
/**
 * This is step 1 of 3 for the ad submission form
 *
 * @package ClassiPress
 * @subpackage New Ad
 * @author AppThemes
 *
 *
 */


global $current_user, $wpdb;

?>
<style type="text/css">
.form_step #getform, .form_step #step1, .form_step #chosenCategory {
    display: none;
}
</style>

  <div class="row main-container">

    <?php colabsthemes_before_submit(); ?>
    
    <!--div id="step1"></div><!--#step1-->
    
    <div class="entry-content">
      <h4><?php _e('Fill The Form','colabsthemes');?></h4>
      <?php echo get_option('colabs_ads_form_msg'); ?>
    </div>

    <div class="row form-submit-listing">
    
      <form name="mainform" id="mainform" class="form_step" action="" method="post" enctype="multipart/form-data">

        <div class="col6">
        
          <div class="input-text">
            <label><?php _e('Listing Title','colabsthemes');?> <span class="colour">*</span></label>
            <input name="post_title" id="post_title" type="text" minlength="2" value="" class="text required" />   
          </div>

          <div class="input-textarea">
            <label><?php _e('Listing Description','colabsthemes');?> <span class="colour">*</span></label>
            <textarea rows="8" cols="40" name="post_content" id="post_content" class="required" minlength="2"></textarea>
          </div>
          
          <div class="input-text">
            <label><?php _e('Price','colabsthemes');?>: <span class="colour">*</span></label>
            <input name="colabs_price" id="colabs_price" type="text" minlength="2" value="" class="text required" />
          </div>

          <div class="input-text">
            <label><?php _e('Address','colabsthemes');?>: <span class="colour">*</span></label>
            <input name="colabs_location" id="colabs_location" type="text" minlength="2" value="" class="text required" />
          </div>
<!--
          <div class="input-text">
            <label><?php _e('Zip/Postal Code','colabsthemes');?>: <span class="colour">*</span></label>
            <input name="colabs_zipcode" id="colabs_zipcode" type="text" minlength="2" value="" class="text required" />
          </div>
-->
          <div class="input-text">
            <label><?php _e('Email','colabsthemes');?>: </label>
            <input name="colabs_email" id="colabs_email" type="text" minlength="2" value="" class="text" />
          </div>

          <div class="input-text">
            <label><?php _e('Website','colabsthemes');?>: </label>
            <input name="colabs_site" id="colabs_site" type="text" minlength="2" value="" class="text" />
          </div>

          <div class="input-text">
            <label><?php _e('Phone','colabsthemes');?>: </label>
            <input name="colabs_phone" id="colabs_phone" type="text" minlength="2" value="" class="text" />
          </div>
                    
          <div class="input-text">
            <label><?php _e('Tags','colabsthemes');?>: <span class="colour">*</span><span class="label-helper">(<?php _e('separate tags with coma','colabsthemes');?>)</span></label>
            <input name="tags_input" id="tags_input" type="text" minlength="2" value="" class="text required"/>
          </div>
          
        </div>
        <!-- /.col6 -->

        <div class="col6">
          <div class="input-text">
            <label><?php _e('Cost Per Listing','colabsthemes');?>:</label>
            <?php colabs_cost_per_listing(); ?> <?php // printf(__('for %s days', 'colabsthemes'), get_option('colabs_prun_period')); ?>
          </div>
          <div class="input-select">
            <label><?php _e('Select Categories','colabsthemes'); ?>: <span class="colour">*</span></label>
            <div class="ad-categories">
                <div id="catlvl0">
                <?php
                
                if ( get_option('colabs_price_scheme') == 'category' && get_option('colabs_enable_paypal') == 'true' && get_option('colabs_ad_parent_posting') != 'false' ) {
                
                    colabs_dropdown_categories_prices('show_option_none='.__('Select one','colabsthemes').'&class=dropdownlist required&orderby=name&order=ASC&hide_empty=0&hierarchical=1&taxonomy='.COLABS_TAX_CAT.'&depth=1');
                
                } else {

                   wp_dropdown_categories('show_option_none='.__('Select one','colabsthemes').'&class=dropdownlist required&orderby=name&order=ASC&hide_empty=0&hierarchical=1&taxonomy='.COLABS_TAX_CAT.'&depth=1');
                
                }
                
                ?>
                </div><!-- /#catlvl0 -->
            </div><!-- /.ad-categories -->
            
          </div><!-- /.input-select -->
          <div class="input-select">
            <label><?php _e('Select Location','colabsthemes'); ?>: </label>
            <div class="ad-location">
                <div id="loclvl0">
                <?php
                   wp_dropdown_categories('show_option_none='.__('Select one','colabsthemes').'&class=dropdownlist&name=loc&orderby=name&order=ASC&hide_empty=0&hierarchical=1&taxonomy='.COLABS_TAX_LOC.'&depth=0');
                ?>
                </div><!-- /#loclvl0 -->
            </div><!-- /.ad-location -->
          </div><!-- /.input-select -->
          <?php
          // show the image, featured ad, payment type and other options
          echo colabs_other_fields();
          ?>
        
          <div id="ad-form-input" class="input-submit">
            <input type="submit" name="step1" id="step1" class="btn btn-primary" value="<?php _e('Continue &rsaquo;&rsaquo;','colabsthemes'); ?>" />
            <div id="chosenCategory"><input id="cat" name="cat" type="input" value="-1" /><input id="loc" name="loc" type="input" value="-1" /></div>
          </div>          

        </div>
        <!-- /.col6 -->

        <input type="hidden" id="catname" name="catname" value="<?php echo $_POST['catname']; ?>" />
        <input type="hidden" id="fid" name="fid" value="<?php if(isset($_POST['fid'])) echo $_POST['fid']; ?>" />
        <input type="hidden" id="oid" name="oid" value="<?php echo $order_id; ?>" />

      </form>
        
    </div>
    <!-- /.form-submit-listing -->

    <?php colabsthemes_after_submit(); ?>
  
  </div>
  <!-- /.main-container -->
