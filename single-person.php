<?php get_header(); the_post(); ?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container my-5">
		<div class="row">
			<div class="col-md-4 mb-5">

				<aside class="person-contact-container">

					<div class="mb-4">
						<?php echo get_person_thumbnail( $post, 'rounded-circle' ); ?>
					</div>

					<h1 class="h5 person-title text-center mb-2">
						<?php echo get_person_name( $post ); ?>
					</h1>

					<?php if ( $job_title = get_field( 'person_jobtitle' ) ): ?>
					<div class="person-job-title text-center mb-4"><?php echo $job_title; ?></div>
					<?php endif; ?>

					<?php echo get_person_contact_btns_markup( $post ); ?>

					<?php echo get_person_dept_markup( $post ); ?>
					<?php echo get_person_office_markup( $post ); ?>
					<?php echo get_person_email_markup( $post ); ?>
					<?php echo get_person_phones_markup( $post ); ?>

				</aside>

			</div>

			<div class="col-md-8 col-lg-7 pl-md-5">

				<section class="person-content">
					<?php echo get_person_desc_heading( $post ); ?>

					<?php
					if ( $post->post_content ) {
						the_content();
					}
					else {
						echo '<p>No description available.</p>';
					}
					?>

					<?php if ( $cv_url = get_field( 'person_cv' ) ): ?>
					<p>
						<a class="btn btn-primary mt-3" href="<?php echo $cv_url; ?>">Download CV</a>
					</p>
					<?php endif; ?>
				</section>

			</div>
		</div>

		<?php echo get_person_news_publications_markup( $post ); ?>

		<?php echo get_person_videos_markup( $post ); ?>
	</div>
</article>

<?php get_footer(); ?>
