<?php
/**
 * Plugin Name: Moderate Buddypress xProfile Changes
 * Plugin URI:
 * Description: Moderate BuddyPress xProfile changes using visibility levels.
 * Author: Jo Waltham at Callia Web
 * Author URI: https://www.calliaweb.co.uk
 * Version: 0.0.1
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * This plugin uses Visiibility Levels to moderate profile changes.
 * Creates a new 'moderated' visibility level which is only visible to admin.
 * When a user saves their profile it sets all their profile fields
 * visibility level to moderated and an email is sent to the site admin.
 * The site admin then needs to view the profile and just click save to
 * set all the fields visibility levels back to thier defaults.
 *
 * It requires your profile fields be set to 'Allow members to override'
 * (not Enforce field visibility) otherwise it cannot change the profile
 * fields visibility levels to moderated on save.
 *
 * It disables all but admin to change visibility levels to ensure users do
 * not change their profile fields visibility levels.
 *
 * It does not moderate new registrations.
 * It does not moderate profile avatar and cover images changes.
 */

add_action( 'xprofile_updated_profile', 'jmw_buddypress_profile_update', 10, 5 );
/**
 * Email site admin that profile has been updated.
 * Fires after all XProfile fields have been saved for the current profile.
 *
 * @param int   $user_id          Displayed user ID.
 * @param array $posted_field_ids Array of field IDs that were edited.
 * @param bool  $errors           Whether or not any errors occurred.
 * @param array $old_values       Array of original values before updated.
 * @param array $new_values       Array of newly saved values after update.
 *
 * @since 0.0.1
 *
 * TO DO: Do not email when admin updates profile.
 */
function jmw_buddypress_profile_update( $user_id, $posted_field_ids, $errors, $old_values, $new_values ) {

    $message_subject = sprintf( '%s: Profile moderation required for %s',
    	esc_html( get_bloginfo( 'name' ) ),
    	esc_html( bp_core_get_user_displayname( $user_id ) )
    );

    $message = sprintf( 'Member %s has updated their profile. See %s',
    	esc_html( bp_core_get_user_displayname( $user_id ) ),
    	esc_url( bp_loggedin_user_domain() )
    );

     wp_mail( get_bloginfo( 'admin_email' ), $message_subject, $message );
 }

add_filter( 'bp_xprofile_get_visibility_levels', 'jmw_customize_profile_visibility' );
/**
 * Filter visibility levels and add moderated visibility level.
 *
 * @param array $allowed_visibilities Array of visibility levels
 *
 * @since 0.0.1
 */
function jmw_customize_profile_visibility( $allowed_visibilities ) {

	$allowed_visibilities[ 'moderated' ] = array(
		'id'	=> 'moderated',
		'label'	=> _x( 'No-one - In Moderation', 'Visibility level setting', 'bp-extended-profile-visibility' )
	);

	return $allowed_visibilities;
}


add_filter( 'bp_xprofile_get_hidden_field_types_for_user', 'jmw_get_hidden_visibility_types_for_user', 10, 3 );
/**
 * Set our moderated visibility level to hidden to all but admin.
 *
 * @param array $hidden_fields     Array of hidden fields for the displayed/logged in user.
 * @param int   $displayed_user_id ID of the displayed user.
 * @param int   $current_user_id   ID of the current user.
 * @since 0.0.1
 */
function jmw_get_hidden_visibility_types_for_user( $hidden_levels, $displayed_user_id, $current_user_id ) {

	if( ! current_user_can( 'manage_options' ) ) {
		$hidden_levels[] = 'moderated';
	}

	return $hidden_levels;

}

 add_filter( 'bp_current_user_can', 'jmw_permit_admin_to_edit_field_visibility', 10, 4 );
/**
 * Override custom visibility setting.
 *
 * @param bool   $retval     Whether or not the current user has the capability.
 * @param string $capability The capability being checked for.
 * @param int    $blog_id    Blog ID. Defaults to the BP root blog.
 * @param array  $args       Array of extra arguments as originally passed.
 *
 * @since 0.0.1
 */
 function jmw_permit_admin_to_edit_field_visibility( $retval, $capability, $blog_id, $args ) {

 	if( 'bp_xprofile_change_field_visibility' != $capability ) {
 		return $retval;
 	}

 	if( current_user_can( 'manage_options' ) ) {
 		$retval = true;
 	}
 	else {
 		$retval = false;
 	}

 	return $retval;
 }

add_action( 'xprofile_profile_field_data_updated', 'jmw_moderate_profile_field_data', 10, 2 );
/**
 * When profile field is saved change visibility level to moderated,
 * unless admin then change it back to the field default.
 *
 * Fires after the saving of each profile field, if successful.
 *
 * @param int    $field_id ID of the field being updated.
 * @param string $value    Value that was saved to the field.
 *
 * @since 0.0.1
 */
function jmw_moderate_profile_field_data( $field_id, $value ) {

	if( current_user_can( 'manage_options' ) ) {
		$field = xprofile_get_field( $field_id );
		$visibility_level = $field->default_visibility;
	} else {
		$visibility_level = 'moderated';
	}

	xprofile_set_field_visibility_level( $field_id, bp_displayed_user_id(), $visibility_level );
}
