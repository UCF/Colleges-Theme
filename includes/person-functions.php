<?php

/**
 * Displays a person's thumbnail image.
 *
 * TODO display fallback thumbnail based on customizer option
 *
 * @param $post object | Person post object
 **/
function display_person_thumbnail( $post ) {
	if ( !$post->post_type == 'person' ) { return; }

	if ( $thumbnail = get_the_post_thumbnail_url( $post ) ):
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
 * @param $post object | Person post object
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
 * @param $post object | Person post object
 **/
function display_person_contact_btns( $post ) {
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
 * @param $post object | Person post object
 **/
function display_person_contact_info( $post ) {
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
		<div class="col-xl-4 col-md-12 col-sm-4 people-label">
			Office
		</div>
		<div class="col-xl-8 col-md-12 col-sm-8 people-attr">
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
		<div class="col-xl-4 col-md-12 col-sm-4 people-label">
			E-mail
		</div>
		<div class="col-xl-8 col-md-12 col-sm-8 people-attr">
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
	if ( $has_phones = have_rows( 'person_phones', $post->ID ) ):
	?>
	<div class="row">
		<div class="col-xl-4 col-md-12 col-sm-4 people-label">
			Phone
		</div>
		<div class="col-xl-8 col-md-12 col-sm-8 people-attr">
			<?php
			while ( $has_phones ): the_row();
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
 * @param $post object | Person post object
 **/
function get_person_news( $post ) {
	return;
}


/**
 * TODO
 * Returns research publications related to a person.
 *
 * @param $post object | Person post object
 **/
function get_person_research( $post ) {
	return;
}


/**
 * TODO
 * Returns markup for one or more publications. For use in single-person.php
 *
 * @param $posts array | array of Publication objects
 **/
function display_person_publications( $posts ) {
	return;
}


/**
 * TODO
 * @param $post object | Person post object
 **/
function display_person_videos( $post ) {
	return;
}
