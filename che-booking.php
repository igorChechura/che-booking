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
	}

	static function activation()
	{
		flush_rewrite_rules();
	}

	static function deactivation()
	{
		flush_rewrite_rules();
	}

	public function add_admin_menu() {
		add_menu_page(
			esc_html__('CheBooking Settings', 'chebooking'),
			esc_html__('CheBooking', 'chebooking'),
			'manage_options',
			'chebooking',
			'',
			'dashicons-admin-multisite',
			10
		);
	}

	public function room_template($template) {
		if(is_post_type_archive('room')) {
			$theme_files = ['archive-room.php', 'che-booking/archive-room.php'];
			$exist_in_theme = locate_template($theme_files, false);
			if($exist_in_theme !== '') {
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
	}
}

if (class_exists('CheBooking')) {
	$cheBooking = new CheBooking();
	$cheBooking->register();
}

register_activation_hook(__FILE__, array($cheBooking, 'activation'));
register_deactivation_hook(__FILE__, array($cheBooking, 'deactivation'));
