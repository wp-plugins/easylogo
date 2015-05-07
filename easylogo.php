<?php
/*
Plugin Name: Easy Logo
Plugin URI: http://plugins.imvarunkmr.net/easylogo
Description: Upload logos on your WordPress sites and manage them easily
Version: 1.3
Author: Varun Kumar
Author URI: http://imvarunkmr.net
License: GPLv2  
*/
?><?php
/* Copyright 2014 VARUN KUMAR (email : imvarunkmr@gmail.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/
?><?php

add_action( 'admin_menu', 'elv_easylogo_add_settings_page' );
/**
 * Adds a new settings page under Appearance menu
 *
 * @since Easy Logo 1.0
 */
function elv_easylogo_add_settings_page() {
	add_theme_page( __( 'Change Logo' ), __( 'Easy Logo' ), 'manage_options', 'elv_easylogo_main_page', 'elv_easylogo_display_main_page' );
}

/**
 * Retrieves plugin options if they exist or returns default values if not
 *
 * @since Easy Logo 1.0
 * 
 * @return array of Easy Logo options or default values
 */
function elv_easylogo_get_options() {
	if( get_option( 'elv_easylogo_options' ) === false ) {
		$elv_easylogo_options['image_path'] = "";
		$elv_easylogo_options['hover'] = "none";
		$elv_easylogo_options['responsive'] = 0;
		$elv_easylogo_options['retina_version'] = "";
		$elv_easylogo_options['use_retina'] = 0;
	}
	else {
		$elv_easylogo_options = get_option( 'elv_easylogo_options' );
		
		// If the checkbox is unchecked for responisve logo
		if( !isset( $elv_easylogo_options['responsive'] ) )
			$elv_easylogo_options['responsive'] = 0;
		
		// If the checkbox is unchecked for retina version
		if( !isset( $elv_easylogo_options['use_retina'] ) )
			$elv_easylogo_options['use_retina'] = 0;
	}
	
	
	return $elv_easylogo_options;
	
}

/**
 * Displays the settings page
 *
 * @since Easy Logo 1.0
 */
function elv_easylogo_display_main_page() {
	?><div class="wrap">
		<h2>Easy Logo </h2>
		<p class="description" >Add logo to your theme hassle free</p>
		<form action="options.php" method="post">
			<?php settings_fields( 'elv_easylogo_options' ); ?>
			<?php do_settings_sections( 'elv_easylogo' ); ?>
			<input type="submit" name="submit" value="Save Setting" class="button-primary" />
		</form>
	</div><?php
}

add_action( 'admin_init', 'elv_easylogo_register_settings' );

/**
 * Registers settings for Easy Logo
 *
 * Also adds settings sections and settings fields
 *
 * @since Easy Logo 1.0
 */
function elv_easylogo_register_settings(){
	
	/**
	 * Registers Main setting for Easy Logo
	 *
	 */
	register_setting( 'elv_easylogo_options', 'elv_easylogo_options', 'elv_easylogo_validate_options' );
	
	/**
	 * Adds a settings section 
	 *
	 */
	add_settings_section( 'elv_easylogo_main_section', 'Settings', 'elv_easylogo_text', 'elv_easylogo' );
	
	/**
	 * Adds a setting field  for logo image selection 
	 *
	 */
	add_settings_field( 'elv_easylogo_image_path', 'Select Easy logo Image', 'elv_easylogo_image_path_input', 'elv_easylogo', 'elv_easylogo_main_section' );	
	
	/**
	 * Adds a setting field  for selecting the hover effect
	 *
	 */
	add_settings_field( 'elv_easylogo_hover', 'Select Hover Effect', 'elv_easylogo_hover_select', 'elv_easylogo', 'elv_easylogo_main_section' );
	
	/**
	 * Adds a setting field to make logo responsive
	 *
	 */
	add_settings_field( 'elv_easylogo_responsive', 'Make logo responsive?', 'elv_easylogo_responsive_select', 'elv_easylogo', 'elv_easylogo_main_section' );
	
	/**
	 * Upload retina version of the image
	 *
	 */
	add_settings_field( 'elv_easylogo_retina', 'Upload retina version', 'elv_easylogo_retina_version_upload', 'elv_easylogo', 'elv_easylogo_main_section' );	
}

/**
 * Displays text info for main settings section
 *
 * @since Easy Logo 1.0
 */
function elv_easylogo_text() {
	/**
	 * Currently displays nothing
	 *
	 */
	echo '<span class="update-nag">Dear user, kindly paste <b>'. htmlspecialchars("<?php show_easylogo(); ?>") . '</b> in your header.php where you want to display the logo.</span>';
}

/**
 * Displays text field for image URL
 *
 * Also displays a button to select image from media library 
 *
 * @since Easy Logo 1.0
 */
function elv_easylogo_image_path_input() {
	$options = elv_easylogo_get_options();
	$elv_easylogo_image = $options['image_path'];
	$elv_easylogo_hover_effect = $options['hover'];
	
	/**
	 * Markup for displaying text field and media library button
	 *
	 */
	?><p>
		<input id="upload_image_button" type="button" value="Media Library" class="button-secondary" />
		<input id="elv_easy_logo_image" class="regular-text code" type="text" name="elv_easylogo_options[image_path]" value="<?php echo esc_attr($elv_easylogo_image); ?>">
	</p>
	<p class="description">Enter an image URL or use an image from media library.</p> <?php
}

/**
 * Displays select box for choosing hover.css hover effect 
 *
 * Also displays a button to select image from media library 
 *
 * @since Easy Logo 1.0
 */
function elv_easylogo_hover_select(){ 
	$options = elv_easylogo_get_options();
	$elv_easylogo_image = $options['image_path'];
	$elv_easylogo_hover_effect = $options['hover'];
	
	/**
	 * Markup for displaying the select box 
	 *
	 */
	?><p><select id="elv_select_hover_effect" name= "elv_easylogo_options[hover]">
		<option value="none" <?php selected($elv_easylogo_hover_effect, 'none'); ?>>None</option>
		<option value="grow" <?php selected($elv_easylogo_hover_effect, 'grow'); ?>>Grow</option>
		<option value="shrink" <?php selected($elv_easylogo_hover_effect, 'shrink'); ?>>Shrink</option>
		<option value="pulse" <?php selected($elv_easylogo_hover_effect, 'pulse'); ?>>Pulse</option>
		<option value="pulse-grow" <?php selected($elv_easylogo_hover_effect, 'pulse-grow'); ?>>Pulse Grow</option>
		<option value="pulse-shrink" <?php selected($elv_easylogo_hover_effect, 'pulse-shrink'); ?>>Pulse Shrink</option>
		<option value="push" <?php selected($elv_easylogo_hover_effect, 'push'); ?>>Push</option>
		<option value="pop" <?php selected($elv_easylogo_hover_effect, 'pop'); ?>>Pop</option>
		<option value="rotate" <?php selected($elv_easylogo_hover_effect, 'rotate'); ?>>Rotate</option>
		<option value="grow-rotate" <?php selected($elv_easylogo_hover_effect, 'grow-rotate'); ?>>Grow Rotate</option>
		<option value="float" <?php selected($elv_easylogo_hover_effect, 'float'); ?>>Float</option>
		<option value="sink" <?php selected($elv_easylogo_hover_effect, 'sink'); ?>>Sink</option>
		<option value="hover" <?php selected($elv_easylogo_hover_effect, 'hover'); ?>>Hover</option>
		<option value="hang" <?php selected($elv_easylogo_hover_effect, 'hang'); ?>>Hang</option>
		<option value="skew" <?php selected($elv_easylogo_hover_effect, 'skew'); ?>>Skew</option>
		<option value="skew-forward" <?php selected($elv_easylogo_hover_effect, 'skew-forward'); ?>>Skew Forward</option>
		<option value="skew-backward" <?php selected($elv_easylogo_hover_effect, 'skew-backward'); ?>>Skew Backward</option>
		<option value="wobble-horizontal" <?php selected($elv_easylogo_hover_effect, 'wobble-horizontal'); ?>>Wobble Horizontal</option>
		<option value="wobble-vertical" <?php selected($elv_easylogo_hover_effect, 'wobble-vertical'); ?>>Wobble Vertical</option>
		<option value="wobble-to-bottom-right" <?php selected($elv_easylogo_hover_effect, 'wobble-to-bottom-right'); ?>>Wobble to bottom right</option>
		<option value="wobble-to-top-right" <?php selected($elv_easylogo_hover_effect, 'wobble-to-top-right'); ?>>Wobble to top right</option>
		<option value="wobble-top" <?php selected($elv_easylogo_hover_effect, 'wobble-top'); ?>>Wobble Top</option>
		<option value="wobble-bottom" <?php selected($elv_easylogo_hover_effect, 'wobble-bottom'); ?>>Wobble Bottom</option>
		<option value="wobble-skew" <?php selected($elv_easylogo_hover_effect, 'wobble-skew'); ?>>Wobble Skew</option>
		<option value="buzz" <?php selected($elv_easylogo_hover_effect, 'buzz'); ?>>Buzz</option>
		<option value="buzz-out" <?php selected($elv_easylogo_hover_effect, 'buzz-out'); ?>>Buzz Out</option>
		<option value="float-shadow" <?php selected($elv_easylogo_hover_effect, 'float-shadow'); ?>>Float Shadow</option>
		<option value="hover-shadow" <?php selected($elv_easylogo_hover_effect, 'hover-shadow'); ?>>Hover Shadow</option>
		<option value="shadow-radial" <?php selected($elv_easylogo_hover_effect, 'shadow-radial'); ?>>Shadow Radial</option>
		<option value="bubble-top" <?php selected($elv_easylogo_hover_effect, 'bubble-top'); ?>>Bubble Top</option>
		<option value="bubble-bottom" <?php selected($elv_easylogo_hover_effect, 'bubble-bottom'); ?>>Bubble Bottom</option>
		<option value="bubble-right" <?php selected($elv_easylogo_hover_effect, 'bubble-right'); ?>>Bubble Right</option>
		<option value="bubble-left" <?php selected($elv_easylogo_hover_effect, 'bubble-left'); ?>>Bubble Left</option>
		<option value="bubble-float-top" <?php selected($elv_easylogo_hover_effect, 'bubble-float-top'); ?>>Bubble Float Top</option>
		<option value="bubble-float-right" <?php selected($elv_easylogo_hover_effect, 'bubble-float-right'); ?>>Bubble Float Right</option>
		<option value="bubble-float-left" <?php selected($elv_easylogo_hover_effect, 'bubble-float-left'); ?>>Bubble Float Left</option>
		<option value="bubble-float-bottom" <?php selected($elv_easylogo_hover_effect, 'bubble-float-bottom'); ?>>Bubble Float Bottom</option>
		<option value="curl-top-left" <?php selected($elv_easylogo_hover_effect, 'curl-top-left'); ?>>Curl Top Left</option>
		<option value="curl-top-right" <?php selected($elv_easylogo_hover_effect, 'curl-top-right'); ?>>Curl Top Right</option>
		<option value="curl-bottom-left" <?php selected($elv_easylogo_hover_effect, 'curl-bottom-left'); ?>>Curl Bottom Left</option>
		<option value="curl-bottom-right" <?php selected($elv_easylogo_hover_effect, 'curl-bottom-right'); ?>>Curl Bottom Right</option>
	</select></p>
	<p class="description">Few recommended effects - Float Shadow, Shadow Radial, Curl-(any)</p>
	<span style="line-height:0" id="easylogo-admin-preview-p" class="<?php echo $elv_easylogo_hover_effect; ?>">
	<img id="elv_easylogo_admin_preview" src="<?php echo $elv_easylogo_image; ?>" alt="Logo" /></span><?php 
}

/**
 * Displays checkbox field for responsive image
 *
 * @since Easy Logo 1.0
 */
function elv_easylogo_responsive_select() {
	$options = elv_easylogo_get_options();
	$elv_easylogo_is_responsive = $options['responsive'];
	/**
	 * Markup for displaying the select box 
	 *
	 */
	?><input name="elv_easylogo_options[responsive]" type="checkbox" value="true" <?php checked( $elv_easylogo_is_responsive, "true" ); ?> /> Yes
	<p class="description">If you already use any responive design plugins, you may keep this option unchecked </p><?php
}

/**
 * Displays markup to allow users to upload retina version of their logo 
 *
 * @since Easy Logo 1.0
 */
function elv_easylogo_retina_version_upload() {
	$options = elv_easylogo_get_options();
	$elv_easylogo_retina_version = $options['retina_version'];
	$elv_easylogo_is_retina_checked = $options['use_retina'];
	
	/**
	 * Markup for displaying text field and media library button
	 *
	 */
	?><p><input type="checkbox" name="elv_easylogo_options[use_retina]" value="use_retina" <?php checked( $elv_easylogo_is_retina_checked, "use_retina" ); ?> />Use below image on retina screens</p>
	<p>
		<input id="upload_retina_image_button" type="button" value="Media Library" class="button-secondary" />
		<input id="elv_easy_logo_retina_image" class="regular-text code" type="text" name="elv_easylogo_options[retina_version]" value="<?php echo esc_attr($elv_easylogo_retina_version); ?>">
	</p>
	<p class="description">Please make sure @2x is appended in retina version of image file.
		 <br />Rest of the filename should be exactly the same</p> <?php
}

/**
 * Validates the form submission by user
 *
 * @since Easy Logo 1.0
 */
function elv_easylogo_validate_options($input) {
	
	$input['image_path'] = esc_url( $input['image_path'] );
	
	return $input;
	
}

/**
 * This function displays the logo on the front end of the site
 * 
 * User needs to call this function inside his theme where he wants to display the logo
 *
 * @since Easy Logo 1.0
 */
function show_easylogo() {
	$options = elv_easylogo_get_options();
	$elv_easylogo_image = $options['image_path'];
	$elv_easylogo_hover_effect = $options['hover'];
	$elv_easylogo_is_responsive = $options['responsive'];
	
	if( $elv_easylogo_image === "" ) { ?>
		<h2 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h2><?php 
	}
	else { ?>
		<h2><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
			<span style = "line-height:0" class = "<?php echo $elv_easylogo_hover_effect; ?>">
			<img src="<?php echo $elv_easylogo_image; ?>" alt="<?php bloginfo( 'name' ); ?>"
			<?php if( $elv_easylogo_is_responsive == "true" ) {?> style = "max-width:100%" <?php } ?> />
			</span></a></h2><?php
	}
}

add_action( 'admin_print_scripts', 'elv_easylogo_scripts' );
/**
 * Enqueues Easy Logo javascript files
 *
 * @since Easy Logo 1.0
 */
function elv_easylogo_scripts() {
	wp_enqueue_script( 'elv_easylogo_js', plugins_url('/easylogo/js/easylogo.js'), array( 'jquery', 'media-upload', 'thickbox' ) );
}

add_action( 'template_redirect', 'elv_easylogo_scripts_theme_only' );
/**
 * Enqueues retina.js
 *
 * @since Easy Logo 1.0
 */
function elv_easylogo_scripts_theme_only() {
	/**
	 * enqueue retina.js if retina version has been uploaded
	 *
	 */
	$options = elv_easylogo_get_options();
	if( $options['use_retina'] === "use_retina" )
		wp_enqueue_script( 'elv_easylogo_retina_js', plugins_url('/easylogo/js/retina.min.js'), array(), '', true );	
}

add_action( 'admin_print_styles', 'elv_easylogo_styles_admin' );
/**
 * enqueues 'Easy Logo' and 'WordPress thickbox' styles in admin and front end
 * 
 * Conditionaly checks and inserts hover.css if required
 *
 * @since Easy Logo 1.0
 */
function elv_easylogo_styles_admin() {
	wp_enqueue_media(); // Fixing media library button
	wp_enqueue_style( 'thickbox' );
	wp_enqueue_style( 'elv_hover_css', plugins_url('/easylogo/css/hover/hover-min.css'), array(), '', false );
}

add_action( 'template_redirect', 'elv_easylogo_styles_front_end' );
/**
 * enqueues hover.css in front end of website
 * 
 * Only inserts when user has selected some effects
 *
 * @since Easy Logo 1.0
 */
function elv_easylogo_styles_front_end() {
	$options = elv_easylogo_get_options();
	$elv_easylogo_hover_effect = $options['hover'];
	if( $options != false && $elv_easylogo_hover_effect !='none' ) {
		wp_enqueue_style( 'elv_hover_css', plugins_url('/easylogo/css/hover/hover-min.css'), array(), '', false );
	}
}

register_deactivation_hook('__FILE__', 'elv_easylogo_uninstall');
/**
 * removes easylogo options upon deactivation
 *
 * @since Easy Logo 1.0
 */
function elv_easylogo_uninstall() {
	delete_option('elv_easylogo_options');
}