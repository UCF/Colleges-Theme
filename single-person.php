<?php get_header(); the_post(); ?>

<article class="<?php echo $post->post_status; ?> post-list-item">
	<div class="container my-5">
		<div class="row">
			<div class="col-md-4 mb-5">

				<aside class="person-contact-container">

					<?php if ( $thumbnail = get_the_post_thumbnail_url() ): ?>
					<div class="mb-4">
						<div class="media-background-container person-photo rounded-circle mx-auto">
							<img src="<?php echo $thumbnail; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="media-background object-fit-cover">
						</div>
					</div>
					<?php endif; ?>

					<h1 class="h5 person-title text-center mb-2">
						<?php echo $post->person_title_prefix; ?>
						<?php the_title(); ?><?php echo $post->person_title_suffix; ?>
					</h1>

					<?php if ( $post->person_jobtitle ): ?>
					<div class="person-job-title text-center mb-4"><?php echo $post->person_jobtitle; ?></div>
					<?php endif; ?>

					<?php if ( $post->person_email || $post->person_phones ): ?>
					<div class="row mt-3 mb-5">
						<?php if ( $post->person_email ): ?>
						<div class="col-md offset-md-0 col-8 offset-2 my-1">
							<a href="mailto:<?php echo $post->person_email; ?>" class="btn btn-primary btn-block">Email</a>
						</div>
						<?php endif; ?>

						<?php if ( $post->person_phones ): // TODO does not account for multiple phone numbers! ?>
						<div class="col-md offset-md-0 col-8 offset-2 my-1">
							<a href="tel:<?php echo preg_replace( "/\D/", '', $post->person_phones ); ?>" class="btn btn-primary btn-block">Phone</a>
						</div>
						<?php endif; ?>
					</div>
					<?php endif; ?>

					<!-- TODO departments -->

					<?php if ( $post->person_room ): ?>
					<div class="row">
						<div class="col-xl-4 col-md-12 col-sm-4 people-label">
							Office
						</div>
						<div class="col-xl-8 col-md-12 col-sm-8 people-attr">
							<?php if ( $post->person_room_url ): ?>
							<a href="<?php echo $post->person_room_url; ?>">
								<?php echo $post->person_room; ?>
							</a>
							<?php else: ?>
							<span>
								<?php echo $post->person_room; ?>
							</span>
							<?php endif; ?>
						</div>
					</div>

					<hr class="my-2">
					<?php endif; ?>

					<?php if ( $post->person_email ): ?>
					<div class="row">
						<div class="col-xl-4 col-md-12 col-sm-4 people-label">
							E-mail
						</div>
						<div class="col-xl-8 col-md-12 col-sm-8 people-attr">
							<a href="mailto:<?php echo $post->person_email; ?>" class="person-email">
								<?php echo $post->person_email; ?>
							</a>
						</div>
					</div>

					<hr class="my-2">
					<?php endif; ?>

					<?php if ( $post->person_phones ): ?>
					<div class="row">
						<div class="col-xl-4 col-md-12 col-sm-4 people-label">
							Phone
						</div>
						<div class="col-xl-8 col-md-12 col-sm-8 people-attr">
							<a href="tel:<?php echo preg_replace( "/\D/", '', $post->person_phones ); ?>" class="person-tel">
								<?php echo $post->person_phones; // TODO does not account for multiple phone numbers! ?>
							</a>
						</div>
					</div>

					<hr class="my-2">
					<?php endif; ?>

					<!-- TODO office hours -->

					<!-- TODO class schedule -->

				</aside>

			</div>

			<div class="col-md-8 col-lg-7 pl-md-5">

				<?php // START Biography ?>
				<div class="person-content">
					<h2 class="person-subheading">Biography</h2>
					<?php the_content(); ?>

					<?php if ( $post->person_cv_url ): ?>
					<a class="btn btn-primary mt-3" href="<?php echo $post->person_cv_url; ?>">Download CV</a>
					<?php endif; ?>
				</div>
				<?php // END Biography ?>

			</div>
		</div>
	</div>

	<!-- TODO publications?? -->

	<!-- TODO videos/media?? -->

</article>

<?php get_footer(); ?>
