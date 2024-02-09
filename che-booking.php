<?php
/*
Plugin Name: Che Booking
Description: Booking plugin.
Author: Igor Chechura
Version: 1.0
*/
if (!function_exists('add_action')) {
	exit;
}

define('CHE_BOOKING_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Load templates
if (!class_exists('Gamajo_Template_Loader')) {
	require CHE_BOOKING_PLUGIN_DIR . 'includes/class-gamajo-template-loader.php';
}

require CHE_BOOKING_PLUGIN_DIR . 'includes/class-che-booking-template-loader.php';

class CheBooking
{
	public function register()
	{
		// register post type
		add_action('init', [$this, 'custom_post_type']);

		// enqueue admin
		add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);

		// enqueue front
		add_action('wp_enqueue_scripts', [$this, 'enqueue_front']);

		// load template
		add_filter('template_include', [$this, 'room_template']);

		// admin menu
		add_action('admin_menu', [$this, 'add_admin_menu']);

		// add links to pugin page
		add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'add_plugin_setting_link']);

		// init settings
		add_action('admin_init', [$this, 'settings_init']);

		add_action('admin_menu', [$this, 'add_meta_box_for_room']);

		add_action('save_post', [$this, 'save_metadata'], 10, 2);
	}

	// metabox
	public function add_meta_box_for_room()
	{
		add_meta_box(
			'che_booking_rooms',
			'Room Settings',
			[$this, 'meta_box_html'],
			'room',
			'normal',
			'default'
		);
	}

	public function meta_box_html($post)
	{
		$price = get_post_meta($post->ID, 'chebooking_price', true);

		wp_nonce_field('chebookingnoncefields', '_chebooking');

		echo '
			<table class="form-table">
				<tbody>
					<tr>
						<th><laberl for="chebooking_price">' . esc_html__('Room Price', 'chebooking') . '</laberl></th>
						<td><input type="text" id="chebooking_price" name="chebooking_price" value="' . esc_attr($price) . '"></td>
					</tr>
				</tbody>
			</table>
		';
	}

	public function save_metadata($post_id, $post)
	{
		if (!isset($_POST['_chebooking']) || !wp_verify_nonce($_POST['_chebooking'], 'chebookingnoncefields')) {
			return $post_id;
		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		if ($post->post_type != 'room') {
			return $post_id;
		}

		$post_type = get_post_type_object($post->post_type);
		if (!current_user_can($post_type->cap->edit_post, $post_id)) {
			return $post_id;
		}

		if (is_null($_POST['chebooking_price'])) {
			delete_post_meta($post_id, 'chebooking_price');
		} else {
			update_post_meta($post_id, 'chebooking_price', sanitize_text_field($_POST['chebooking_price']));
		}

		return $post_id;
	}

	// activation
	static function activation()
	{
		flush_rewrite_rules();
	}

	static function deactivation()
	{
		flush_rewrite_rules();
	}

	// plugin settings
	public function add_plugin_setting_link($links)
	{
		$custom_link = '<a href="admin.php?page=chebooking">' . esc_html__('Settings', 'chebooking') . '</a>';
		array_push($links, $custom_link);
		return $links;
	}

	public function add_admin_menu()
	{
		add_menu_page(
			esc_html__('CheBooking Settings', 'chebooking'),
			esc_html__('CheBooking', 'chebooking'),
			'manage_options',
			'chebooking',
			[$this, 'admin_page'],
			'dashicons-admin-multisite',
			100
		);
	}

	public function admin_page()
	{
		require_once plugin_dir_path(__FILE__) . 'admin/admin.php';
	}

	public function settings_init()
	{
		register_setting('booking_settings', 'booking_settings_options');

		add_settings_section('booking_settings_section', esc_html__('Settings', 'chebooking'), [$this, 'settings_section_html'], 'chebooking');

		add_settings_field('posts_per_page', esc_html__('Posts per page', 'chebooking'), [$this, 'posts_per_page_html'], 'chebooking', 'booking_settings_section');
		add_settings_field('title_for_rooms_page', esc_html__('Archive page title', 'chebooking'), [$this, 'title_for_rooms_page_html'], 'chebooking', 'booking_settings_section');
	}

	public function settings_section_html()
	{
		echo esc_html__('Main settings', 'chebooking');
	}

	public function posts_per_page_html()
	{
		$options = get_option('booking_settings_options');

		$value = isset($options['posts_per_page']) ? $options['posts_per_page'] : '';

		echo '<input type="text" name="booking_settings_options[posts_per_page]" value="' . $value . '">';
	}

	public function title_for_rooms_page_html()
	{
		$options = get_option('booking_settings_options');

		$value = isset($options['title_for_rooms_page']) ? $options['title_for_rooms_page'] : '';

		echo '<input type="text" name="booking_settings_options[title_for_rooms_page]" value="' . $value . '">';
	}

	// frontend
	public function room_template($template)
	{
		if (is_post_type_archive('room')) {
			$theme_files = ['archive-room.php', 'che-booking/archive-room.php'];
			$exist_in_theme = locate_template($theme_files, false);
			if ($exist_in_theme !== '') {
				return $exist_in_theme;
			} else {
				return plugin_dir_path(__FILE__) . 'templates/archive-room.php';
			}
		}
		return $template;
	}

	public function enqueue_admin()
	{
		wp_enqueue_style('cheBookingStyle', plugins_url('/assets/admin/styles.css', __FILE__));
		wp_enqueue_script('cheBookingScript', plugins_url('/assets/admin/scripts.js', __FILE__));
	}

	public function enqueue_front()
	{
		wp_enqueue_style('cheBookingStyle', plugins_url('/assets/front/styles.css', __FILE__));
		wp_enqueue_script('cheBookingScript', plugins_url('/assets/front/scripts.js', __FILE__));
	}

	// post type
	public function custom_post_type()
	{
		register_post_type('room', [
			'public' => true,
			'has_archive' => true,
			'rewrite' => [
				'slug' => 'rooms'
			],
			'label' => esc_html__('Rooms', 'chebooking'),
			'supports' => ['title', 'editor', 'thumbnail'],
		]);

		$labels = [
			'name'              => _x('Locations', 'taxonomy general name', 'chebooking'),
			'singular_name'     => _x('Location', 'taxonomy singular name', 'chebooking'),
			'search_items'      =>  __('Search Locations', 'chebooking'),
			'all_items'         => __('All Locations', 'chebooking'),
			'parent_item'       => __('Parent Location', 'chebooking'),
			'parent_item_colon' => __('Parent Location:', 'chebooking'),
			'edit_item'         => __('Edit Location', 'chebooking'),
			'update_item'       => __('Update Location', 'chebooking'),
			'add_new_item'      => __('Add New Location', 'chebooking'),
			'new_item_name'     => __('New Location Name', 'chebooking'),
			'menu_name'         => __('Location', 'chebooking'),
		];

		$args = [
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => ['slug' => 'room/location'],
		];

		register_taxonomy('location', 'room', $args);

		$labels_type = [
			'name'              => _x('Types', 'taxonomy general name', 'chebooking'),
			'singular_name'     => _x('Type', 'taxonomy singular name', 'chebooking'),
			'search_items'      =>  __('Search Types', 'chebooking'),
			'all_items'         => __('All Types', 'chebooking'),
			'parent_item'       => __('Parent Type', 'chebooking'),
			'parent_item_colon' => __('Parent Type:', 'chebooking'),
			'edit_item'         => __('Edit Type', 'chebooking'),
			'update_item'       => __('Update Type', 'chebooking'),
			'add_new_item'      => __('Add New Type', 'chebooking'),
			'new_item_name'     => __('New Type Name', 'chebooking'),
			'menu_name'         => __('Type', 'chebooking'),
		];

		$args_type = [
			'hierarchical' => true,
			'labels' => $labels_type,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => ['slug' => 'room/type'],
		];

		register_taxonomy('type', 'room', $args_type);
	}

	public function get_terms_hierarhical($tax_name, $current_term)
	{
		$taxonomy_terms = get_terms($tax_name, ['hide_empty => false', 'parent' => 0]);

		if (!empty($taxonomy_terms)) {
			foreach ($taxonomy_terms as $term) {
				if ($current_term == $term->term_id) {
					echo '<option value="' . $term->term_id . '" selected>' . $term->name . '</option>';
				} else {
					echo '<option value="' . $term->term_id . '">' . $term->name . '</option>';
				}



				$child_terms = get_terms($tax_name, ['hide_empty => false', 'parent' => $term->term_id]);

				if (!empty($child_terms)) {
					foreach ($child_terms as $child) {
						echo '<option value="' . $child->term_id . '"> - ' . $child->name . '</option>';
					}
				}
			}
		}
	}
}

if (class_exists('CheBooking')) {
	$cheBooking = new CheBooking();
	$cheBooking->register();
}

register_activation_hook(__FILE__, array($cheBooking, 'activation'));
register_deactivation_hook(__FILE__, array($cheBooking, 'deactivation'));
