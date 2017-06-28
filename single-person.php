<?php get_header(); the_post(); ?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container my-5">
		<div class="row">
			<div class="col-md-4 mb-5">

				<aside class="person-contact-container">

					<div class="mb-4">
						<?php echo display_person_thumbnail( $post ); ?>
					</div>

					<h1 class="h5 person-title text-center mb-2">
						<?php echo get_person_name( $post ); ?>
					</h1>

					<?php if ( $job_title = get_field( 'person_jobtitle' ) ): ?>
					<div class="person-job-title text-center mb-4"><?php echo $job_title; ?></div>
					<?php endif; ?>

					<?php echo display_person_contact_btns( $post ); ?>

					<?php echo display_person_contact_info( $post ); // TODO dept's ?>

				</aside>

			</div>

			<div class="col-md-8 col-lg-7 pl-md-5">

				<section class="person-content">
					<h2 class="person-subheading">Biography</h2>
					<?php if ( $post->post_content ) {
						the_content();
					}
					else {
						echo 'No biography available.';
					}
					?>

					<?php if ( $cv_url = get_field( 'person_cv_url' ) ): ?>
					<a class="btn btn-primary mt-3" href="<?php echo $cv_url; ?>">Download CV</a>
					<?php endif; ?>
				</section>

			</div>
		</div>

		<?php if ( $news = get_person_news( $post ) || $research = get_person_research( $post ) ): // TODO ?>
		<div class="row">
			<?php if ( $news ): ?>
			<div class="col-md">
				<h2 class="person-subheading">In The News</h2>
				<?php echo display_person_publications( $news ); // TODO ?>
			</div>
			<?php endif; ?>

			<?php if ( $research ): ?>
			<div class="col-md">
				<h2 class="person-subheading">Research and Publications</h2>
				<?php echo display_person_publications( $research ); // TODO ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php echo display_person_videos( $post ); // TODO ?>
	</div>
</article>

<?php get_footer(); ?>
