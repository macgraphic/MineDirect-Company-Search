<?php
/*
Plugin Name: MineDirect Company Search
Plugin URI: .
Description: Creates a way to search mining companies
Version: 1.0.1
Author: Mark Smallman
Author URI: https://macgraphic.co.uk
License: GPLv2
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

// function mine_custom_archive_query($query)
// {
// 	if (!is_admin() && $query->is_main_query()) {
// 		if ($query->is_post_type_archive('company')) {
// 			$sort_option = isset($_GET['company_sort']) ? $_GET['company_sort'] : 'sort_updated';

// 			switch ($sort_option) {
// 				case 'sort_updated':
// 					$query->set('meta_key', 'latest_update');
// 					$query->set('orderby', 'meta_value');
// 					$query->set('order', 'DESC');
// 					break;

// 				case 'sort_titlea':
// 					$query->set('orderby', 'title');
// 					$query->set('order', 'ASC');
// 					break;

// 				case 'sort_titlez':
// 					$query->set('orderby', 'title');
// 					$query->set('order', 'DESC');
// 					break;

// 				default:
// 					break;
// 			}
// 		}
// 	}
// }
// add_action('pre_get_posts', 'mine_custom_archive_query');

function mineral_generate_filters() {
	ob_start();
	$mineral_tax = 'minerals';
	$data_to_js = array(
		'minerals_taxonomy' => $mineral_tax,
	);
	$mineral_tax_terms = get_terms($mineral_tax, 'orderby=name&hide_empty=1');
?>


<?php 
	function mine_company_sort_options()
	{
		ob_start();
		?>
		<label for="company-sort">Sort Companies:</label>
		<select id="company-sort" name="company_sort">
			<option value="sort_updated">Latest Update (Default)</option>
			<option value="sort_titlea">Company Name A-Z</option>
			<option value="sort_titlez">Company Name Z-A</option>
		</select>
		<?php
		return ob_get_clean();
	}
?>

	<nav class="widget inner-padding widget_block widget_search mineral-menu mined-filter-menu" aria-label="<?php _e('Secondary menu'); ?>" role="navigation" itemtype="https://schema.org/SiteNavigationElement" itemscope>
		<ul class="secondary-menu">
			<li class="js-search-list-item">
				<form class="search-form vanilla" role="search" aria-label="<?php _e('Local site search'); ?>">
					<div>
						<input class='input' tabindex="2" type="search" id="searchbox" placeholder="<?php _e('Search'); ?>">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="8.26087" cy="8.26087" r="7.76087" stroke="#222222" />
							<path d="M14 14L19 19" stroke="#222222" />
						</svg>
					</div>
					<input type="hidden" tabindex="22" class="search-reset" value="<?php _e('Undo'); ?>">
				</form>
			</li>
			<span class="filter-label"><?php _e('Minerals'); ?></span>
			<li>
				<a role="link" tabindex="2" class="reset-filters" id="reset"><?php _e('Reset / Show All'); ?></a>
			</li>
			<?php
			foreach ($mineral_tax_terms as $mineral_term) {
				$kslug = $mineral_term->slug;
				$kname = $mineral_term->name;
				if (!empty($kslug)) { ?>
					<li>
						<a href="#page" tabindex="2" class="filter" role="link" id="<?php echo $kslug; ?>">
							<?php echo $kname; ?>
						</a>
					</li>
			<?php
				}
			}
			?>
		</ul>
	</nav>

<?php
	// cleanup
	return ob_get_clean();
}

// Enqueue JavaScript file for AJAX handling and the assets for the filtering
function company_search_enqueue_scripts()
{
	// Define and assign value to $minerals_taxonomy variable
	$minerals_taxonomy = 'minerals';

	wp_enqueue_style('company-search-styles', plugin_dir_url(__FILE__) . 'assets/company-search.css', false, '1.0.0.', 'all');
	wp_enqueue_script('company-search', plugin_dir_url(__FILE__) . 'assets/company-search.js', array('jquery'), '1.0', true);
	
	$php_vars = array(
		'minerals_taxonomy' => $minerals_taxonomy,
		'ajaxurl' => rest_url('../wp-json/wp/v2/'),
		'nonce' => wp_create_nonce('company_sort_nonce'), // Add nonce creation
	);
	wp_localize_script( 'company-search', 'php_vars', $php_vars );
}
add_action('wp_enqueue_scripts', 'company_search_enqueue_scripts');

// AJAX callback function to handle sorting
function company_search_ajax_callback( WP_REST_Request $request ) {

	// Verify the AJAX nonce for security
	$nonce = $request->get_header('X-WP-Nonce');
	if (!wp_verify_nonce($nonce, 'company_search_nonce')) {
		wp_send_json_error('Invalid nonce');
	}

	// Get the selected sort option from the AJAX request
	$sort_option = isset($_POST['sort_option']) ? sanitize_text_field($_POST['sort_option']) : '';

	// Perform the sorting logic here and generate the updated content
	$updated_content = ''; // Initialize the variable

	// Query the posts based on the selected sort option
	$args = array(
		'post_type' => 'company', // Replace 'company' with your actual post type slug
		'meta_key'  => 'latest_update',
		'orderby'   => 'meta_value', // Set the appropriate sorting parameters based on the sort option
		'order'     => 'DESC',
		// Add any additional query parameters as needed
	);
	$query = new WP_Query($args);

	// Load the template part file to generate the HTML markup for each post
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			ob_start();
			get_template_part('template-parts/content', 'companycard'); // Replace with the actual template part file name
			$updated_content .= ob_get_clean();
		}
		wp_reset_postdata(); // Reset the post data to restore the original query
	}

	// Return the updated content as a response
	wp_send_json_success($updated_content);
}
add_action('wp_ajax_company_search', 'company_search_ajax_callback');
add_action('wp_ajax_nopriv_company_search', 'company_search_ajax_callback');


