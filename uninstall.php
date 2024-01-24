<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Revmove posts room from db
// global $wpdb;
// $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type IN ('room');");

$rooms = get_posts([
    'post_type' => 'room',
    'numberposts' => -1
]);

foreach ($rooms as $room) {
    wp_delete_post($room->ID, true);
}
