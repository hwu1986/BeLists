<?php
/*
Template Name: Contact Form
*/
?>
<?php 
 $title_before = '<h1 class="entry-title">';
 $title_after = '</h1>';
 $title_before = $title_before . '<a href="' . get_permalink( get_the_ID() ) . '" rel="bookmark" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">';
 $title_after = '</a>' . $title_after;
//If the form is submitted
if(isset($_POST['submitted'])) {

	//Check to see if the honeypot captcha field was filled in
	if(trim($_POST['checking']) !== '') {
		$captchaError = true;
	} else {
	
		//Check to make sure that the name field is not empty
		if(trim($_POST['contactName']) === '') {
			$nameError =  __('You forgot to enter your name.', 'colabsthemes'); 
			$hasError = true;
		} else {
			$name = trim($_POST['contactName']);
		}
		
		//Check to make sure sure that a valid email address is submitted
		if(trim($_POST['email']) === '')  {
			$emailError = __('You forgot to enter your email address.', 'colabsthemes');
			$hasError = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
			$emailError = __('You entered an invalid email address.', 'colabsthemes');
			$hasError = true;
		} else {
			$email = trim($_POST['email']);
		}
			
		//Check to make sure comments were entered	
		if(trim($_POST['comments']) === '') {
			$commentError = __('You forgot to enter your comments.', 'colabsthemes');
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$comments = stripslashes(trim($_POST['comments']));
			} else {
				$comments = trim($_POST['comments']);
			}
		}
			
		//If there is no error, send the email
		if(!isset($hasError)) {
			
			$emailTo = get_option('colabs_contactform_email'); 
			$subject = __('Contact Form Submission from ', 'colabsthemes').$name;
			$sendCopy = trim($_POST['sendCopy']);
			$body = __("Name: $name \n\nEmail: $email \n\nComments: $comments", 'colabsthemes');
			$headers = __('From: ', 'colabsthemes') .' <'.$email.'>' . "\r\n" . __('Reply-To: ','colabsthemes') . $email;

			//Modified 2010-04-29 (fox)
			wp_mail($emailTo, $subject, $body, $headers);

			if($sendCopy == true) {
				$subject = __('You emailed ', 'colabsthemes').get_bloginfo('title');
				$headers = __('From: ','colabsthemes') . '<'.$emailTo.'>';
				wp_mail($email, $subject, $body, $headers);
			}

			$emailSent = true;

		}
	}
} ?>
<?php
get_header();
?>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
jQuery(document).ready(function() {
	jQuery('form#frmcontact').submit(function() {
		jQuery('form#frmcontact .error').remove();
		var hasError = false;
		jQuery('.requiredField').each(function() {
			if(jQuery.trim(jQuery(this).val()) == '') {
                var labelText = jQuery(this).prev('label').attr('id');
				jQuery(this).parent().append('<span class="error"><?php _e('You forgot to enter your', 'colabsthemes'); ?> '+labelText+'.</span>');
				jQuery(this).addClass('inputError');
				hasError = true;
			} else if(jQuery(this).hasClass('email')) {
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
				if(!emailReg.test(jQuery.trim(jQuery(this).val()))) {
					var labelText = jQuery(this).prev('label').text();
					jQuery(this).parent().append('<br/><label></label><span class="error"><?php _e('You entered an invalid', 'colabsthemes'); ?> '+labelText+'.</span>');
					jQuery(this).addClass('inputError');
					hasError = true;
				}
			}
		});
		if(!hasError) {
			var formInput = jQuery(this).serialize();
			jQuery.post(jQuery(this).attr('action'),formInput, function(data){
				jQuery('form#frmcontact').slideUp("fast", function() {				   
					jQuery(this).before('<p class="tick"><?php _e('<strong>Thanks!</strong> Your email was successfully sent.', 'colabsthemes'); ?></p>');
				});
			});
		}
		
		return false;
		
	});
});
//-->!]]>
</script>
<style>
span.error{
    display: block;
    color: #C00 !important;
    margin-left: 5px 0 0 25%;
}
</style>
<?php get_header(); ?>

<?php if ( function_exists('colabs_cl_breadcrumb') ) colabs_cl_breadcrumb(); ?>

<!-- #Main Starts -->
<?php colabs_main_before(); ?>
    <div class="row main-container">

        <div class="main-content col9">

            
            <header class="entry-header">
			  <h2><?php the_title(); ?></h2>
			</header>
			
			<?php if(isset($emailSent) && $emailSent == true) { ?>
                <div class="notification-success"><?php _e('Your email was successfully sent.', 'colabsthemes'); ?>&nbsp;<?php _e('We will reply your message immediately.', 'colabsthemes'); ?></div>
			<?php  } ?>
			
			<div <?php post_class(); ?>>
				
				<?php 
				if (have_posts()) { $count = 0;
					while (have_posts()) { the_post(); $count++;?>
						<div class="entry-content"><?php the_content(''); ?></div>
					<?php }
				}?>	
				
				<?php if(isset($hasError) || isset($captchaError) ) { ?>
					<div class="errordiv"><?php _e('There was an error submitting the form.', 'colabsthemes'); ?></div>
				<?php } ?>
				
				<?php if ( get_option('colabs_contactform_email') == '' ) { ?>
					<div class="errordiv"><?php _e('E-mail has not been setup properly. Please add your contact e-mail.', 'colabsthemes'); ?></div>
				<?php } ?>
				
				<form action="<?php the_permalink(); ?>" id="frmcontact" method="post" class="form-section">

							<div class="input-text"><label for="txtname" id="<?php _e('Name', 'colabsthemes'); ?>"><?php _e('Name', 'colabsthemes'); ?>&nbsp;<span>(<?php _e('Required','colabsthemes'); ?>)</span></label>
								<input type="text" name="contactName" id="txtname" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="txt requiredField textboxcontact" />
								<?php if($nameError != '') { ?>
									<span class="error"><?php echo $nameError;?></span> 
								<?php } ?>
							<br />
							</div>
							
							<div class="input-text"><label for="txtemail" id="<?php _e('Email', 'colabsthemes'); ?>"><?php _e('Email', 'colabsthemes'); ?>&nbsp;<span>(<?php _e('Required','colabsthemes'); ?>)</span></label>
								<input type="text" name="email" id="txtemail" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="txt requiredField email textboxcontact" />
								<?php if($emailError != '') { ?>
									<span class="error"><?php echo $emailError;?></span>
								<?php } ?>
							<br /></div>
							
							
							<div class="contact-message">
								<label for="txtmessage" id="<?php _e('Message', 'colabsthemes'); ?>"></label>
								<textarea  name="comments" id="txtmessage" rows="10" cols="30" class="requiredField textareacontact"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
								<?php if($commentError != '') { ?>
									<span class="error"><?php echo $commentError;?></span> 
								<?php } ?>
							<br /></div>
							
							<div><input type="checkbox" name="sendCopy" id="sendCopy" value="true"<?php if(isset($_POST['sendCopy']) && $_POST['sendCopy'] == true) echo ' checked="checked"'; ?> /><label for="sendCopy" class="sendCopy"><?php _e('Send a copy of this email to yourself', 'colabsthemes'); ?></label><br /></div>
							
							<div>
								<label for="checking" class="screenReader"><?php _e('If you want to submit this form, do not enter anything in this field', 'colabsthemes') ?></label>
								<input type="text" name="checking" id="checking" class="screenReader" value="<?php if(isset($_POST['checking']))  echo $_POST['checking'];?>" />
							</div>

							<input type="hidden" name="submitted" id="submitted" value="true" />
							<input class="submitcontact submit btn btn-primary" type="submit" value="<?php _e('Submit', 'colabsthemes'); ?>" />
								
						</form>
						<!-- End Form -->
            </div>
       
        </div><!-- /.main-content -->  
		
		<?php get_sidebar(); ?>
		
    </div><!-- /.main-container -->
	
<?php colabs_main_after(); ?>
  
<?php get_footer(); ?>
