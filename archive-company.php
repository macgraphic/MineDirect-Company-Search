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
							<?php echo mine_company_sort_options(); ?>
							<hr />
							<?php echo mineral_generate_filters('minerals'); ?>
						</div>
					</div>
				<?php } ?>

				<div class="gb-grid-column mainContent companyArchLoopWrap">
					<div class="gb-container allCompanies">

						<?php
						if ( have_posts() ) :
							while ( have_posts() ) :
								the_post();
								$listtypes = get_the_terms( get_the_ID(), 'listing_type' );
								$minerals = get_the_terms( get_the_ID(), 'minerals' );
								?>

								<div class="companySingleWrap cards company type-company <?php if ( ! empty( $listtypes ) ) : ?><?php foreach ( $listtypes as $listtype ) : ?><?php echo sanitize_html_class( $listtype->slug ); ?><?php endforeach; ?><?php endif; ?> status-publish hentry <?php if ( ! empty( $minerals ) ) : ?><?php foreach ( $minerals as $mineral ) : ?> <?php echo sanitize_html_class( $mineral->taxonomy ) . '-' . sanitize_html_class( $mineral->slug ) . ' '; ?><?php endforeach; ?><?php endif; ?>">
									<?php $compimg = get_field( 'company_image' ); ?>
									<div class="compSingleImg" style="background-image: url(<?php echo esc_url( $compimg['url'] ); ?>);"></div>
									<div class="companySingleInner">
										<div class="mineralsRow">
											<?php if ( ! empty( $minerals ) ) : ?>
												<?php foreach ( $minerals as $mineral ) : ?>
													<span class="gb-button dynamic-term-class gb-button-text post-term-item"><?php echo $mineral->name; ?></span>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
										<div class="gb-headline gb-headline-text">
											<h2>
												<a href="<?php the_permalink(); ?>">
													<?php the_title(); ?>
												</a>
											</h2>
										</div>
										<div class="gb-container displayDateUpdated">
											<div class="wp-block-group has-base-3-color has-text-color is-nowrap is-layout-flex wp-container-3">
												<?php
												$date = get_field( 'latest_update' );
												if ( $date ) :
													?>
													<div class="gb-container">
														<p class="has-base-3-color has-text-color">Latest Update: </p>
													</div>
													<div class="gb-container">
														<?php $unixtimestamp = strtotime( get_field( 'latest_update' ) ); ?>
														<p class="gb-headline gb-headline-text"><?php echo date_i18n( "F jS Y", $unixtimestamp ); ?></p>
													</div>
												<?php endif; ?>
											</div>
										</div>
										<div class="btnContainer gb-container">
											<a class="gb-button gb-button-text" href="<?php the_permalink(); ?>">
												View Company
											</a>
										</div>
									</div>
								</div>

								<?php
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
