<?php

/**
 * This is the sidebar contact form used on the single ad page
 *
 */

$msg = '';

// if contact form has been submitted, send the email
if (isset($_POST['submit']) && $_POST['send_email'] == 'true') {

    // get the submitted math answer
    $rand_post_total = (int)$_POST['rand_total'];

    // compare the submitted answer to the real answer
    $rand_total = (int)$_POST['rand_num'] + (int)$_POST['rand_num2'];

    // if it's a match then send the email
    if ($rand_total == $rand_post_total) {
        colabs_contact_ad_owner_email($post->ID);
        $msg = '<p class="green center"><strong>' . __('Your message has been sent!', 'colabsthemes') . '</strong></p>';
    } else {
        $msg = '<p class="red center"><strong>' . __('ERROR: Incorrect captcha answer', 'colabsthemes') . '</strong></p>';
    }

}

?>

   <form name="mainform" id="mainform" class="form_contact inquiry-form" action="#priceblock2" method="post" enctype="multipart/form-data">

       <?php echo $msg; ?>

        <p class="input-block">
            <label><?php _e('Full Name:', 'colabsthemes') ?> <span class="required"><?php _e('Required', 'colabsthemes') ?></span> </label>
            <input name="from_name" id="from_name" type="text" minlength="2" value="<?php if(isset($_POST['from_name'])) echo esc_attr( stripslashes($_POST['from_name']) ); ?>" class="text required" />
        </p>
        
        <p class="input-block">
            <label><?php _e('Email:', 'colabsthemes') ?> <span class="required"><?php _e('Required', 'colabsthemes') ?></span> </label>
            <input name="from_email" id="from_email" type="text" minlength="5" value="<?php if(isset($_POST['from_email'])) echo esc_attr( stripslashes($_POST['from_email']) ); ?>" class="text required email" />
        </p>

        <p class="input-block">
            <label><?php _e('Subject:', 'colabsthemes') ?> <span class="required"><?php _e('Required', 'colabsthemes') ?></span> </label>
            <input name="subject" id="subject" type="text" minlength="2" value="<?php _e('Re:', 'colabsthemes') ?> <?php the_title();?>" class="text required" />
        </p>

        <p class="input-block">
            <label><?php _e('Message:', 'colabsthemes') ?> <span class="required"><?php _e('Required', 'colabsthemes') ?></span> </label>
            <textarea name="message" id="message" rows="3" cols="" class="text required"><?php if(isset($_POST['message'])) echo esc_attr( stripslashes($_POST['message']) ); ?></textarea>
        </p>
        
        <p>
            <?php
            // create a random set of numbers for spam prevention
            $randomNum = '';
            $randomNum2 = '';
            $randomNumTotal = '';

            $rand_num = rand(0,9);
            $rand_num2 = rand(0,9);
            $randomNumTotal = $randomNum + $randomNum2;
            ?>
            <label><?php _e('Sum of', 'colabsthemes') ?> <?php echo $rand_num; ?> + <?php echo $rand_num2; ?> =</label>
            <input name="rand_total" id="rand_total" type="text" minlength="1" value="" class="text required number" />
            <div class="clr"></div>
        </p>

        <p>
            <input name="submit" type="submit" id="submit_inquiry" class="btn btn-primary" value="<?php _e('Send Inquiry','colabsthemes'); ?>" />
        </p>

        <input type="hidden" name="rand_num" value="<?php echo $rand_num; ?>" />
        <input type="hidden" name="rand_num2" value="<?php echo $rand_num2; ?>" />
        <input type="hidden" name="send_email" value="true" />

   </form>
