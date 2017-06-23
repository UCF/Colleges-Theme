<?php
/**
 * Handle all theme configuration here
 **/

define( 'THEME_URL', get_stylesheet_directory_uri() );
define( 'THEME_STATIC_URL', THEME_URL . '/static' );
define( 'THEME_CSS_URL', THEME_STATIC_URL . '/css' );
define( 'THEME_JS_URL', THEME_STATIC_URL . '/js' );
define( 'THEME_CUSTOMIZER_PREFIX', 'ucf_colleges_' );
define( 'THEME_CUSTOMIZER_DEFAULTS', serialize( array(
	// TODO
) ) );

function __init__() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
	add_theme_support( 'title-tag' );

	add_image_size( 'header-img', 575, 575, true );
	add_image_size( 'header-img-sm', 767, 400, true );
	add_image_size( 'header-img-md', 991, 400, true );
	add_image_size( 'header-img-lg', 1199, 400, true );
	add_image_size( 'header-img-xl', 1600, 400, true );
	add_image_size( 'bg-img', 575, 2000, true );
	add_image_size( 'bg-img-sm', 767, 2000, true );
	add_image_size( 'bg-img-md', 991, 2000, true );
	add_image_size( 'bg-img-lg', 1199, 2000, true );
	add_image_size( 'bg-img-xl', 1600, 2000, true );

	register_nav_menu( 'header-menu', __( 'Header Menu' ) );
	register_nav_menu( 'footer-menu-1', __( 'Footer Menu 1' ) );
	register_nav_menu( 'footer-menu-2', __( 'Footer Menu 2' ) );

	register_sidebar( array(
		'name' => __( 'Footer - Column One' ),
		'id' => 'footer-one',
		'description' => 'Far left column in footer on the bottom of pages. If left empty, this column will contain a UCF logo and your organization\'s address.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
	) );
	register_sidebar( array(
		'name' => __( 'Footer - Column Two' ),
		'id' => 'footer-two',
		'description' => 'Second column from the left in footer, on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
	) );
}

add_action( 'after_setup_theme', '__init__' );


function define_customizer_sections( $wp_customize ) {
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'webfonts',
		array(
			'title' => 'Web Fonts'
		)
	);
}

add_action( 'customize_register', 'define_customizer_sections' );


function define_customizer_fields( $wp_customize ) {
	// Web Fonts
	$wp_customize->add_setting(
		'cloud_typography_key',
		array(
			'default' => get_theme_mod_default( 'cloud_typography_key' )
		)
	);

	$wp_customize->add_control(
		'cloud_typography_key',
		array(
			'type'        => 'text',
			'label'       => 'Cloud.Typography CSS Key URL',
			'description' => 'The CSS Key provided by Cloud.Typography for this project.  <strong>Only include the value in the "href" portion of the link
								tag provided; e.g. "//cloud.typography.com/000000/000000/css/fonts.css".</strong><br><br>NOTE: Make sure the Cloud.Typography
								project has been configured to deliver fonts to this site\'s domain.<br>
								See the <a target="_blank" href="http://www.typography.com/cloud/user-guide/managing-domains">Cloud.Typography docs on managing domains</a> for more info.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'webfonts'
		)
	);
}

add_action( 'customize_register', 'define_customizer_fields' );


/**
 * Enable TinyMCE formatting options in the Athena Shortcodes plugin.
 **/
if ( function_exists( 'athena_sc_tinymce_init' ) ) {
	add_filter( 'athena_sc_enable_tinymce_formatting', '__return_true' );
}


/**
 * Remove paragraph tag from excerpts
 **/
remove_filter( 'the_excerpt', 'wpautop' );


/**
 * Disable comments and trackbacks/pingbacks on this site.
 **/
update_option( 'default_ping_status', 'off' );
update_option( 'default_comment_status', 'off' );


/**
 * Hide unused admin tools
 **/
function hide_admin_links() {
	remove_menu_page( 'edit-comments.php' );
}

add_action( 'admin_menu', 'hide_admin_links' );


/**
 * Prevent Wordpress from trying to redirect to a "loose match" post when
 * an invalid URL is requested.  WordPress will redirect to 404.php instead.
 *
 * See http://wordpress.stackexchange.com/questions/3326/301-redirect-instead-of-404-when-url-is-a-prefix-of-a-post-or-page-name
 **/
function no_redirect_on_404( $redirect_url ) {
	if ( is_404() ) {
		return false;
	}
	return $redirect_url;
}

add_filter( 'redirect_canonical', 'no_redirect_on_404' );


/**
 * Kill attachment pages, author pages, daily archive pages, search, and feeds.
 *
 * http://betterwp.net/wordpress-tips/disable-some-wordpress-pages/
 **/
function kill_unused_templates() {
	global $wp_query, $post;

	if ( is_author() || is_attachment() || is_day() || is_search() || is_feed() ) {
		wp_redirect( home_url() );
		exit();
	}
}

add_action( 'template_redirect', 'kill_unused_templates' );

