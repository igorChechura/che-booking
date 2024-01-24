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
	function __construct()
	{
		add_action('init', array($this, 'custom_post_type'));
	}

	static function activation()
	{
		flush_rewrite_rules();
	}

	static function deactivation()
	{
		flush_rewrite_rules();
	}

	function custom_post_type()
	{
		register_post_type('room', [
			'public' => true,
			'label' => esc_html__('Rooms', 'chebooking'),
			'supports' => ['title', 'editor', 'thumbnail'],
		]);
	}
}

if (class_exists('CheBooking')) {
	$cheBooking = new CheBooking();
}

register_activation_hook(__FILE__, array($cheBooking, 'activation'));
register_deactivation_hook(__FILE__, array($cheBooking, 'deactivation'));
