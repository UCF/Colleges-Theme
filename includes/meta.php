<?php
/**
 * Includes functions that handle registration/enqueuing of meta tags, styles,
 * and scripts in the document head and footer.
 **/

/**
 * Enqueue front-end css and js.
 **/
function enqueue_frontend_assets() {
	$theme         = wp_get_theme( 'Colleges-Theme' );
	$theme_version = ( $theme instanceof WP_Theme ) ? $theme->get( 'Version' ) : false;
	$style_deps    = array();

	// Register Cloud.Typography CSS Key
	if ( $fontkey = get_theme_mod( 'cloud_typography_key' ) ) {
		wp_enqueue_style( 'webfont', $fontkey, null, null );
		$style_deps[] = 'webfont';
	}

	// Register main theme stylesheet
	wp_enqueue_style( 'style', COLLEGES_THEME_CSS_URL . '/style.min.css', $style_deps, $theme_version );

	// Deregister jquery and re-register newer version in the document head.
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', null, null, false );
	wp_enqueue_script( 'jquery' );

	// Add other header scripts
	wp_register_script( 'typeaheadjs', 'https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.0.1/typeahead.bundle.min.js', array( 'jquery' ), null, false );
	wp_register_script( 'handlebars', 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.6/handlebars.min.js', null, null, false );

	// Add footer scripts
	wp_enqueue_script( 'ucf-header', '//universityheader.ucf.edu/bar/js/university-header.js?use-1200-breakpoint=1', null, null, true );
	wp_enqueue_script( 'tether', 'https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.7/js/tether.min.js', null, null, true );
	wp_enqueue_script( 'script', COLLEGES_THEME_JS_URL . '/script.min.js', array( 'jquery', 'tether' ), $theme_version, true );

	// Add localized script variables to the document
	$site_url = parse_url( get_site_url() );
	wp_localize_script( 'script', 'UCFCOLLEGE', array(
		'domain' => $site_url['host']
	) );
}

add_action( 'wp_enqueue_scripts', 'enqueue_frontend_assets' );


/**
 * Meta tags to insert into the document head.
 **/
function add_meta_tags() {
?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php
$gw_verify = get_theme_mod( 'gw_verify' );
if ( $gw_verify ):
?>
<meta name="google-site-verification" content="<?php echo htmlentities( $gw_verify ); ?>">
<?php endif; ?>
<?php
}

add_action( 'wp_head', 'add_meta_tags', 1 );


/**
 * Removed unneeded meta tags generated by WordPress.
 * Some of these may already be handled by security plugins.
 **/
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
add_filter( 'emoji_svg_url', '__return_false' );


/**
 * Adds ID attribute to UCF Header script.
 **/
function add_id_to_ucfhb( $url ) {
	if (
		( false !== strpos($url, 'bar/js/university-header.js' ) )
		|| ( false !== strpos( $url, 'bar/js/university-header-full.js' ) )
	) {
      remove_filter( 'clean_url', 'add_id_to_ucfhb', 10, 3 );
      return "$url' id='ucfhb-script";
    }
    return $url;
}

add_filter( 'clean_url', 'add_id_to_ucfhb', 10, 1 );


/**
 * Adds Google Analytics script to the document head.  Note that, if a Google
 * Tag Manager ID is provided in the customizer, this hook will have no effect.
 **/
function add_google_analytics() {
	$ga_account  = get_theme_mod( 'ga_account' );
	$ga4_account = get_theme_mod( 'ga4_account' );
	$gtm_id      = get_theme_mod( 'gtm_id' );
	if ( $ga4_account && !$gtm_id ) {
		add_ga4_analytics( $ga4_account );
	} else if ( $ga_account && !$gtm_id ) {
		add_classic_analytics( $ga_account );
	}
}

/**
 * Adds the classic (UA) analytics snippet into the head.
 *
 * @author Jim Barnes
 * @since v0.8.0
 * @param  string $ga_account The Google Analytics property
 * @return void
 */
function add_classic_analytics( $ga_account ) {
?>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', '<?php echo $ga_account; ?>', 'auto');
		ga('send', 'pageview');
	</script>
<?php
}

/**
 * Adds the GA4 code snippet.
 *
 * @author Jim Barnes
 * @since v0.8.0
 * @param  string $ga_account The Google Analytics version 4 property
 * @return void
 */
function add_ga4_analytics( $ga_account ) {
?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ga_account; ?>"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', '<?php echo $ga_account; ?>');
	</script>
<?php
}

add_action( 'wp_head', 'add_google_analytics' );


/**
 * Prints the Google Tag Manager script tag in the document head if a GTM ID is
 * set in the customizer.
 **/
function google_tag_manager() {
	$gtm_id = get_theme_mod( 'gtm_id' );
	if ( $gtm_id ) :
?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo $gtm_id; ?>');</script>
<!-- End Google Tag Manager -->
<?php
	endif;
}

add_action( 'wp_head', 'google_tag_manager', 3 );


/**
 * Prints the Google Tag Manager noscript snippet using the GTM ID in Theme Options.
 **/
function google_tag_manager_noscript() {
	$gtm_id = get_theme_mod( 'gtm_id' );
	if ( $gtm_id ) :
?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo $gtm_id; ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php
	endif;
}

add_action( 'after_body_open', 'google_tag_manager_noscript', 0 );


/**
 * Sets a default favicon if a site icon is not already set.
 */
function add_favicon_default() {
	if ( !has_site_icon() ):
?>
<link rel="shortcut icon" href="<?php echo COLLEGES_THEME_URL . '/favicon.ico'; ?>" />
<?php
	endif;
}

add_filter( 'wp_head', 'add_favicon_default' );


/**
 * Modify settings for supported plugins to prevent duplicate registration and
 * enqueuing of assets.
 **/

function colleges_post_list_js_deps( $deps ) {
	return array( 'jquery', 'typeaheadjs', 'handlebars' );
}

if ( defined( 'UCF_POST_LIST__PLUGIN_FILE' ) ) {
	$post_list_plugin_data = get_plugin_data( UCF_POST_LIST__PLUGIN_FILE, false, false );

	if (
		isset( $post_list_plugin_data['Version'] )
		&& version_compare( $post_list_plugin_data['Version'], '2.0.7', '<' )
	) {
		add_filter( 'ucf_post_list_js_deps', 'colleges_post_list_js_deps', 10, 1 );
		add_filter( 'option_ucf_post_list_include_js_libs', '__return_false' );
	} else {
		add_filter( 'option_ucf_post_list_include_js_libs', '__return_true' );
	}
}

if ( defined( 'UCF_DEGREE_SEARCH__PLUGIN_FILE' ) ) {
	$degree_search_plugin_data = get_plugin_data( UCF_DEGREE_SEARCH__PLUGIN_FILE, false, false );
	$post_list_plugin_data = array();
	if ( defined( 'UCF_POST_LIST__PLUGIN_FILE' ) ) {
		$post_list_plugin_data = get_plugin_data( UCF_POST_LIST__PLUGIN_FILE, false, false );
	}

	if (
		isset( $degree_search_plugin_data['Version'] )
		&& version_compare( $degree_search_plugin_data['Version'], '0.7.7', '<' )
		&& isset( $post_list_plugin_data['Version'] )
		&& version_compare( $post_list_plugin_data['Version'], '2.0.7', '<' )
	) {
		add_filter( 'option_ucf_degree_search_include_typeahead', '__return_false' );
	} else {
		add_filter( 'option_ucf_degree_search_include_typeahead', '__return_true' );
	}
}
