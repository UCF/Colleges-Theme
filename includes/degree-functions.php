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
 * TODO - actually return a description from search service data
 *
 * Returns markup for a degree's description from the academic catalog.
 * If a post excerpt is available, it will be returned instead.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Degree post object
 * @return Mixed | description HTML or void
 **/
function get_degree_desc_markup( $post ) {
	if ( !$post->post_type == 'degree' ) { return; }

	$desc = has_excerpt( $post->ID ) ? get_the_excerpt( $post->ID ) : get_post_meta( 'degree_description', $post->ID, true ); // TODO field name for imported degree data?

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
 * TODO - actually determine the program type based on search service degree
 * data.  This function assumes all undergraduate degrees have the term
 * "Undergraduate Programs".
 *
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

	if ( is_array( $program_types ) && in_array( 'Undergraduate Programs', $program_types ) ) {
		return true;
	}

	return false;
}


/**
 * TODO - actually determine the program type based on search service degree
 * data.  This function assumes all graduate degrees have the term
 * "Graduate Programs".
 *
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

	if ( is_array( $program_types ) && in_array( 'Graduate Programs', $program_types ) ) {
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
 * TODO
 * Returns the catalog URL for a given degree.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Degree post object
 * @return Mixed | url string or void
 **/
function get_degree_catalog_url( $post ) {
	if ( !$post->post_type == 'degree' ) { return; }

	$apply_url = 'http://catalog.ucf.edu';  // TODO

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
