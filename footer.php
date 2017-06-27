		</main>
		<footer class="bg-inverse py-5">
			<div class="container">
				<div class="row">
					<div class="col-lg-4">
						<section class="primary-footer-section-left">
							<h2 class="h5 text-primary mb-2"><?php echo get_sitename_formatted(); ?></h2>
							<?php echo display_contact_address(); ?>
							<!-- TODO social buttons (via plugin) -->
						</section>
					</div>
					<div class="col-lg-4">
						<section class="primary-footer-section-center"><?php dynamic_sidebar( 'footer-col-1' ); ?></section>
					</div>
					<div class="col-lg-4">
						<section class="primary-footer-section-right"><?php dynamic_sidebar( 'footer-col-2' ); ?></section>
					</div>
				</div>
			</div>
		</footer>
		<?php wp_footer(); ?>
	</body>
</html>
