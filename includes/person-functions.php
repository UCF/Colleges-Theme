<?php

/**
 * Displays a person's thumbnail image.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @param $css_classes str | Additional classes to add to the thumbnail wrapper
 * @return Mixed | thumbnail HTML or void
 **/
function get_person_thumbnail( $post, $css_classes='' ) {
	if ( !$post->post_type == 'person' ) { return; }

	$thumbnail = get_the_post_thumbnail_url( $post ) ?: get_theme_mod_or_default( 'person_thumbnail' );
	// Account for attachment ID being returned by get_theme_mod_or_default():
	if ( is_numeric( $thumbnail ) ) {
		$thumbnail = wp_get_attachment_url( $thumbnail );
	}

	ob_start();
	if ( $thumbnail ):
?>
	<div class="media-background-container person-photo mx-auto <?php echo $css_classes; ?>">
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
	$has_phones = have_rows( 'person_phone_numbers', $post->ID );
	$phones     = get_field( 'person_phone_numbers', $post->ID );

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
			<a href="tel:<?php echo preg_replace( "/\D/", '', $phones[0]['number'] ); ?>" class="btn btn-primary btn-block">Phone</a>
		</div>
		<?php endif; ?>
	</div>
<?php
	endif;
	return ob_get_clean();
}


/**
 * Displays a person's department(s) in a condensed table-like format.
 * For use on single-person.php
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | Grid and contact info HTML or void
 **/
function get_person_dept_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	$depts = wp_get_post_terms( $post->ID, 'departments' );
	$depts = !is_wp_error( $depts ) && !empty( $depts ) && class_exists( 'UCF_Departments_Common' ) ? $depts : false;

	ob_start();
	if ( $depts ) :
?>
	<div class="row">
		<div class="col-xl-4 col-md-12 col-sm-4 person-label">
			Department<?php if ( count( $depts ) > 1 ) { echo 's'; } ?>
		</div>
		<div class="col-xl-8 col-md-12 col-sm-8 person-attr">
			<ul class="list-unstyled mb-0">
				<?php foreach ( $depts as $dept ): ?>
				<li><?php echo UCF_Departments_Common::get_website_link( $dept ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<hr class="my-2">
<?php
	endif;
	return ob_get_clean();
}


/**
 * Display's a person's office location in a condensed table-like format.
 * For use on single-person.php
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | Grid and contact info HTML or void
 **/
function get_person_office_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	ob_start();
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
	return ob_get_clean();
}


/**
 * Display's a person's email in a condensed table-like format.
 * For use on single-person.php
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | Grid and contact info HTML or void
 **/
function get_person_email_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	ob_start();
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
	return ob_get_clean();
}


/**
 * Display's a person's phone numbers in a condensed table-like format.
 * For use on single-person.php
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return Mixed | Grid and contact info HTML or void
 **/
function get_person_phones_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	ob_start();
	if ( have_rows( 'person_phone_numbers', $post->ID ) ):
?>
	<div class="row">
		<div class="col-xl-4 col-md-12 col-sm-4 person-label">
			Phone
		</div>
		<div class="col-xl-8 col-md-12 col-sm-8 person-attr">
			<ul class="list-unstyled mb-0">
			<?php
			while ( have_rows( 'person_phone_numbers', $post->ID ) ): the_row();
				$phone = get_sub_field( 'number' );
				if ( $phone ):
			?>
				<li>
					<a href="tel:<?php echo preg_replace( "/\D/", '', $phone ); ?>" class="person-tel">
						<?php echo $phone; ?>
					</a>
				</li>
			<?php
				endif;
			endwhile;
			?>
			</ul>
		</div>
	</div>
	<hr class="my-2">
<?php
	endif;
	return ob_get_clean();
}


/**
 * Display's a person's description/biography heading.
 *
 * @author Jo Dickson
 * @since 1.0.2
 * @param $post object | Person post object
 * @return Mixed | Description heading HTML or void
 */
function get_person_desc_heading( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	$show_heading = get_field( 'person_display_desc_heading', $post->ID );
	$heading_text = trim( get_field( 'person_desc_heading', $post->ID ) );

	// Account for existing Person posts created before v1.0.2 field updates
	if ( $show_heading === null ) {
		$show_heading = true;
		$heading_text = 'Biography';
	}

	ob_start();
	if ( $show_heading && ! empty( $heading_text ) ):
?>
	<h2 class="person-subheading"><?php echo wptexturize( $heading_text ); ?></h2>
<?php
	endif;
	return ob_get_clean();
}


/**
 * Returns news publications related to a person.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return array | Array of Post objects
 **/
function get_person_news( $post, $limit=4, $start=0 ) {
	return get_posts( array(
		'post_type' => 'post',
		'posts_per_page' => $limit,
		'offset' => $start,
		'category_name' => 'faculty-news',
		'meta_query' => array(
			array(
				'key' => 'post_associated_people',
				'value' => '"' . $post->ID . '"',
				'compare' => 'LIKE'
			)
		)
	) );
}


/**
 * Returns research publications related to a person.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Person post object
 * @return array | Array of Post objects
 **/
function get_person_publications( $post, $limit=4, $start=0 ) {
	return get_posts( array(
		'post_type' => 'post',
		'posts_per_page' => $limit,
		'offset' => $start,
		'category_name' => 'publication',
		'meta_query' => array(
			array(
				'key' => 'post_associated_people',
				'value' => '"' . $post->ID . '"',
				'compare' => 'LIKE'
			)
		)
	) );
}


/**
 * Returns a styled unordered list of posts associated with a person. For use
 * in single-person.php
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $posts array | array of Post objects
 * @return string | publication list HTML
 **/
function get_person_post_list_markup( $posts ) {
	ob_start();
	if ( $posts ):
?>
	<ul class="list-unstyled">
		<?php foreach ( $posts as $post ): ?>
		<li class="mb-md-4">
			<h3 class="h5">
				<?php if ( get_post_meta( $post->ID, '_links_to', true ) || $post->post_content ): ?>
				<a href="<?php echo get_permalink( $post ); ?>">
					<?php echo wptexturize( $post->post_title ); ?>
				</a>
				<?php else: ?>
				<?php echo wptexturize( $post->post_title ); ?>
				<?php endif; ?>
			</h3>
			<?php if ( has_excerpt( $post ) ): ?>
			<div>
				<?php echo apply_filters( 'the_content', get_the_excerpt( $post ) ); ?>
			</div>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>
<?php
	endif;
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
function get_person_news_publications_markup( $post ) {
	if ( $post->post_type !== 'person' ) { return; }

	$news = get_person_news( $post );
	$pubs = get_person_publications( $post );

	ob_start();

	if ( $news || $pubs ):
?>
	<div class="row">
		<?php if ( $news ): ?>
		<div class="col-lg">
			<h2 class="person-subheading mt-5">In The News</h2>
			<?php echo get_person_post_list_markup( $news ); ?>
		</div>
		<?php endif; ?>

		<?php if ( $pubs ): ?>
		<div class="col-lg">
			<h2 class="person-subheading mt-5">Research and Publications</h2>
			<?php echo get_person_post_list_markup( $pubs ); ?>
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

	if ( have_rows( 'person_medias', $post->ID ) ):
?>
	<h2 class="person-subheading mt-5">Videos and Media</h2>
	<div class="row">
		<?php
		while ( have_rows( 'person_medias', $post->ID ) ): the_row();
			$video_title     = get_sub_field( 'title' );
			$video_embed     = get_sub_field( 'link' );
			$video_embed_url = get_sub_field( 'link', false );
			$video_source    = get_sub_field( 'source' );

			// Lazy check for whether or not WordPress returned a fallback link
			// for the embed
			$oembed_is_valid = ( substr( $video_embed, 0, 2 ) !== '<a ' && substr( $video_embed, -4 ) !== '</a>' );
		?>
		<div class="col-md-6 my-3">
			<?php if ( $video_embed && $oembed_is_valid ): ?>
			<div class="embed-responsive embed-responsive-16by9 mb-3">
				<?php echo $video_embed; ?>
			</div>
			<?php endif; ?>

			<?php if ( $video_title ): ?>
			<h3 class="h5 text-uppercase mb-1">
				<?php if ( !$oembed_is_valid && $video_embed_url ): ?>
				<a href="<?php echo $video_embed_url; ?>">
					<?php echo $video_title; ?>
				</a>
				<?php else: ?>
				<?php echo $video_title; ?>
				<?php endif; ?>
			</h3>
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


/**
 * Add custom people list layout for UCF Post List shortcode
 **/

function colleges_post_list_display_people_before( $content, $items, $atts ) {
	ob_start();
?>
<div class="ucf-post-list colleges-post-list-people">
<?php
	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_people_before', 'colleges_post_list_display_people_before', 10, 3 );


function colleges_post_list_display_people_title( $content, $items, $atts ) {
	$formatted_title = '';

	if ( $atts['list_title'] ) {
		$formatted_title = '<h2 class="ucf-post-list-title">' . $atts['list_title'] . '</h2>';
	}

	return $formatted_title;
}

add_filter( 'ucf_post_list_display_people_title', 'colleges_post_list_display_people_title', 10, 3 );


function colleges_post_list_display_people( $content, $items, $atts ) {
	if ( ! is_array( $items ) && $items !== false ) { $items = array( $items ); }
	ob_start();
?>
	<?php if ( $items ): ?>
	<ul class="list-unstyled row ucf-post-list-items">
		<?php foreach ( $items as $item ): ?>
		<li class="col-6 col-sm-4 col-md-3 col-xl-2 mt-3 mb-2 ucf-post-list-item">
			<a class="person-link" href="<?php echo get_permalink( $item->ID ); ?>">
				<?php echo get_person_thumbnail( $item ); ?>
				<h3 class="mt-2 mb-1 person-name"><?php echo get_person_name( $item ); ?></h3>
				<?php if ( $job_title = get_field( 'person_jobtitle', $item->ID ) ): ?>
				<div class="font-italic person-job-title">
					<?php echo $job_title; ?>
				</div>
				<?php endif; ?>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php else: ?>
	<div class="ucf-post-list-error mb-4">No results found.</div>
	<?php endif; ?>
<?php
	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_people', 'colleges_post_list_display_people', 10, 3 );


function colleges_post_list_display_people_after( $content, $items, $atts ) {
	ob_start();
?>
</div>
<?php
	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_people_after', 'colleges_post_list_display_people_after', 10, 3 );


/**
 * Modifies searchable values for each person in a 'people' post list search.
 **/

function colleges_search_people_localdata( $localdata, $posts, $atts ) {
	$data = json_decode( $localdata, true ) ?: array();

	if ( !empty( $data ) && $atts['layout'] === 'people' ) {
		foreach ( $data as $index => $item ) {
			if ( isset( $item['id'] ) && get_post_type( $item['id'] ) === 'person' ) {
				$person = get_post( $item['id'] );
				$name = get_person_name( $person );
				$job_title = get_field( 'person_jobtitle', $person->ID );
				$job_title = $job_title ? strip_tags( $job_title ) : false; // Fix stupid job title hackery

				// Update person datum matches
				$matches = array( $name );
				if ( $job_title ) {
					$matches[] = $job_title;
				}

				$data[$index]['matches'] = array_merge( $item['matches'], $matches );

				// Update displayKey for each person
				$display = $name;
				if ( $job_title ) {
					$display .= ' ' . $job_title;
				}

				$data[$index]['display'] = $display;

				// Add extra template data
				ob_start();
	?>
				<div class="media">
					<?php echo get_person_thumbnail( $person ); ?>
					<div class="media-body ml-4">
						<?php echo $name; ?>
						<?php if ( $job_title ): ?>
						<div class="small font-italic">
							<?php echo $job_title; ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
	<?php
				$tmpl_suggestion = ob_get_clean();
				$data[$index]['template']['suggestion'] = $tmpl_suggestion;
			}
		}
	}

	return json_encode( $data );
}

add_filter( 'ucf_post_list_search_localdata', 'colleges_search_people_localdata', 10, 3 );


/**
 * Modifies templates for the 'people' post list layout typeahead results.
 **/

function colleges_search_people_templates( $templates, $posts, $atts ) {
	if ( $atts['layout'] !== 'people' ) { return $templates; }

	ob_start();
?>
	{
		suggestion: Handlebars.compile('<div>{{{template.suggestion}}}</div>')
	}
<?php
	return ob_get_clean();
}

add_filter( 'ucf_post_list_search_templates', 'colleges_search_people_templates', 10, 3 );
