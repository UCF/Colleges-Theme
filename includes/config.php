<?php
/**
 * Handle all theme configuration here
 **/

define( 'THEME_URL', get_stylesheet_directory_uri() );
define( 'THEME_STATIC_URL', THEME_URL . '/static' );
define( 'THEME_CSS_URL', THEME_STATIC_URL . '/css' );
define( 'THEME_JS_URL', THEME_STATIC_URL . '/js' );
define( 'THEME_IMG_URL', THEME_STATIC_URL . '/img' );
define( 'THEME_CUSTOMIZER_PREFIX', 'ucf_colleges_' );
define( 'THEME_CUSTOMIZER_DEFAULTS', serialize( array(
	'apply_undergraduate_url' => 'https://apply.ucf.edu/application/',
	'apply_graduate_url'      => 'https://application.graduate.ucf.edu/',
	'person_header_title'     => 'Faculty and Research',
	'person_header_subtitle'  => get_bloginfo( 'name' ),
	'person_thumbnail'        => THEME_IMG_URL . '/no-photo.jpg'
) ) );


function __init__() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
	add_theme_support( 'title-tag' );

	add_image_size( 'header-img', 575, 575, true );
	add_image_size( 'header-img-sm', 767, 525, true );
	add_image_size( 'header-img-md', 991, 525, true );
	add_image_size( 'header-img-lg', 1199, 525, true );
	add_image_size( 'header-img-xl', 1600, 525, true );
	add_image_size( 'header-img-short-sm', 767, 400, true );
	add_image_size( 'header-img-short-md', 991, 400, true );
	add_image_size( 'header-img-short-lg', 1199, 400, true );
	add_image_size( 'header-img-short-xl', 1600, 400, true );
	add_image_size( 'bg-img', 575, 2000, true );
	add_image_size( 'bg-img-sm', 767, 2000, true );
	add_image_size( 'bg-img-md', 991, 2000, true );
	add_image_size( 'bg-img-lg', 1199, 2000, true );
	add_image_size( 'bg-img-xl', 1600, 2000, true );

	register_nav_menu( 'header-menu', __( 'Header Menu' ) );

	register_sidebar( array(
		'name' => __( 'Footer - Column 1' ),
		'id' => 'footer-col-1',
		'description' => 'Center column in footer, on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title'  => '<h2 class="h6 heading-underline letter-spacing-3">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer - Column 2' ),
		'id' => 'footer-col-2',
		'description' => 'Far right column in footer, on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title'  => '<h2 class="h6 heading-underline letter-spacing-3">',
		'after_title'   => '</h2>',
	) );
}

add_action( 'after_setup_theme', '__init__' );


function define_customizer_sections( $wp_customize ) {
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'analytics',
		array(
			'title' => 'Analytics'
		)
	);

	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'webfonts',
		array(
			'title' => 'Web Fonts'
		)
	);

	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'admissions',
		array(
			'title' => 'UCF Admissions'
		)
	);

	if ( post_type_exists( 'person' ) ) {
		$wp_customize->add_section(
			THEME_CUSTOMIZER_PREFIX . 'people',
			array(
				'title' => 'People'
			)
		);
	}

	if ( post_type_exists( 'degree' ) ) {
		$wp_customize->add_section(
			THEME_CUSTOMIZER_PREFIX . 'degrees',
			array(
				'title' => 'Degrees'
			)
		);
	}
}

add_action( 'customize_register', 'define_customizer_sections' );


function define_customizer_fields( $wp_customize ) {
	// Site Identity
	$wp_customize->add_setting(
		'organization_address'
	);

	$wp_customize->add_control(
		'organization_address',
		array(
			'type'        => 'textarea',
			'label'       => 'Organization Address',
			'description' => 'The address of your organization.',
			'section'     => 'title_tagline'
		)
	);

	// Analytics
	$wp_customize->add_setting(
		'gw_verify'
	);

	$wp_customize->add_control(
		'gw_verify',
		array(
			'type'        => 'text',
			'label'       => 'Google WebMaster Verification',
			'description' => 'Example: <em>9Wsa3fspoaoRE8zx8COo48-GCMdi5Kd-1qFpQTTXSIw</em>',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'analytics'
		)
	);

	$wp_customize->add_setting(
		'gtm_id'
	);

	$wp_customize->add_control(
		'gtm_id',
		array(
			'type'        => 'text',
			'label'       => 'Google Tag Manager Container ID',
			'description' => 'The ID for the container in Google Tag Manager that represents this site. Takes precedence over a Google Analytics Account value below if both are provided.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'analytics'
		)
	);

	$wp_customize->add_setting(
		'ga_account'
	);

	$wp_customize->add_control(
		'ga_account',
		array(
			'type'        => 'text',
			'label'       => 'Google Analytics Account',
			'description' => 'Example: <em>UA-9876543-21</em>. Leave blank for development.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'analytics'
		)
	);

	// Web Fonts
	$wp_customize->add_setting(
		'cloud_typography_key'
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

	// Admissions
	$wp_customize->add_setting(
		'apply_undergraduate_url',
		array(
			'default' => get_theme_mod_default( 'apply_undergraduate_url' )
		)
	);

	$wp_customize->add_control(
		'apply_undergraduate_url',
		array(
			'type'        => 'text',
			'label'       => 'Undergraduate Application URL',
			'description' => 'URL that points to the undergraduate student application.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'admissions'
		)
	);

	$wp_customize->add_setting(
		'apply_graduate_url',
		array(
			'default' => get_theme_mod_default( 'apply_graduate_url' )
		)
	);

	$wp_customize->add_control(
		'apply_graduate_url',
		array(
			'type'        => 'text',
			'label'       => 'Graduate Application URL',
			'description' => 'URL that points to the graduate student application.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'admissions'
		)
	);

	// People
	if ( post_type_exists( 'person' ) ) {

		$wp_customize->add_setting(
			'person_header_title',
			array(
				'default' => get_theme_mod_default( 'person_header_title' )
			)
		);

		$wp_customize->add_control(
			'person_header_title',
			array(
				'type'        => 'text',
				'label'       => 'Default Header Title Text',
				'description' => 'Title text to use by default in the header in single person templates. Can be overridden per-person.',
				'section'     => THEME_CUSTOMIZER_PREFIX . 'people'
			)
		);

		$wp_customize->add_setting(
			'person_header_subtitle',
			array(
				'default' => get_theme_mod_default( 'person_header_subtitle' )
			)
		);

		$wp_customize->add_control(
			'person_header_subtitle',
			array(
				'type'        => 'text',
				'label'       => 'Default Header Subtitle Text',
				'description' => 'Subtitle text to use by default in the header in single person templates. Can be overridden per-person.',
				'section'     => THEME_CUSTOMIZER_PREFIX . 'people'
			)
		);

		$wp_customize->add_setting(
			'person_thumbnail', array(
				'default' => get_theme_mod_default( 'person_thumbnail' )
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'person_thumbnail',
				array(
					'label'       => 'Default Thumbnail',
					'description' => 'Default thumbnail image to use when displaying photos of people.',
					'section'     => THEME_CUSTOMIZER_PREFIX . 'people'
				)
			)
		);

		$wp_customize->add_setting(
			'person_header_image'
		);

		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				'person_header_image',
				array(
					'label'       => 'Default Header Image (-sm+)',
					'description' => 'Default header image at the -sm breakpoint and up for single person templates. Recommended dimensions: 1600px x 400px',
					'section'     => THEME_CUSTOMIZER_PREFIX . 'people',
					'mime_type'   => 'image'
				)
			)
		);

		$wp_customize->add_setting(
			'person_header_image_xs'
		);

		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				'person_header_image_xs',
				array(
					'label'       => 'Default Header Image (-xs)',
					'description' => 'Default header image at the -xs breakpoint for single person templates. Recommended dimensions: 575px x 575px',
					'section'     => THEME_CUSTOMIZER_PREFIX . 'people',
					'mime_type'   => 'image'
				)
			)
		);

	}

	// Degrees
	if ( post_type_exists( 'degree' ) ) {

		$wp_customize->add_setting(
			'degree_header_image'
		);

		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				'degree_header_image',
				array(
					'label'       => 'Default Header Image (-sm+)',
					'description' => 'Default header image at the -sm breakpoint and up for single degree templates. Recommended dimensions: 1600px x 400px',
					'section'     => THEME_CUSTOMIZER_PREFIX . 'degrees',
					'mime_type'   => 'image'
				)
			)
		);

		$wp_customize->add_setting(
			'degree_header_image_xs'
		);

		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				'degree_header_image_xs',
				array(
					'label'       => 'Default Header Image (-xs)',
					'description' => 'Default header image at the -xs breakpoint for single degree templates. Recommended dimensions: 575px x 575px',
					'section'     => THEME_CUSTOMIZER_PREFIX . 'degrees',
					'mime_type'   => 'image'
				)
			)
		);

	}
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
 * Kill attachment pages, author pages, daily archive pages, and feeds.
 *
 * http://betterwp.net/wordpress-tips/disable-some-wordpress-pages/
 **/
function kill_unused_templates() {
	global $wp_query, $post;

	if ( is_author() || is_attachment() || is_day() || is_feed() ) {
		wp_redirect( home_url() );
		exit();
	}
}

add_action( 'template_redirect', 'kill_unused_templates' );

