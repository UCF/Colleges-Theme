<?php
/**
 * Returns markup for a degree's meta information in a stylized box.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Degree post object
 * @return Mixed | meta info HTML or void
 **/
function get_degree_meta_markup( $post ) {
	if ( !$post->post_type == 'degree' ) { return; }

	$program_types = wp_get_post_terms( $post->ID, 'program_types' );
	$program_types = !is_wp_error( $program_types ) && !empty( $program_types ) && class_exists( 'UCF_Degree_Program_Types_Common' ) ? $program_types : false;
	$depts         = wp_get_post_terms( $post->ID, 'departments' );
	$depts         = !is_wp_error( $depts ) && !empty( $depts ) && class_exists( 'UCF_Departments_Common' ) ? $depts : false;

	ob_start();
	if ( $program_types || $depts ):
?>
	<div class="card card-faded mb-3 mb-lg-4">
		<div class="card-block">
			<dl class="row mb-0">
				<?php if ( $program_types ): ?>
				<dt class="col-sm-4">Degree</dt>
				<dd class="col-sm-8">
					<ul class="list-unstyled mb-0">
						<?php foreach ( $program_types as $program_type ): ?>
						<li><?php echo UCF_Degree_Program_Types_Common::get_name_or_alias( $program_type ); ?></li>
						<?php endforeach; ?>
					</ul>
				</dd>
				<?php endif; ?>

				<?php if ( $depts ): ?>
				<dt class="col-sm-4">Department<?php if ( count( $depts ) > 1 ) { ?>s<?php } ?></dt>
				<dd class="col-sm-8">
					<ul class="list-unstyled mb-0">
						<?php foreach ( $depts as $dept ): ?>
						<li><?php echo UCF_Departments_Common::get_website_link( $dept ); ?></li>
						<?php endforeach; ?>
					</ul>
				</dd>
				<?php endif; ?>
			</dl>
		</div>
	</div>
<?php
	endif;
	return ob_get_clean();
}


/**
 * Returns a degree's description (unformatted) from the academic catalog.
 * If a post excerpt is available, it will be returned instead.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Degree post object
 * @return Mixed | description string or void
 **/
function get_degree_desc( $post ) {
	if ( !$post->post_type == 'degree' ) { return; }

	return has_excerpt( $post->ID ) ? get_the_excerpt( $post->ID ) : get_post_meta( 'degree_description', $post->ID, true );
}


/**
 * Returns markup for a degree's description.  For use in single-degree.php
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Degree post object
 * @return Mixed | description HTML or void
 **/
function get_degree_desc_markup( $post ) {
	if ( !$post->post_type == 'degree' ) { return; }

	$desc = get_degree_desc( $post );

	ob_start();
	if ( $desc ):
?>
	<div class="lead font-slab-serif">
		<?php echo apply_filters( 'the_content', $desc ); ?>
	</div>
<?php
	endif;
	return ob_get_clean();
}


/**
 * Returns whether or not a given degree is an undergraduate degree.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Degree post object
 * @return Boolean
 **/
function is_undergraduate_degree( $post ) {
	if ( !$post->post_type == 'degree' ) { return false; }

	$program_types = wp_get_post_terms( $post->ID, 'program_types', array( 'fields' => 'names' ) );

	if (
		is_array( $program_types )
		&& (
			in_array( 'Undergraduate Program', $program_types )
			|| in_array( 'Undergraduate Degree', $program_types )
			|| in_array( 'Minor', $program_types )
			|| in_array( 'Articulated Program', $program_types )
			|| in_array( 'Accelerated Program', $program_types )
		)
	) {
		return true;
	}

	return false;
}


/**
 * Returns whether or not a given degree is a graduate degree.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Degree post object
 * @return Boolean
 **/
function is_graduate_degree( $post ) {
	if ( !$post->post_type == 'degree' ) { return false; }

	$program_types = wp_get_post_terms( $post->ID, 'program_types', array( 'fields' => 'names' ) );

	if (
		is_array( $program_types )
		&& (
			in_array( 'Graduate Program', $program_types )
			|| in_array( 'Master', $program_types )
			|| in_array( 'Doctorate', $program_types )
			|| in_array( 'Certificate', $program_types )
		)
	) {
		return true;
	}

	return false;
}


/**
 * Returns the UCF application URL for a given degree.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Degree post object
 * @return Mixed | url string or void
 **/
function get_degree_apply_url( $post ) {
	if ( !$post->post_type == 'degree' ) { return; }

	$apply_url = '';

	// Determine whether degree is undergraduate or graduate.  Note that
	// some degrees may not have an undergraduate or graduate program type.
	if ( is_undergraduate_degree( $post ) ) {
		$apply_url = get_theme_mod_or_default( 'apply_undergraduate_url' );
	}
	elseif ( is_graduate_degree( $post ) ) {
		$apply_url = get_theme_mod_or_default( 'apply_graduate_url' );
	}

	return $apply_url;
}


/**
 * Returns the catalog URL for a given degree.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Degree post object
 * @return Mixed | url string or void
 **/
function get_degree_catalog_url( $post ) {
	if ( !$post->post_type == 'degree' ) { return; }

	$apply_url = get_post_meta( $post->ID, 'degree_pdf', true ) ?: '';

	return $apply_url;
}


/**
 * Returns markup for a degree's call-to-action buttons.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Degree post object
 * @return Mixed | CTA HTML or void
 **/
function get_degree_cta_btns_markup( $post ) {
	if ( !$post->post_type == 'degree' ) { return; }

	$apply_url = get_degree_apply_url( $post );
	$catalog_url = get_degree_catalog_url( $post );

	ob_start();
?>
	<?php if ( $apply_url ): ?>
	<a class="btn btn-block btn-primary" href="<?php echo $apply_url; ?>">Apply Now</a>
	<?php endif; ?>

	<?php if ( $catalog_url ): ?>
	<a class="btn btn-block btn-primary" href="<?php echo $catalog_url; ?>">View Course Catalog</a>
	<?php endif; ?>
<?php
	return ob_get_clean();
}


/**
 * Add custom degree block list layout for UCF Post List shortcode
 **/

function colleges_post_list_display_degree_block_before( $content, $posts, $atts ) {
	ob_start();
?>
<div class="ucf-post-list colleges-post-list-degree-block">
<?php
	return ob_get_clean();
}

add_filter( 'ucf_post_list_display_degree_block_before', 'colleges_post_list_display_degree_block_before', 10, 3 );


function colleges_post_list_display_degree_block_title( $content, $posts, $atts ) {
	$formatted_title = '';

	if ( $atts['list_title'] ) {
		$formatted_title = '<h2 class="ucf-post-list-title">' . $atts['list_title'] . '</h2>';
	}

	echo $formatted_title;
}

add_filter( 'ucf_post_list_display_degree_block_title', 'colleges_post_list_display_degree_block_title', 10, 3 );


function colleges_post_list_display_degree_block( $content, $posts, $atts ) {
	if ( ! is_array( $posts ) && $posts !== false ) { $posts = array( $posts ); }
	ob_start();
?>
	<?php if ( $posts ): ?>
	<div class="card-deck">

	<?php foreach( $posts as $index=>$item ) :
		$desc = wp_trim_words( get_degree_desc( $item ), 55, '&hellip;' );

		if( $atts['posts_per_row'] > 0 && $index !== 0 && ( $index % $atts['posts_per_row'] ) === 0 ) {
			echo '</div><div class="card-deck">';
		}
	?>
	<div class="card card-faded mb-4">
		<div class="card-block py-4">
			<h3 class="card-title h5">
				<a class="text-secondary" href="<?php echo get_permalink( $item->ID ); ?>">
					<?php echo $item->post_title; ?>
				</a>
			</h3>
			<div class="card-text">
				<?php echo $desc; ?>
				<a class="d-inline-block font-italic" href="<?php echo get_permalink( $item->ID ); ?>">
					Learn More &rsaquo;
				</a>
			</div>
		</div>
	</div>
	<?php endforeach; ?>

	<?php else: ?>
	<div class="ucf-post-list-error">No results found.</div>
	<?php endif; ?>
<?php
	echo ob_get_clean();
}

add_filter( 'ucf_post_list_display_degree_block', 'colleges_post_list_display_degree_block', 10, 3 );


function colleges_post_list_display_degree_block_after( $content, $posts, $atts ) {
	ob_start();
?>
</div>
<?php
	echo ob_get_clean();
}

add_filter( 'ucf_post_list_display_degree_block_after', 'colleges_post_list_display_degree_block_after', 10, 3 );


/**
 * Modify the list of valid taxonomies for Degree posts used by the Degree
 * Search plugin.
 **/
function colleges_degree_taxonomies() {
	return array(
		'post_tag',
		'program_types',
		'departments',
		'career_paths'
	);
}

add_filter( 'ucf_degree_taxonomies', 'colleges_degree_taxonomies' );
