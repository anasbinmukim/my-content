<?php

get_header();

$show_default_title = get_post_meta( get_the_ID(), '_et_pb_show_title', true );

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php if (et_get_option('divi_integration_single_top') <> '' && et_get_option('divi_integrate_singletop_enable') == 'on') echo(et_get_option('divi_integration_single_top')); ?>

				<?php
					$et_pb_has_comments_module = has_shortcode( get_the_content(), 'et_pb_comments' );
					$additional_class = $et_pb_has_comments_module ? ' et_pb_no_comments_section' : '';
					$current_quote_id = get_the_ID();
				?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' . $additional_class ); ?>>
					<div class="et_post_meta_wrapper">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</div> <!-- .et_post_meta_wrapper -->
					<div class="entry-content">
					<?php

						the_content();

						//wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>
					</div> <!-- .entry-content -->
					<div class="quote_author">- <?php echo get_the_term_list( $current_quote_id, 'quoteauthor', '', '' ); ?></div>
					<div class="quote_tags">
						<?php echo get_the_term_list( $current_quote_id, 'quotetag', 'Tags: ', ' ' ); ?>
					</div>
					 <!-- .et_post_meta_wrapper -->
				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>
			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer(); ?>
