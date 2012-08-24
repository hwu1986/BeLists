<?php

/**
 * Add more profile fields to the user
 *
 * Easy to add new fields to the user profile by just
 * creating your new section below and adding a new
 * update_user_meta line
 *
 * @since 3.0.0
 * @uses show_user_profile & edit_user_profile WordPress functions
 *
 * @param int $user User Object
 * @return bool True on successful update, false on failure.
 *
 */

global $colabs_extended_profile_fields;

$colabs_extended_profile_fields = array(
	'twitter_id' => array(
		'title'=> __('Twitter:','colabsthemes'),
		'type' => 'text',
		'description' => __('Enter your Twitter username without the URL.','colabsthemes'),
		'admin_description' => ''
	),
	'facebook_id' => array(
		'title'=> __('Facebook:','colabsthemes'),
		'type' => 'text',
		'description' =>  sprintf(__("Enter your Facebook username without the URL. <br />Don't have one yet? <a target='_blank' href='%s'>Get a custom URL.</a>",'colabsthemes'), 'http://www.facebook.com/username/'),
		'admin_description' => ''
	),
	'paypal_email' => array(
		'title'=> __('PayPal Email:','colabsthemes'),
		'type' => 'text',
		'description' =>  __('Used for purchasing ads via PayPal (if enabled).','colabsthemes'),
		'admin_description' => ''
	),

	'active_membership_pack' => array(
		'title'=> __('Active Membership Pack:','colabsthemes'),
		'protected' => 'yes',
		'type' => 'active_membership_pack',
		'description' =>  __('Custom Membership Pack active for the user. Can only be changed by admins.','colabsthemes'),
		'admin_description' => __('Enter Pack ID to activate membership for user.','colabsthemes')
	),
	'membership_expires' => array(
		'title'=> __('Membership Pack Expires Date:','colabsthemes'),
		'protected' => 'yes',
		'type' => 'date',
		'description' =>  __('Date for unlimited/dealer posting (if enabled). Can only be changed by admins.','colabsthemes'),
		'admin_description' => __('Enter date in format <code>m/d/Y H:i</code> Example date: <code>01/26/2012 13:25</code>','colabsthemes')
	)

);
$colabs_extended_profile_fields = apply_filters('colabs_extended_profile_fields', $colabs_extended_profile_fields);



// display the additional user profile fields
if (!function_exists('cp_profile_fields')) {
    function cp_profile_fields($user) {
	global $colabs_extended_profile_fields;
?>
		<h3><?php _e('Extended Profile', 'colabsthemes'); ?></h3>
        <table class="form-table">

			<?php
			foreach ( $colabs_extended_profile_fields as $field_id => $field_values ) :

				if ( isset($field_values['protected']) && $field_values['protected'] == 'yes' && !is_admin() )
				    $protected = 'disabled="disabled"';
				else
				    $protected = '';

				//TODO - use this value for display purposes while protecting stored value
				//prepare, modify, or filter the field value based on the field ID
				switch ($field_id):
					case 'active_membership_pack':
						$the_display_value = get_pack(get_the_author_meta( $field_id, $user->ID ),'','pack_name');
						break;
					default:
						$the_display_value = false;
						break;
				endswitch;
				$the_value =  get_the_author_meta( $field_id, $user->ID );

				//begin writing the row and heading
				?>
						<tr id="<?php echo $field_id; ?>_row">
							<th><label for="<?php echo $field_id; ?>"><?php echo esc_html( $field_values['title'] ); ?></label></th>
                            <td>
				<?php
				//print the appropriate profile field based on the type of field
				switch ($field_values['type']):

					case 'date':
				?>
								<input type="text" name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" value="<?php esc_attr_e( $the_value ); ?>" class="regular-text" size="35" <?php if(!empty($protected)) echo 'style="display: none;"'; ?> /><br />
								<span class="description" <?php if(!empty($protected)) echo 'style="display: none;"'; ?> ><?php echo $field_values['admin_description']; ?><br /></span>
                <input type="text" name="<?php echo $field_id; ?>_display" id="<?php echo $field_id; ?>" value="<?php esc_attr_e( colabs_display_date($the_value) ); ?>" class="regular-text" size="35" disabled="disabled" /><br />
								<span class="description"><?php echo $field_values['description']; ?></span>
				<?php
					break;

					case 'active_membership_pack':
				?>
								<input type="text" name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" value="<?php esc_attr_e( $the_value ); ?>" class="regular-text" size="35" <?php if(!empty($protected)) echo 'style="display: none;"'; ?> /><br />
								<span class="description" <?php if(!empty($protected)) echo 'style="display: none;"'; ?> ><?php echo $field_values['admin_description']; ?><br /></span>
                <input type="text" name="<?php echo $field_id; ?>_display" id="<?php echo $field_id; ?>" value="<?php esc_attr_e( $the_display_value ); ?>" class="regular-text" size="35" disabled="disabled" /><br />
								<span class="description"><?php echo $field_values['description']; ?></span>
				<?php
					break;

					default:
				?>
								<input type="text" name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" value="<?php echo esc_attr_e( $the_value ); ?>" class="regular-text" size="35" <?php echo $protected ?> /><br />
								<span class="description"><?php echo $field_values['description']; ?></span>
				<?php
					break;

					//close the row
				?>
                    		</td>
						</tr>
                <?php
				endswitch;

			endforeach;
			?>

		</table>

    <?php
    }
}
add_action('show_user_profile', 'cp_profile_fields', 0);
add_action('edit_user_profile', 'cp_profile_fields');


// save the user profile fields
if (!function_exists('cp_profile_fields_save')) {
    function cp_profile_fields_save($user_id) {
    	global $colabs_extended_profile_fields;

        if ( !current_user_can('edit_user', $user_id) ) return false;

        /* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
       	foreach ($colabs_extended_profile_fields as $field_id => $field_values) :

			switch ( $field_values['type'] ) :
				case 'protected':
					//make sure the user is an admin or has the ability to edits all user accounts
					if ( current_user_can('edit_users') ) update_user_meta( $user_id, $field_id, sanitize_text_field( $_POST[$field_id] ) );
					break;
				default:
					update_user_meta( $user_id, $field_id, sanitize_text_field( $_POST[$field_id] ) );
			endswitch;

		endforeach;

    }
}
add_action('personal_options_update', 'cp_profile_fields_save');
add_action('edit_user_profile_update', 'cp_profile_fields_save');
?>
