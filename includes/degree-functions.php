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
	<div class="card card-faded">
		<div class="card-block">
			<dl class="row mb-0">
				<?php if ( $program_types ): ?>
				<dt class="col-lg-4">Degree</dt>
				<dd class="col-lg-8">
					<ul class="list-unstyled mb-0">
						<?php foreach ( $program_types as $program_type ): ?>
						<li><?php echo UCF_Degree_Program_Types_Common::get_name_or_alias( $program_type ); ?></li>
						<?php endforeach; ?>
					</ul>
				</dd>
				<?php endif; ?>

				<?php if ( $depts ): ?>
				<dt class="col-lg-4">Department<?php if ( count( $depts ) > 1 ) { ?>s<?php } ?></dt>
				<dd class="col-lg-8">
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
 * Returns markup for a degree's description from the academic catalog.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @param $post object | Degree post object
 * @return Mixed | description HTML or void
 **/
function get_degree_desc_markup( $post ) {
	if ( !$post->post_type == 'degree' ) { return; }

	ob_start();
	// if (  ):
?>
<?php
	// endif;
	return ob_get_clean();
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

	ob_start();
?>
<?php
	return ob_get_clean();
}
