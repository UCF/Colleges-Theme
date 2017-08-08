<?php
include_once 'includes/utilities.php';
include_once 'includes/config.php';
include_once 'includes/meta.php';
include_once 'includes/wp-bs-navwalker.php';

include_once 'includes/header-functions.php';
include_once 'includes/person-functions.php';
include_once 'includes/degree-functions.php';


/**
 * Returns an array of src's for a media background <picture>'s <source>s by
 * breakpoint.
 *
 * $img_size_prefix is expected to be a prefix for a set of registered image
 * sizes, which has dimensions defined for each of Athena's responsive
 * breakpoints.  For example, if given a prefix 'bg-img', it is expected that
 * bg-img, bg-img-sm, bg-img-md, bg-img-lg, and bg-img-xl are valid registered
 * image sizes.
 *
 * @param int $attachment_xs_id Attachment ID for the image to be used at the -xs breakpoint
 * @param int $attachment_sm_id Attachment ID for the image to be used at the -sm breakpoint and up
 * @param string $img_size_prefix Prefix for a set of image sizes
 * @return array
 **/
function get_media_background_picture_srcs( $attachment_xs_id, $attachment_sm_id, $img_size_prefix ) {
	$bg_images = array();

	if ( $attachment_sm_id ) {
		$bg_images = array_merge(
			$bg_images,
			array(
				'xl' => get_attachment_src_by_size( $attachment_sm_id, $img_size_prefix . '-xl' ),
				'lg' => get_attachment_src_by_size( $attachment_sm_id, $img_size_prefix . '-lg' ),
				'md' => get_attachment_src_by_size( $attachment_sm_id, $img_size_prefix . '-md' ),
				'sm' => get_attachment_src_by_size( $attachment_sm_id, $img_size_prefix . '-sm' )
			)
		);

		// Try to get a fallback -xs image if needed
		if ( !$attachment_xs_id ) {
			$bg_images = array_merge(
				$bg_images,
				array( 'xs' => get_attachment_src_by_size( $attachment_sm_id, $img_size_prefix ) )
			);
		}

		// Remove duplicate image sizes, in case an old image isn't pre-cropped
		$bg_images = array_unique( $bg_images );

		// Use the largest-available image as the fallback <img>
		$bg_images['fallback'] = reset( $bg_images );
	}
	if ( $attachment_xs_id ) {
		$bg_images = array_merge(
			$bg_images,
			array( 'xs' => get_attachment_src_by_size( $attachment_xs_id, $img_size_prefix ) )
		);
	}

	// Strip out false-y values (in case an attachment failed to return somewhere)
	$bg_images = array_filter( $bg_images );

	return $bg_images;
}


/**
 * Returns markup for a media background <picture>, given an array of media
 * background picture <source> src's from get_media_background_picture_srcs().
 *
 * @param array $srcs Array of image urls that correspond to <source> src vals
 * @return string
 **/
function get_media_background_picture( $srcs ) {
	ob_start();

	if ( isset( $srcs['fallback'] ) ) :
?>
	<?php
	// Define classes for the <picture> element
	$picture_classes = 'media-background-picture ';
	if ( !isset( $srcs['xs'] ) ) {
		// Hide the <picture> element at -xs breakpoint when no mobile image
		// is available
		$picture_classes .= 'hidden-xs-down';
	}
	?>
	<picture class="<?php echo $picture_classes; ?>">
		<?php if ( isset( $srcs['xl'] ) ) : ?>
		<source class="media-background object-fit-cover" srcset="<?php echo $srcs['xl']; ?>" media="(min-width: 1200px)">
		<?php endif; ?>

		<?php if ( isset( $srcs['lg'] ) ) : ?>
		<source class="media-background object-fit-cover" srcset="<?php echo $srcs['lg']; ?>" media="(min-width: 992px)">
		<?php endif; ?>

		<?php if ( isset( $srcs['md'] ) ) : ?>
		<source class="media-background object-fit-cover" srcset="<?php echo $srcs['md']; ?>" media="(min-width: 768px)">
		<?php endif; ?>

		<?php if ( isset( $srcs['sm'] ) ) : ?>
		<source class="media-background object-fit-cover" srcset="<?php echo $srcs['sm']; ?>" media="(min-width: 576px)">
		<?php endif; ?>

		<?php if ( isset( $srcs['xs'] ) ) : ?>
		<source class="media-background object-fit-cover" srcset="<?php echo $srcs['xs']; ?>" media="(max-width: 575px)">
		<?php endif; ?>

		<img class="media-background object-fit-cover" src="<?php echo $srcs['fallback']; ?>" alt="">
	</picture>
<?php
	endif;

	return ob_get_clean();
}


/**
 * Returns markup for a media background <video> element.
 *
 * $videos is expected to be an array whose keys correspond to supported
 * <source> filetypes; e.g. $videos = array( 'webm' => '...', 'mp4' => '...' ).
 * Values should be video urls.
 *
 * Note: we never display autoplay videos at the -xs breakpoint.
 *
 * @param array $videos Array of video urls that correspond to <source> src vals
 * @return string
 **/
function get_media_background_video( $videos, $loop=false ) {
	ob_start();
?>
	<video class="hidden-xs-down media-background media-background-video object-fit-cover" autoplay muted <?php if ( $loop ) { ?>loop<?php } ?>>
		<?php if ( isset( $videos['webm'] ) ) : ?>
		<source src="<?php echo $videos['webm']; ?>" type="video/webm">
		<?php endif; ?>

		<?php if ( isset( $videos['mp4'] ) ) : ?>
		<source src="<?php echo $videos['mp4']; ?>" type="video/mp4">
		<?php endif; ?>
	</video>
	<button class="media-background-video-toggle btn play-enabled hidden-xs-up" type="button" data-toggle="button" aria-pressed="false" aria-label="Play or pause background videos">
		<span class="fa fa-pause media-background-video-pause" aria-hidden="true"></span>
		<span class="fa fa-play media-background-video-play" aria-hidden="true"></span>
	</button>
<?php
	return ob_get_clean();
}


/**
 * Display college address information
 **/
function get_contact_address_markup() {
	$address = get_theme_mod( 'organization_address' );
	if ( !empty( $address ) ) {
		ob_start();
	?>
	<address class="address">
		<?php echo wptexturize( nl2br( $address ) ); ?>
	</address>
	<?php
		return ob_get_clean();
	}
	return;
}


/**
 * Add custom layouts for the UCF Post List shortcode
 **/

function colleges_post_list_layouts( $layouts ) {
	return array_merge( $layouts, array(
		'people' => 'People Layout',
		'degree_block' => 'Degree Block Layout'
	) );
}

add_filter( 'ucf_post_list_get_layouts', 'colleges_post_list_layouts', 10, 1 );


function colleges_post_list_search( $content, $posts, $atts ) {
	if ( ! is_array( $posts ) && $posts !== false ) { $posts = array( $posts ); }

	ob_start();

	if ( $atts['layout'] === 'people' ):
	?>
		<?php if ( $posts ): ?>
		<div class="row mb-4">
			<div class="col-md-10 offset-md-1">
				<div class="ucf-post-search-form" id="post-list-search-<?php echo $atts['list_id']; ?>" data-id="post-list-<?php echo $atts['list_id']; ?>">
					<label class="sr-only"><?php echo $atts['search_placeholder']; ?></label>
					<input class="typeahead" type="text" placeholder="<?php echo $atts['search_placeholder']; ?>">
				</div>
			</div>
		</div>
		<?php endif; ?>
	<?php
	else:
		echo $content;
	endif;

	return ob_get_clean();
}

add_filter( 'ucf_post_list_search', 'colleges_post_list_search', 10, 3 );
