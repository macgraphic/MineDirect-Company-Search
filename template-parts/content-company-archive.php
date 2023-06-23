<?php
/**
 * Template part for displaying the loop for companies on the archive-company page.
 *
 */

?>

	<?php
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

