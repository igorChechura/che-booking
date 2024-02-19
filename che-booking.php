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

require_once CHE_BOOKING_PLUGIN_DIR . 'includes/meta-box-class/class-che-booking-meta-boxes.php';
if (is_admin()) {
	$prefix = 'ba_';
	/* 
   * configure your meta box
   */
	$config = array(
		'id'             => 'demo_meta_box',          // meta box id, unique per meta box
		'title'          => 'Simple Meta Box fields',          // meta box title
		'pages'          => array('post', 'page'),      // post types, accept custom post types as well, default is array('post'); optional
		'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
		'priority'       => 'high',            // order of meta box: high (default), low; optional
		'fields'         => array(),            // list of meta fields (can be added by field arrays)
		'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
		'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
	);


	/*
   * Initiate your meta box
   */
	$my_meta =  new AT_Meta_Box($config);

	/*
   * Add fields to your meta box
   */

	//text field
	$my_meta->addText($prefix . 'text_field_id', array('name' => 'My Text '));
	//textarea field
	$my_meta->addTextarea($prefix . 'textarea_field_id', array('name' => 'My Textarea '));
	//checkbox field
	$my_meta->addCheckbox($prefix . 'checkbox_field_id', array('name' => 'My Checkbox '));
	//select field
	$my_meta->addSelect($prefix . 'select_field_id', array('selectkey1' => 'Select Value1', 'selectkey2' => 'Select Value2'), array('name' => 'My select ', 'std' => array('selectkey2')));
	//radio field
	$my_meta->addRadio($prefix . 'radio_field_id', array('radiokey1' => 'Radio Value1', 'radiokey2' => 'Radio Value2'), array('name' => 'My Radio Filed', 'std' => array('radionkey2')));
	//Image field
	$my_meta->addImage($prefix . 'image_field_id', array('name' => 'My Image '));
	//file upload field
	$my_meta->addFile($prefix . 'file_field_id', array('name' => 'My File'));
	//file upload field with type limitation
	$my_meta->addFile($prefix . 'file_pdf_field_id', array('name' => 'My File limited to PDF Only', 'ext' => 'pdf', 'mime_type' => 'application/pdf'));
	/*
   * Don't Forget to Close up the meta box Declaration 
   */
	//Finish Meta Box Declaration 
	$my_meta->Finish();

	/**
	 * Create a second metabox
	 */
	/* 
   * configure your meta box
   */
	$config2 = array(
		'id'             => 'demo_meta_box2',          // meta box id, unique per meta box
		'title'          => 'Advanced Meta Box fields',          // meta box title
		'pages'          => array('post', 'page'),      // post types, accept custom post types as well, default is array('post'); optional
		'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
		'priority'       => 'high',            // order of meta box: high (default), low; optional
		'fields'         => array(),            // list of meta fields (can be added by field arrays)
		'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
		'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
	);


	/*
   * Initiate your 2nd meta box
   */
	$my_meta2 =  new AT_Meta_Box($config2);

	/*
   * Add fields to your 2nd meta box
   */
	//add checkboxes list 
	$my_meta2->addCheckboxList($prefix . 'CheckboxList_field_id', array('checkboxkey1' => 'checkbox Value1', 'checkboxkey2' => 'checkbox Value2'), array('name' => 'My checkbox list ', 'std' => array('checkboxkey2')));
	//date field
	$my_meta2->addDate($prefix . 'date_field_id', array('name' => 'My Date '));
	//Time field
	$my_meta2->addTime($prefix . 'time_field_id', array('name' => 'My Time '));
	//Color field
	$my_meta2->addColor($prefix . 'color_field_id', array('name' => 'My Color '));
	//wysiwyg field
	$my_meta2->addWysiwyg($prefix . 'wysiwyg_field_id', array('name' => 'My wysiwyg Editor '));
	//taxonomy field
	$my_meta2->addTaxonomy($prefix . 'taxonomy_field_id', array('taxonomy' => 'category'), array('name' => 'My Taxonomy '));
	//posts field
	$my_meta2->addPosts($prefix . 'posts_field_id', array('post_type' => 'post'), array('name' => 'My Posts '));
	//add Code editor field
	$my_meta2->addCode($prefix . 'code_field_id', array(
		'name'   => 'Code editor Field',
		'syntax' => 'php',
		'theme'  => 'light'
	));

	/*
   * To Create a reapeater Block first create an array of fields
   * use the same functions as above but add true as a last param
   */
	$repeater_fields[] = $my_meta2->addText($prefix . 're_text_field_id', array('name' => 'My Text '), true);
	$repeater_fields[] = $my_meta2->addTextarea($prefix . 're_textarea_field_id', array('name' => 'My Textarea '), true);
	$repeater_fields[] = $my_meta2->addCheckbox($prefix . 're_checkbox_field_id', array('name' => 'My Checkbox '), true);
	$repeater_fields[] = $my_meta2->addImage($prefix . 'image_field_id', array('name' => 'My Image '), true);
	/*
   * Then just add the fields to the repeater block
   */
	//repeater block
	$my_meta2->addRepeaterBlock($prefix . 're_', array(
		'inline'   => true,
		'name'     => 'This is a Repeater Block',
		'fields'   => $repeater_fields,
		'sortable' => true
	));

	/*
   * To Create a conditinal Block first create an array of fields
   * use the same functions as above but add true as a last param (like the repater block)
   */
	$Conditinal_fields[] = $my_meta2->addText($prefix . 'con_text_field_id', array('name' => 'My Text '), true);
	$Conditinal_fields[] = $my_meta2->addTextarea($prefix . 'con_textarea_field_id', array('name' => 'My Textarea '), true);
	$Conditinal_fields[] = $my_meta2->addCheckbox($prefix . 'con_checkbox_field_id', array('name' => 'My Checkbox '), true);
	$Conditinal_fields[] = $my_meta2->addColor($prefix . 'con_color_field_id', array('name' => 'My color '), true);

	/*
   * Then just add the fields to the repeater block
   */
	//repeater block
	$my_meta2->addCondition(
		'conditinal_fields',
		array(
			'name'   => __('Enable conditinal fields? ', 'mmb'),
			'desc'   => __('<small>Turn ON if you want to enable the <strong>conditinal fields</strong>.</small>', 'mmb'),
			'fields' => $Conditinal_fields,
			'std'    => false
		)
	);

	/*
   * Don't Forget to Close up the meta box Declaration 
   */
	//Finish Meta Box Declaration 
	$my_meta2->Finish();


	$prefix = "_groupped_";
	$config3 = array(
		'id'             => 'demo_meta_box3',          // meta box id, unique per meta box
		'title'          => 'Groupped Meta Box fields',          // meta box title
		'pages'          => array('post', 'page'),      // post types, accept custom post types as well, default is array('post'); optional
		'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
		'priority'       => 'low',            // order of meta box: high (default), low; optional
		'fields'         => array(),            // list of meta fields (can be added by field arrays)
		'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
		'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
	);


	/*
   * Initiate your 3rd meta box
   */
	$my_meta3 =  new AT_Meta_Box($config3);
	//first field of the group has 'group' => 'start' and last field has 'group' => 'end'

	//text field
	$my_meta3->addText($prefix . 'text_field_id', array('name' => 'My Text ', 'group' => 'start'));
	//textarea field
	$my_meta3->addTextarea($prefix . 'textarea_field_id', array('name' => 'My Textarea '));
	//checkbox field
	$my_meta3->addCheckbox($prefix . 'checkbox_field_id', array('name' => 'My Checkbox '));
	//select field
	$my_meta3->addSelect($prefix . 'select_field_id', array('selectkey1' => 'Select Value1', 'selectkey2' => 'Select Value2'), array('name' => 'My select ', 'std' => array('selectkey2')));
	//radio field
	$my_meta3->addRadio($prefix . 'radio_field_id', array('radiokey1' => 'Radio Value1', 'radiokey2' => 'Radio Value2'), array('name' => 'My Radio Filed', 'std' => array('radionkey2'), 'group' => 'end'));

	/*
   * Don't Forget to Close up the meta box Declaration 
   */
	//Finish Meta Box Declaration 
	$my_meta3->Finish();
}

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
		$size = get_post_meta($post->ID, 'chebooking_size', true);

		wp_nonce_field('chebookingnoncefields', '_chebooking');

		echo '
			<table class="form-table">
				<tbody>
					<tr>
						<th><laberl for="chebooking_price">' . esc_html__('Room Price', 'chebooking') . '</laberl></th>
						<td><input type="text" id="chebooking_price" name="chebooking_price" value="' . esc_attr($price) . '"></td>
					</tr>
					<tr>
						<th><laberl for="chebooking_size">' . esc_html__('Room Size', 'chebooking') . '</laberl></th>
						<td><input type="text" id="chebooking_size" name="chebooking_size" value="' . esc_attr($size) . '"></td>
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

		if (is_null($_POST['chebooking_size'])) {
			delete_post_meta($post_id, 'chebooking_size');
		} else {
			update_post_meta($post_id, 'chebooking_size', sanitize_text_field($_POST['chebooking_size']));
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
