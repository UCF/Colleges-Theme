<?php
/**
 * General utilities
 **/
function format_raw_postmeta( $postmeta ) {
	$retval = array();

	foreach( $postmeta as $key=>$val ) {
		if ( is_array( $val ) && count( $val ) === 1 ) {
			$retval[$key] = $val[0];
		} else {
			$retval[$key] = $val;
		}
	}

	return $retval;
}


/**
 * Returns a theme mod value from COLLEGES_THEME_CUSTOMIZER_DEFAULTS.
 **/
function get_theme_mod_default( $theme_mod ) {
	$defaults = unserialize( COLLEGES_THEME_CUSTOMIZER_DEFAULTS );
	if ( $defaults && isset( $defaults[$theme_mod] ) ) {
		return $defaults[$theme_mod];
	}
	return false;
}


/**
 * Returns a theme mod value or the default set in COLLEGES_THEME_CUSTOMIZER_DEFAULTS if
 * the theme mod value hasn't been set yet.
 **/
function get_theme_mod_or_default( $theme_mod ) {
	$mod = get_theme_mod( $theme_mod  );
	$default = get_theme_mod_default( $theme_mod );

	// Only apply the default if an explicit theme mod value hasn't been set
	// yet (e.g. immediately after theme activation). Otherwise, assume empty
	// values are intentional.
	if ( $mod === false && $default ) {
		return $default;
	}
	return $mod;
}


/**
 * Utility function that returns an image url by its thumbnail size.
 **/
function get_attachment_src_by_size( $id, $size ) {
	$attachment = wp_get_attachment_image_src( $id, $size, false );
	if ( is_array( $attachment ) ) {
		return $attachment[0];
	}
	return $attachment;
}


/**
 * Checks if a given option is set to the provided value, and updates it
 * if it is not
 */
function force_option_value( $option, $value ) {
	if ( get_option( $option ) !== $value ) {
		update_option( $option, $value );
	}
}
