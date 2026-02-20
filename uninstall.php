<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

$table_name = $wpdb->prefix . 'avalai_conversations';

$wpdb->query("DROP TABLE IF EXISTS $table_name");

delete_option('avalai_api_key');