<?php

/**
 * Displays a person's thumbnail image.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | thumbnail HTML or void
 **/
function get_person_thumbnail( $post ) {
	if ( !$post->post_type == 'person' ) { return; }

	$thumbnail = get_the_post_thumbnail_url( $post ) ?: get_theme_mod_or_default( 'person_thumbnail' );

	if ( $thumbnail ):
?>
	<div class="media-background-container person-photo rounded-circle mx-auto">
		<img src="<?php echo $thumbnail; ?>" alt="<?php $post->post_title; ?>" title="<?php $post->post_title; ?>" class="media-background object-fit-cover">
	</div>
<?php
	endif;
	return ob_get_clean();
}


/**
 * Returns a person's name with title prefix and suffix applied.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | person's formatted name or void
 **/
function get_person_name( $post ) {
	if ( !$post->post_type == 'person' ) { return; }

	$prefix = get_field( 'person_title_prefix', $post->ID ) ?: '';
	$suffix = get_field( 'person_title_suffix', $post->ID ) ?: '';

	if ( $prefix ) {
		$prefix = trim( $prefix ) . ' ';
	}

	if ( $suffix && substr( $suffix, 0, 1 ) !== ',' ) {
		$suffix = ' ' . trim( $suffix );
	}

	return wptexturize( $prefix . $post->post_title . $suffix );
}


/**
 * Displays contact buttons for a person. For use on single-person.php
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | Grid and contact btn HTML or void
 **/
function get_person_contact_btns_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	$email      = get_field( 'person_email', $post->ID );
	$has_phones = have_rows( 'person_phones', $post->ID );
	$phones     = get_field( 'person_phones', $post->ID );

	ob_start();

	if ( $email || $phones ):
?>
	<div class="row mt-3 mb-5">
		<?php if ( $email ): ?>
		<div class="col-md offset-md-0 col-8 offset-2 my-1">
			<a href="mailto:<?php echo $email; ?>" class="btn btn-primary btn-block">Email</a>
		</div>
		<?php endif; ?>

		<?php if ( $has_phones ): ?>
		<div class="col-md offset-md-0 col-8 offset-2 my-1">
			<a href="tel:<?php echo preg_replace( "/\D/", '', $phones[0] ); ?>" class="btn btn-primary btn-block">Phone</a>
		</div>
		<?php endif; ?>
	</div>
<?php
	endif;
	return ob_get_clean();
}


/**
 * Display's a person's contact information in a condensed table-like format.
 * For use on single-person.php
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | Grid and contact info HTML or void
 **/
function get_person_contact_info_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	ob_start();
?>
	<?php
	// TODO departments
	?>

	<?php
	// START room
	if ( $room = get_field( 'person_room', $post->ID ) ):
	?>
	<div class="row">
		<div class="col-xl-4 col-md-12 col-sm-4 person-label">
			Office
		</div>
		<div class="col-xl-8 col-md-12 col-sm-8 person-attr">
			<?php if ( $room_url = get_field( 'person_room_url', $post->ID ) ): ?>
			<a href="<?php echo $room_url; ?>">
				<?php echo $room; ?>
			</a>
			<?php else: ?>
			<span>
				<?php echo $room; ?>
			</span>
			<?php endif; ?>
		</div>
	</div>
	<hr class="my-2">
	<?php
	endif;
	// END room
	?>

	<?php
	// START email
	if ( $email = get_field( 'person_email', $post->ID ) ):
	?>
	<div class="row">
		<div class="col-xl-4 col-md-12 col-sm-4 person-label">
			E-mail
		</div>
		<div class="col-xl-8 col-md-12 col-sm-8 person-attr">
			<a href="mailto:<?php echo $email; ?>" class="person-email">
				<?php echo $email; ?>
			</a>
		</div>
	</div>
	<hr class="my-2">
	<?php
	endif;
	// END email
	?>

	<?php
	// START Phones
	if ( have_rows( 'person_phones', $post->ID ) ):
	?>
	<div class="row">
		<div class="col-xl-4 col-md-12 col-sm-4 person-label">
			Phone
		</div>
		<div class="col-xl-8 col-md-12 col-sm-8 person-attr">
			<?php
			while ( have_rows( 'person_phones', $post->ID ) ): the_row();
				$phone = get_sub_field( 'phone' );
			?>
				<?php if ( $phone ): ?>
				<a href="tel:<?php echo preg_replace( "/\D/", '', $phone ); ?>" class="person-tel">
					<?php echo $phone; ?>
				</a>
				<?php endif; ?>
			<?php endwhile; ?>
		</div>
	</div>
	<hr class="my-2">
	<?php
	endif;
	// END phones
	?>
<?php
	return ob_get_clean();
}


/**
 * TODO
 * Returns news publications related to a person.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return array | Array of Publication post objects
 **/
function get_person_news( $post ) {
	return;
}


/**
 * TODO
 * Returns research publications related to a person.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return array | Array of Publication post objects
 **/
function get_person_research( $post ) {
	return;
}


/**
 * Returns a styled unordered list of publications. For use in
 * single-person.php
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $posts array | array of Publication post objects
 * @return string | publication list HTML
 **/
function get_person_publication_list_markup( $posts ) {
	ob_start();
?>
<?php
	return ob_get_clean();
}


/**
 * Displays News and Research/Publications for a person. For use in
 * single-person.php
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | Grid and person's publication list HTML or void
 **/
function get_person_publications_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	$news = get_person_news( $post ); // TODO
	$research = get_person_research( $post ); // TODO

	ob_start();

	if ( $news || $research ):
?>
	<div class="row">
		<?php if ( $news ): ?>
		<div class="col-md">
			<h2 class="person-subheading mt-5">In The News</h2>
			<?php echo get_person_publication_list_markup( $news ); // TODO ?>
		</div>
		<?php endif; ?>

		<?php if ( $research ): ?>
		<div class="col-md">
			<h2 class="person-subheading mt-5">Research and Publications</h2>
			<?php echo get_person_publication_list_markup( $research ); // TODO ?>
		</div>
		<?php endif; ?>
	</div>
<?php
	endif;
	return ob_get_clean();
}


/**
 * Displays videos and media assigned to a person. For use in single-person.php
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | Grid and person's video list HTML or void
 **/
function get_person_videos_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	ob_start();

	if ( have_rows( 'person_media', $post->ID ) ):
?>
	<h2 class="person-subheading mt-5">Videos and Media</h2>
	<div class="row">
		<?php
		while ( have_rows( 'person_media', $post->ID ) ): the_row();
			$video_title  = get_sub_field( 'title' );
			$video_embed  = get_sub_field( 'link' );
			$video_source = get_sub_field( 'source' );
		?>
		<div class="col-md-6 my-3">
			<?php if ( $video_embed ): ?>
			<div class="embed-responsive embed-responsive-16by9 mb-3">
				<?php echo $video_embed; ?>
			</div>
			<?php endif; ?>

			<?php if ( $video_title ): ?>
			<h3 class="h5 text-uppercase mb-1"><?php echo $video_title; ?></h3>
			<?php endif; ?>

			<?php if ( $video_source ): ?>
			<p><?php echo $video_source; ?></p>
			<?php endif; ?>
		</div>
		<?php endwhile; ?>
	</div>
<?php
	endif;
	return ob_get_clean();
}
