<?php
get_header(); the_post();

$cta_btns = get_degree_cta_btns_markup( $post );
$sidebar_content = get_field( 'degree_sidebar_content' );
?>

<div class="container mb-5 mt-3 mt-md-4 mt-lg-5">
	<article class="<?php echo $post->post_status; ?> post-list-item">
		<div class="row">
			<div class="col-lg-8">
				<?php echo get_degree_meta_markup( $post ); ?>

				<?php if ( $cta_btns ): ?>
				<div class="hidden-lg-up mb-4">
					<?php echo $cta_btns; ?>
				</div>
				<?php endif; ?>

				<?php echo get_degree_desc_markup( $post ); ?>
				<?php the_content(); ?>

				<?php if ( $sidebar_content ): ?>
				<div class="hidden-lg-up mt-5">
					<?php echo $sidebar_content; ?>
				</div>
				<?php endif; ?>
			</div>

			<?php if ( $cta_btns || $sidebar_content ): ?>
			<div class="col-lg-4 hidden-md-down">
				<?php echo $cta_btns; ?>
				<div class="my-5">
					<?php echo $sidebar_content; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</article>
</div>

<?php get_footer(); ?>
