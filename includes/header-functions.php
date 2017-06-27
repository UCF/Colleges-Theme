<?php
/**
 * Header Related Functions
 **/

/**
 * Returns the site name formatted for use in the site navbar.
 **/
function get_sitename_formatted() {
	$sitename = $sitename_formatted = get_bloginfo( 'name' );

	if ( strpos( $sitename, '&' ) !== false ) {
		$sitename_formatted = str_replace( '&', '&amp;<br>', $sitename );
	}
	else {
		$sitename_formatted = str_replace( 'of', 'of<br>', $sitename );
	}

	return $sitename_formatted;
}


/**
 * Get the markup for the primary site navbar.
 **/
function get_nav_markup() {
	ob_start();
?>
	<nav class="navbar navbar-toggleable-md bg-inverse-t-3 navbar-inverse" role="navigation">
		<div class="container">
			<?php if ( is_home() ): ?>
			<h1 class="navbar-brand mb-0">
				<?php echo get_sitename_formatted(); ?>
			</h1>
			<?php else: ?>
			<a href="<?php echo bloginfo( 'url' ); ?>" class="navbar-brand">
				<?php echo get_sitename_formatted(); ?>
			</a>
			<?php endif; ?>
			<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#header-menu" aria-controls="header-menu" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<?php
				wp_nav_menu( array(
					'theme_location'  => 'header-menu',
					'depth'           => 2,
					'container'       => 'div',
					'container_class' => 'collapse navbar-collapse',
					'container_id'    => 'header-menu',
					'menu_class'      => 'nav navbar-nav ml-md-auto',
					'fallback_cb'     => 'bs4Navwalker::fallback',
					'walker'          => new bs4Navwalker()
				) );
			?>
		</div>
	</nav>
<?php
	return ob_get_clean();
}


/**
 * Gets the header image for pages.
 **/
function get_header_images( $post ) {
	$retval = array(
		'header_image'    => get_field( 'page_header_image', $post->ID ),
		'header_image_xs' => get_field( 'page_header_image_xs', $post->ID )
	);
	if ( $retval['header_image'] ) {
		return $retval;
	}
	return false;
}


/**
 * Gets the header video sources for pages.
 **/
function get_header_videos( $post ) {
	$retval = array(
		'webm' => get_field( 'page_header_webm', $post->ID ),
		'mp4'  => get_field( 'page_header_mp4', $post->ID )
	);
	$retval = array_filter( $retval );
	// MP4 must be available to display video successfully cross-browser
	if ( isset( $retval['mp4'] ) ) {
		return $retval;
	}
	return false;
}


/**
 * Returns title text for use in the page header.
 **/
function get_header_title( $post ) {
	$title = '';

	if ( is_front_page() ) {
		$title = get_field( 'homepage_header_title', $post->ID );
	}
	else if ( $post->post_type == 'person' ) {
		$title = 'Faculty and Research'; // TODO make this configurable
	}
	else {
		$title = get_field( 'page_header_title', $post->ID );
	}

	if ( !$title ) {
		// Fall back to the post title
		$title = $post->post_title;
	}

	return $title;
}


/**
 * Returns subtitle text for use in the page header.
 **/
function get_header_subtitle( $post ) {
	$subtitle = '';

	if ( $post->post_type == 'person' ) {
		$subtitle = get_bloginfo( 'name' ); // TODO make this configurable
	}
	else {
		$subtitle = get_field( 'page_header_subtitle', $post->ID );
	}

	return $subtitle;
}


/**
 * Returns markup for a call-to-action button in the homepage header.
 **/
function get_homepage_header_cta( $post, $position ) {
	if ( $post->ID !== intval( get_option( 'page_on_front' ) ) ) { return false; }

	$btn_text = get_field( 'homepage_button_' . $position . '_text', $post->ID );
	$btn_href = get_field( 'homepage_button_' . $position . '_url', $post->ID );

	if ( !$btn_text || !$btn_href ) { return false; }

	ob_start();
?>
<a class="btn btn-outline-inverse home-header-button" href="<?php echo $btn_href; ?>"><?php echo wptexturize( $btn_text ); ?></a>
<?php
	return ob_get_clean();
}


/**
 * Returns the markup for page headers.
 **/
function get_header_media_markup( $post ) {
	$content_position = get_field( 'page_header_content_position', $post->ID );
	$title            = get_header_title( $post );
	$subtitle         = get_header_subtitle( $post );

	$content_cols = '';
	switch ( $content_position ) {
		case 'center':
			$content_cols = 'col-lg-8 offset-lg-2 text-center';
			break;
		case 'right':
			$content_cols = 'col-lg-8 offset-lg-4 text-right';
			break;
		case 'left':
		default:
			$content_cols = 'col-lg-8';
			break;
	}

	$homepage_button_left   = get_homepage_header_cta( $post, 'left' );
	$homepage_button_center = get_homepage_header_cta( $post, 'center' );
	$homepage_button_right  = get_homepage_header_cta( $post, 'right' );

	$videos     = get_header_videos( $post );
	$images     = get_header_images( $post );
	$video_loop = get_field( 'page_header_video_loop', $post->ID );

	ob_start();

	if ( $images || $videos ) :
		$header_height = get_field( 'page_header_height', $post->ID );
?>
		<div class="header-media <?php echo ( is_front_page() ) ? 'header-media-home' : ''; ?> <?php echo $header_height ?: ''; ?> media-background-container mb-0 d-flex flex-column">
			<?php
			if ( $videos ) {
				echo get_media_background_video( $videos, $video_loop );
			}
			if ( $images ) {
				$bg_image_srcs = array();
				switch ( $header_height ) {
					case 'header-media-fullscreen':
						$bg_image_src_xs = get_media_background_picture_srcs( $images['header_image_xs'], null, 'header-img' );
						$bg_image_srcs_sm = get_media_background_picture_srcs( null, $images['header_image'], 'bg-img' );
						$bg_image_srcs = array_merge( $bg_image_src_xs, $bg_image_srcs_sm );
						break;
					default:
						$bg_image_srcs = get_media_background_picture_srcs( $images['header_image_xs'], $images['header_image'], 'header-img' );
						break;
				}
				echo get_media_background_picture( $bg_image_srcs );
			}
			?>
			<div class="header-content">

			<?php if ( is_front_page() ) : ?>
				<div class="container d-flex flex-column justify-content-between">
					<div class="row">
						<div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1 col-12">
							<div class="home-header-title-wrapper bg-inverse-t-3">
								<h2 class="h1 home-header-title mb-0"><?php echo $title ?></h2>
								<?php if ( $homepage_button_left || $homepage_button_center || $homepage_button_right ): ?>
								<div class="row mt-3 mt-md-4">
									<?php if ( $homepage_button_left ) : ?>
									<div class="col-md d-flex justify-content-center align-items-center"><?php echo $homepage_button_left; ?></div>
									<?php endif; ?>

									<?php if ( $homepage_button_center ) : ?>
									<div class="col-md d-flex justify-content-center align-items-center"><?php echo $homepage_button_center; ?></div>
									<?php endif; ?>

									<?php if ( $homepage_button_right ) : ?>
									<div class="col-md d-flex justify-content-center align-items-center"><?php echo $homepage_button_right; ?></div>
									<?php endif; ?>
								</div>
								<?php endif;?>
							</div>
						</div>
					</div>
					<div class="chevron-wrapper">
						<a href="#article-home">
							<span class="fa fa-chevron-down" aria-hidden="true"></span>
						</a>
					</div>
				</div>
			<?php else : ?>
				<div class="container d-flex align-items-center">
					<div class="row no-gutters w-100">
						<div class="<?php echo $content_cols; ?>">
							<div class="header-title-wrapper">
								<h1 class="header-title"><?php echo $title; ?></h1>
								<?php if ( $subtitle ): ?>
								<p class="header-subtitle"><?php echo $subtitle; ?></p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			</div>
		</div>
<?php
	endif;
	return ob_get_clean();
}


function get_header_markup() {
	global $post;
	echo get_nav_markup( $post );
	echo get_header_media_markup( $post );
}
