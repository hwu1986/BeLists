<?php

/**-----------------------------------------------------------------------------------
 * ColorLabs Framework Updater
 *
 * @package CoLabsFramework
 * @since 1.0
 
 
TABLE OF CONTENTS

- Framework Updater
	- CoLabsFramework Update Page
 	- CoLabsFramework Update Head
 	- CoLabsFramework Version Getter

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* CoLabsFramework Update Page */
/*-----------------------------------------------------------------------------------*/

function colabsthemes_framework_update_page(){
?>
<div class="wrap colabs_notice">
	<h2></h2>

<div id="colabs_options" class="wrap<?php if (get_bloginfo('text_direction') == 'rtl') { echo ' rtl'; } ?>">

	<div class="one_col wrap colabs_container">
    
            <div class="clear"></div>
            <div id="colabs-popup-save" class="colabs-save-popup"><div class="colabs-save-save"><?php _e("Framework Updated","colabsthemes"); ?></div></div>
            <!--div id="colabs-popup-reset" class="colabs-save-popup"><div class="colabs-save-reset">Options Reset</div></div-->
            <div style="width:100%;padding-top:15px;"></div>
            <div class="clear"></div>
        
	<div id="main">
        
	<div id="panel-header">
        <?php colabsthemes_options_page_header('save_button=false'); ?>
	</div><!-- #panel-header -->

    <div id="panel-content">

    <div class="section">
			<h3 class="heading"><?php _e("Framework Update","colabsthemes"); ?></h3>
			<div class="option">

        <?php colabsthemes_framework_update_page_set(); ?>
		
			</div><!-- .option -->
    </div><!-- .section -->
		<div class="section">
			<h3 class="heading"><?php _e("Themes Update","colabsthemes"); ?></h3>
			<div class="option">

        <?php colabsthemes_themes_update_page_set(); ?>
		
			</div><!-- .option -->
    </div><!-- .section -->
		
    </div><!-- #panel-content -->

    <div id="panel-footer">
      <ul>
          <li class="docs"><a title="Theme Documentation" href="<?php echo $manualurl; ?>/documentation/<?php echo strtolower( str_replace( " ","",$themename ) ); ?>" target="_blank" ><?php _e("View Documentation","colabsthemes"); ?></a></li>
          <li class="forum"><a href="http://colorlabsproject.com/resolve/" target="_blank"><?php _e("Submit a Support Ticket","colabsthemes"); ?></a></li>
          <li class="idea"><a href="http://ideas.colorlabsproject.com/" target="_blank"><?php _e("Suggest a Feature","colabsthemes"); ?></a></li>
      </ul>
  	</div><!-- #panel-footer -->
	</div><!-- #main -->

	</div><!-- .colabs_container -->
    
</div><!-- #colabs_options -->

</div><!-- .wrap -->
<?php
} //end of colabsthemes_framework_update_page

function colabsthemes_framework_update_page_set(){
        $method = get_filesystem_method();
        $to = ABSPATH . 'wp-content/themes/' . get_option( 'template') . "/functions/";
        if(isset($_POST['password'])){

            $cred = $_POST;
            $filesystem = WP_Filesystem($cred);

        }
        elseif(isset($_POST['colabs_ftp_cred'])){

             $cred = unserialize(base64_decode($_POST['colabs_ftp_cred']));
             $filesystem = WP_Filesystem($cred);

        } else {

           $filesystem = WP_Filesystem();

        };
        $url = admin_url( 'admin.php?page=colabsthemes_framework_update' );
        ?>

            <?php
            if($filesystem == false){

            request_filesystem_credentials ( $url );

            }  else {
            ?>

            <?php 
            $localversion = get_option( 'colabs_framework_version' );
            $remoteversion = colabs_get_fw_version();
            $upd = colabsthemes_framework_update_check();
            ?>

            <span style="display:none"><?php echo $method; ?></span>
            <form method="post"  enctype="multipart/form-data" id="colabsform" action="<?php /* echo $url; */ ?>">

                <?php if( $upd['update'] ) { ?>
                <?php wp_nonce_field( 'update-options' ); ?>
                <h3><?php _e("A new version of ColorLabs Framework is available.","colabsthemes"); ?></h3>
                <p><?php _e("This updater will download and extract the latest ColorLabs Framework files to your current theme's functions folder. ","colabsthemes"); ?></p>
                <p><?php _e("We recommend backing up your theme files before updating.","colabsthemes"); ?></p>
                <p>&rarr; <strong><?php _e("Your version:","colabsthemes"); ?></strong> <?php echo $upd['localversion']; ?></p>

                <p>&rarr; <strong><?php _e("Current Version:","colabsthemes"); ?></strong> <?php echo $upd['remoteversion']; ?></p>

                <input type="submit" class="button" value="Update Framework" />
                <?php } else { ?>
                <h3><?php _e("You have the latest version of ColorLabs Framework","colabsthemes"); ?></h3>
                <p>&rarr; <strong><?php _e("Your version:","colabsthemes"); ?></strong> <?php echo $upd['localversion']; ?></p>
                <?php } ?>
                <input type="hidden" name="colabs_update_save" value="save" />
                <input type="hidden" name="colabs_ftp_cred" value="<?php echo esc_attr( base64_encode(serialize($_POST))); ?>" />

            </form>
            <?php } 
};

function colabsthemes_themes_update_page_set(){
						
						colabs_theme_update();
						$cookiefile=get_theme_root() . '/cookie.txt';
						$check_cookie=extractCookies(file_get_contents($cookiefile));
						if ($check_cookie!=true){
							if($_POST['login_attempt_id']=='1342424497'){
									_e('<div id="colabs-no-archive-warning" class="updated fade" style="display:block;"><p><strong><i>The user name or password is incorrect</i></strong></p></div>','colabsthemes');
							}
            ?>
							<p><?php _e('Please login with your member account before update your theme','colabsthemes');?></p>
							 <p><?php _e("We recommend backing up your theme files before updating.","colabsthemes"); ?></p>
							<form method="post"  enctype="multipart/form-data" id="colabsform" name="login" class="colabs-login-form">
									<p>
											<label class="element-title" for="login"><?php _e('E-Mail Address or Username:','colabsthemes');?></label> 
											<input id="login" name="amember_login" size="15" value="" type="text">
									</p>
									<p>
											 <label class="element-title" for="pass"><?php _e('Password:','colabsthemes');?></label> 
											 <input id="pass" name="amember_pass" size="15" type="password">
									</p>
									<input type="submit" name="colabs_theme_login" value="Log In" class="button" />
									<input type="hidden" value="1342424497" name="login_attempt_id">
									
							</form>
							<p><?php _e('<a href="http://colorlabsproject.com/member/member/#am-forgot-block" target="_blank">Forgot Password?</a>','colabsthemes');?></p>
            <?php 
						}else{
							$current_theme = wp_get_theme();
							$theme_name = get_option( 'colabs_themename' );
							$storefront_theme = colabs_get_fw_version('http://colorlabsproject.com/updates/'.strtolower($theme_name).'/changelog.txt'); 
							$check_theme_update = version_compare( $storefront_theme, $current_theme->Version, '>' );
							$details_url = add_query_arg(array('TB_iframe' => 'true', 'width' => 1024, 'height' => 800), 'http://colorlabsproject.com/updates/'.strtolower($theme_name).'/changelog.txt');
							$update_url = esc_url( add_query_arg(array( 'page' => 'colabsthemes_framework_update', 'action' => 'colabs-upgrade-theme')) );
							$update_onclick = 'onclick="if ( confirm(\'' . esc_js( __("Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.") ) . '\') ) {return true;}return false;"';			
							$relogin = esc_url( add_query_arg(array( 'page' => 'colabsthemes_framework_update','relogin'=>'true' )) );
							if($check_theme_update==1){
								printf( __('<p>There is a new version of %1$s available. <a href="%2$s" class="thickbox" title="%1$s">View version %3$s details</a> or <a href="%4$s" %5$s >update now</a> or <a href="%6$s" >re-login</a>.</p>'), $theme_name, $details_url, $storefront_theme, $update_url, $update_onclick, $relogin );
							}else{
								printf( __('<p>You have the latest version of %1$s. <a href="%2$s" class="thickbox" title="%1$s">View version %3$s details</a> or <a href="%4$s" >re-login</a>.</p>'), $theme_name, $details_url, $storefront_theme, $relogin );
							}
						}
}

function colabsthemes_framework_update_check(){
    
    $data = array( 'update' => false, 'version' => '1.0.0', 'status' => 'none' );
    
    $data['localversion'] = get_option( 'colabs_framework_version' );
    $data['remoteversion'] = colabs_get_fw_version();
    
	if ( ! $data['localversion'] ) { return $data; }
        
    $check = version_compare( $data['remoteversion'], $data['localversion'] ); // Returns 1 if there is an update available.
    
	if ( $check == 1 ) {
		$data['update'] = true;
        $data['version'] = $data['version'];
	}

	return $data;

}
/*-----------------------------------------------------------------------------------*/
/* CoLabsFramework Update Head */
/*-----------------------------------------------------------------------------------*/

function colabsthemes_framework_update_head(){

  if(isset($_REQUEST['page'])){

	// Sanitize page being requested.
	$_page = strtolower( strip_tags( trim( $_REQUEST['page'] ) ) );

	if( $_page == 'colabsthemes_framework_update'){

		//Setup Filesystem
		$method = get_filesystem_method();

		if(isset($_POST['colabs_ftp_cred'])){

			$cred = unserialize(base64_decode($_POST['colabs_ftp_cred']));
			$filesystem = WP_Filesystem($cred);

		} else {

		   $filesystem = WP_Filesystem();

		};

		if($filesystem == false && $_POST['upgrade'] != 'Proceed'){

			function colabsthemes_framework_update_filesystem_warning() {
					$method = get_filesystem_method();
					echo "<div id='filesystem-warning' class='updated fade'><p>". __("Failed: Filesystem preventing downloads.","colabsthemes")." ( ". $method .")</p></div>";
				}
				add_action( 'admin_notices', 'colabsthemes_framework_update_filesystem_warning' );
				return;
		}
		if(isset($_REQUEST['colabs_update_save'])){

			// Sanitize action being requested.
			$_action = strtolower( trim( strip_tags( $_REQUEST['colabs_update_save'] ) ) );

		if( $_action == 'save' ){

		$temp_file_addr = download_url( 'http://colorlabsproject.com/updates/framework.zip' );

		if ( is_wp_error($temp_file_addr) ) {

			$error = $temp_file_addr->get_error_code();

			if($error == 'http_no_url') {
			//The source file was not found or is invalid
				function colabsthemes_framework_update_missing_source_warning() {
					echo "<div id='source-warning' class='updated fade'><p>". __("Failed: Invalid URL Provided","colabsthemes")."</p></div>";
				}
				add_action( 'admin_notices', 'colabsthemes_framework_update_missing_source_warning' );
			} else {
				function colabsthemes_framework_update_other_upload_warning() {
					echo "<div id='source-warning' class='updated fade'><p>". __("Failed: Upload","colabsthemes")." - $error</p></div>";
				}
				add_action( 'admin_notices', 'colabsthemes_framework_update_other_upload_warning' );

			}

			return;

		  }
		//Unzipp it
		global $wp_filesystem;
		$to = $wp_filesystem->wp_content_dir() . "/themes/" . get_option( 'template') . "/functions/";

		$dounzip = unzip_file($temp_file_addr, $to);

		unlink($temp_file_addr); // Delete Temp File

		if ( is_wp_error($dounzip) ) {

			//DEBUG
			$error = $dounzip->get_error_code();
			$data = $dounzip->get_error_data($error);
			//echo $error. ' - ';
			//print_r($data);

			if($error == 'incompatible_archive') {
				//The source file was not found or is invalid
				function colabsthemes_framework_update_no_archive_warning() {
					echo "<div id='colabs-no-archive-warning' class='updated fade'><p>". __("Failed: Incompatible archive","colabsthemes")."</p></div>";
				}
				add_action( 'admin_notices', 'colabsthemes_framework_update_no_archive_warning' );
			}
			if($error == 'empty_archive') {
				function colabsthemes_framework_update_empty_archive_warning() {
					echo "<div id='colabs-empty-archive-warning' class='updated fade'><p>". __("Failed: Empty Archive","colabsthemes")."</p></div>";
				}
				add_action( 'admin_notices', 'colabsthemes_framework_update_empty_archive_warning' );
			}
			if($error == 'mkdir_failed') {
				function colabsthemes_framework_update_mkdir_warning() {
					echo "<div id='colabs-mkdir-warning' class='updated fade'><p>". __("Failed: mkdir Failure","colabsthemes")."</p></div>";
				}
				add_action( 'admin_notices', 'colabsthemes_framework_update_mkdir_warning' );
			}
			if($error == 'copy_failed') {
				function colabsthemes_framework_update_copy_fail_warning() {
					echo "<div id='colabs-copy-fail-warning' class='updated fade'><p>". __("Failed: Copy Failed","colabsthemes")."</p></div>";
				}
				add_action( 'admin_notices', 'colabsthemes_framework_update_copy_fail_warning' );
			}

			return;

		}

		function colabsthemes_framework_updated_success() {
			echo "<div id='framework-upgraded' class='updated fade'><p>". __("New framework successfully downloaded, extracted and updated.","colabsthemes")."</p></div>";
		}
		add_action( 'admin_notices', 'colabsthemes_framework_updated_success' );

		}
	}
	} //End user input save part of the update
 }
}

add_action( 'admin_head','colabsthemes_framework_update_head' );

//Updater Load Scripts
if (!function_exists('colabs_load_only_updater')) {
function colabs_load_only_updater(){
    add_action( 'admin_head', 'colabs_admin_head_editor' );
    function colabs_admin_head_editor(){
		echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/functions/admin-style.css" media="screen" />';        
    }//END function colabs_admin_head_editor
}//END function colabs_load_only_updater
}//END function_exists

/*-----------------------------------------------------------------------------------*/
/* CoLabsFramework Version Getter */
/*-----------------------------------------------------------------------------------*/

function colabs_get_fw_version($url = ''){

	if(!empty($url)){
		$fw_url = $url;
	} else {
    	$fw_url = 'http://colorlabsproject.com/updates/functions-changelog.txt';
    }

    if(empty($fw_url)) return;

	$temp_file_addr = download_url($fw_url);
	if(!is_wp_error($temp_file_addr) && $file_contents = file($temp_file_addr)) {
        foreach ($file_contents as $line_num => $line) {

                $current_line =  $line;

                if($line_num > 1){    // Not the first or second... dodgy :P

                    if (preg_match( '/^[0-9]/', $line)) {

                            $current_line = stristr($current_line,"Version" );
                            $current_line = preg_replace( '~[^0-9,.]~','',$current_line);
                            $output = $current_line;
                            break;
                    }
                }
        }
        unlink($temp_file_addr);
        return $output;


    } else {
        return __("Currently Unavailable","colabsthemes");
    }

}


/*-----------------------------------------------------------------------------------*/
/* CoLabsThemes Update Check Function - colabs_theme_check */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'colabs_theme_check')) {
function colabs_theme_check(){
	$current_theme = wp_get_theme();
	$theme_name = get_option( 'colabs_themename' );
	$storefront_theme = colabs_get_fw_version('http://colorlabsproject.com/updates/'.strtolower($theme_name).'/changelog.txt'); 
	$check_theme_update = version_compare( $storefront_theme, $current_theme->Version, '>' );
	$details_url = add_query_arg(array('TB_iframe' => 'true', 'width' => 1024, 'height' => 800), 'http://colorlabsproject.com/updates/'.strtolower($theme_name).'/changelog.txt');
	$update_url = esc_url( add_query_arg(array( 'page' => 'colabsthemes_framework_update' ) ) );
			
	
	if($check_theme_update==1){
	?>
	<div class="colabs-save-popup" id="colabs-update-theme" style="display:block;">
		<div class="colabs-save-save">
			<?php 
			printf( __('There is a new version of %1$s available. <a href="%2$s" class="thickbox" title="%1$s">View version %3$s details</a> or <a href="%4$s" >Go to update</a>.'), $theme_name, $details_url, $storefront_theme, $update_url);
			?>
		</div>
	</div>
	<?php
	}
}
}
if (!function_exists( 'colabs_theme_update')) {
function colabs_theme_update(){
	
	$theme_name = get_option( 'colabs_themename' );
	$file_url = 'http://colorlabsproject.com/member/downloads/'.strtolower($theme_name).'/'.strtolower($theme_name).'.zip';
	//$file_url = 'http://colorlabsproject.com/member/downloads/rpg/rpg.zip';
	
	// Get Cookie
	// ----------	
	if($_POST['login_attempt_id']=='1342424497'){
		
			//Setup Filesystem
			$method = get_filesystem_method();
			$cred = $_POST;

			$filesystem = WP_Filesystem($cred);


			if($filesystem == false && $_POST['upgrade'] != 'Proceed'){
						$method = get_filesystem_method();
						echo "<div id='filesystem-warning' class='updated fade' ><p>". __("Failed: Filesystem preventing downloads.","colabsthemes")." ( ". $method .")</p></div>";
					return;
			}
			
			global $wp_filesystem;
			
			$to = $wp_filesystem->wp_content_dir() . "/themes/";

			$login = $_POST['amember_login'];
			$pass = $_POST['amember_pass'];
			$form_url = 'http://colorlabsproject.com/member/login.php';

			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $form_url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $to.'cookie.txt');
			curl_setopt($ch, CURLOPT_POSTFIELDS, "amember_login=".$login."&amember_pass=".$pass."&login_attempt_id=1342401953");
			curl_exec($ch);
			curl_close($ch);		
		
	}
	
	// Download files
	// --------------
	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
		if ( ! current_user_can('update_themes') )
			wp_die(__('You do not have sufficient permissions to update themes for this site.'));
	if ( 'colabs-upgrade-theme' == $action ) {
		$cookiefile=get_theme_root() . '/cookie.txt';
		$check_cookie=extractCookies(file_get_contents($cookiefile));
						
		if ($check_cookie==true){
			//Setup Filesystem
			$method = get_filesystem_method();
			$cred = $_POST;

			$filesystem = WP_Filesystem($cred);


			if($filesystem == false && $_POST['upgrade'] != 'Proceed'){

				function colabsthemes_framework_update_filesystem_warning() {
						$method = get_filesystem_method();
						echo "<div id='filesystem-warning' class='updated fade' style='display:block;'><><p>". __("Failed: Filesystem preventing downloads.","colabsthemes")." ( ". $method .")</p></div>";
					}
					add_action( 'admin_notices', 'colabsthemes_framework_update_filesystem_warning' );
					return;
			}
			
			global $wp_filesystem;
			
			$to = $wp_filesystem->wp_content_dir() . "/themes/";
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $file_url);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $to.'cookie.txt');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

			// Create new file
			$file = fopen($to.'/tmp_pack.zip', 'w');
			curl_setopt($ch, CURLOPT_FILE, $file);

			$zip = curl_exec($ch);
			$do_unzip = unzip_file($to.'/tmp_pack.zip', $to);

			curl_close($ch);
			fclose($file); 
			
			unlink($to.'/tmp_pack.zip'); // Delete Temp File
			if ( is_wp_error($do_unzip) ) {
				$error = $do_unzip->get_error_code();
				$data = $do_unzip->get_error_data($error);
				
				if($error == 'incompatible_archive') {
				//The source file was not found or is invalid
					echo "<div id='colabs-no-archive-warning' class='updated fade' ><p>". __("You have no access. Please Visit <a href='http://colorlabsproject.com/member/signup' target='_blank'>order page</a> to order additional subscriptions.","colabsthemes")."</p></div>";
				}
				if($error == 'empty_archive') {
						echo "<div id='colabs-empty-archive-warning' class='updated fade' ><p>". __("Failed: Empty Archive","colabsthemes")."</p></div>";
				}
				if($error == 'mkdir_failed') {
						echo "<div id='colabs-mkdir-warning' class='updated fade' ><p>". __("Failed: mkdir Failure","colabsthemes")."</p></div>";
				}
				if($error == 'copy_failed') {
						echo "<div id='colabs-copy-fail-warning' class='updated fade'><p>". __("Failed: Copy Failed","colabsthemes")."</p></div>";
				}

				return;
			}
		}
	}
	
	//Re-Login
	//---------
	$relogin = isset($_REQUEST['relogin']) ? $_REQUEST['relogin'] : 'false';
	if($relogin=='true'){
		//Setup Filesystem
			$method = get_filesystem_method();
			$cred = $_POST;

			$filesystem = WP_Filesystem($cred);


			if($filesystem == false && $_POST['upgrade'] != 'Proceed'){

						$method = get_filesystem_method();
						echo "<div id='filesystem-warning' class='updated fade' style='display:block;'><p>". __("Failed: Filesystem preventing downloads.","colabsthemes")." ( ". $method .")</p></div>";
					return;
			}
			
			global $wp_filesystem;
			
			$to = $wp_filesystem->wp_content_dir() . "/themes/";
			
			unlink($to.'/cookie.txt'); // Delete Cookie File 
			$adminurl =admin_url( 'admin.php?page=colabsthemes_framework_update');
			?>
			<script type="text/javascript">
				window.location.href = "<?php echo $adminurl;?>";
			</script>
	<?php }
}
}

function extractCookies($string) {
    $member_cookie = false;
    
    $lines = explode("\n", $string);
 
    // iterate over lines
    foreach ($lines as $line) {
 
        // we only care for valid cookie def lines
        if (isset($line[0]) && substr_count($line, "\t") == 6) {
 
            // get tokens in an array
            $tokens = explode("\t", $line);
 
            // trim the tokens
            $tokens = array_map('trim', $tokens);
						
						if($tokens[5]=='amember_nr')
            $member_cookie = true;

        }
    }
    
    return $member_cookie;
}
?>