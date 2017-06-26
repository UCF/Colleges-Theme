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
	<nav class="navbar navbar-toggleable-md bg-default navbar-inverse" role="navigation">
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
					'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
					'walker'          => new WP_Bootstrap_Navwalker()
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
		$title = get_field( $post->ID, 'homepage_header_title', true );
	}
	else if ( $post->post_type == 'person' ) {
		$title = 'Faculty and Research'; // TODO make this configurable
	}
	else {
		$title = get_field( $post->ID, 'page_header_title', true );
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
		$subtitle = get_field( $post->ID, 'page_header_subtitle', true );
	}

	return $subtitle;
}


/**
 * Returns the markup for page headers.
 **/
function get_header_media_markup( $post ) {
	$header_right_layout = get_field( 'page_header_right_layout', $post->ID );
	$title               = get_header_title( $post );
	$subtitle            = get_header_subtitle( $post );

	// TODO these buttons need new fields for specifying an href value
	$homepage_button_left_text   = get_field( $post->ID, 'homepage_button_left_text', true );
	$homepage_button_center_text = get_field( $post->ID, 'homepage_button_center_text', true );
	$homepage_button_right_text  = get_field( $post->ID, 'homepage_button_right_text', true );

	$videos     = get_header_videos( $post );
	$images     = get_header_images( $post );
	$video_loop = get_field( 'page_header_video_loop', $post->ID );

	ob_start();

	if ( $images || $videos ) :
		$header_height = get_field( 'page_header_height', $post->ID );
?>
		<div class="header-media <?php echo $header_height; ?> <?php echo ( $header_right_layout ) ? 'header-right-layout' : ''; ?> media-background-container mb-0 d-flex flex-column">
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
				<div class="header-content-flexfix">
					<?php if ( is_front_page() ) : ?>
						<div class="header-content-inner d-flex h-100 mt-5">
							<div class="container">
								<div class="row">
									<div class="col-md-8 offset-md-2 col-12 offset-xs-0">
										<div class="home-header-title-wrapper">
											<h2 class="h1 header-title"><?php echo $title ?></h2>
											<div class="header-button-wrapper d-md-flex">
												<?php if ( $homepage_button_left_text ) : ?>
													<button type="button" class="btn btn-outline-inverse header-button"><?php echo do_shortcode( $homepage_button_left_text ); ?></button>
												<?php endif; ?>
												<?php if ( $homepage_button_center_text ) : ?>
													<button type="button" class="btn btn-outline-inverse header-button"><?php echo do_shortcode( $homepage_button_center_text ); ?></button>
												<?php endif; ?>
												<?php if ( $homepage_button_right_text ) : ?>
													<button type="button" class="btn btn-outline-inverse header-button"><?php echo do_shortcode( $homepage_button_right_text ); ?></button>
												<?php endif; ?>
											</div>
										</div>
										<div class="chevron-wrapper h-100 d-flex align-items-end justify-content-center">
											<a href="#article-home">
												<span class="fa fa-chevron-down" aria-hidden="true"></span>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php elseif ( $header_right_layout ) : ?>
						<div class="container">
							<div class="row">
								<div class="col-md-6 offset-md-6 col-sm-7 col-sm-offset-5">
									<div class="header-right-title-wrapper">
										<h1 class="header-right-title"><?php echo $title; ?></h1>
										<p class="header-right-subtitle"><?php echo $subtitle; ?></p>
									</div>
									<!-- TODO social buttons here (via plugin) -->
								</div>
							</div>
						</div>
					<?php else : ?>
						<div class="container">
							<div class="row">
								<div class="col-12">
									<div class="header-title-wrapper">
										<h1 class="header-title"><?php echo $title; ?></h1>
										<p class="header-subtitle"><?php echo $subtitle; ?></p>
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
