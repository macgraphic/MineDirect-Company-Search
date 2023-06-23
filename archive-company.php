<?php
/**
 * The template for displaying Archive pages.
 *
 * @package GeneratePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
?>

<div <?php generate_do_attr( 'content' ); ?>>
	<main <?php generate_do_attr( 'main' ); ?>>
		<?php
		/**
		 * generate_before_main_content hook.
		 *
		 * @since 0.1
		 */
		do_action( 'generate_before_main_content' );
		if ( generate_has_default_loop() ) {
		?>
			<div class="gb-grid-wrapper main-filter-query-wrapper sticky companyArcPageWrap">
				<?php if ( is_plugin_active( 'mine-company-search/mine-search.php') ) { ?>
					<div class="gb-grid-column sidebar companyArcPageSide">
						<div class="gb-container">
							<?php echo mineral_generate_filters('minerals'); ?>
							<hr />
							<?php echo mine_company_sort_options(); ?>
						</div>

					</div>
				<?php } ?>

				<div class="gb-grid-column mainContent companyArchLoopWrap">
					<div class="gb-container allCompanies">

						<?php
						if (have_posts()) :
							while (have_posts()) :
								the_post();
								get_template_part('template-parts/content', 'company-archive');
							endwhile;
						else :
							// Nothing to see here
						endif;
						?>
					</div>
				</div>
			</div>
		<?php
		}
		/**
		 * generate_after_main_content hook.
		 *
		 * @since 0.1
		 */
		do_action( 'generate_after_main_content' );
		?>
	</main>
</div>

<?php
/**
 * generate_after_primary_content_area hook.
 *
 * @since 2.0
 */
do_action( 'generate_after_primary_content_area' );

generate_construct_sidebars();

get_footer();
