<?php
/**
 * WordPress Login Form
 * Function outputs the login form
 *
 *
 * @author ColorLabs
 * @package Classifier
 *
 */

function colabs_login_form( $action = '', $redirect = '' ) {

	global $posted;
	
	if (!$action) 
	    $action = site_url('wp-login.php');
    
    if ( !empty( $_REQUEST['redirect_to'] ) )
		$redirect = $_REQUEST['redirect_to'];
    elseif (!$redirect)
	    $redirect = CL_DASHBOARD_URL;

	?>

	<form action="<?php echo $action; ?>" method="post" class="loginform">
		
		<p>
			<label for="login_username"><?php _e('Username', 'colabsthemes'); ?>&nbsp;:</label>
			<input type="text" class="text" name="log" id="login_username" value="<?php if (isset($posted['login_username'])) esc_attr_e($posted['login_username']); ?>" />
		</p>

		<p>
			<label for="login_password"><?php _e('Password', 'colabsthemes'); ?>&nbsp;:</label>
			<input type="password" class="text" name="pwd" id="login_password" value="" />
		</p>
		
		<div class="clr"></div>

		<div id="checksave">
		
			<p class="rememberme">
				<input name="rememberme" class="checkbox" id="rememberme" value="forever" type="checkbox" checked="checked"/>
				<label for="rememberme"><?php _e('Remember me','colabsthemes'); ?></label>
			</p>	

			<p class="submit">
				<input type="submit" class="btn btn-primary" name="login" id="login" value="<?php _e('Login &raquo;','colabsthemes'); ?>" />					
				<input type="hidden" name="redirect_to" value="<?php echo $redirect; ?>" />
				<input type="hidden" name="testcookie" value="1" />						
			</p>
			
			<p class="lostpass">
				<a class="lostpass" href="<?php echo site_url('wp-login.php?action=lostpassword', 'login') ?>" title="<?php _e('Password Lost and Found', 'colabsthemes'); ?>"><?php _e('Lost your password?', 'colabsthemes'); ?></a>
			</p>
			
			<?php wp_register('<p class="register">','</p>'); ?>					
			
			<?php do_action('login_form'); ?>
			
		</div>

	</form>
	
	<script type="text/javascript">document.getElementById('login_username').focus();</script> 

<?php
}
?>