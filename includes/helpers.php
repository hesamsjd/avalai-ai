<?php

if (!defined('ABSPATH')) {
    exit;
}

function avalai_get_conversations($limit = 20)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'avalai_conversations';

    return $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name ORDER BY created_at DESC LIMIT %d",
            $limit
        )
    );
}

function avalai_save_conversation($prompt, $response, $user_id = null)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'avalai_conversations';

    $wpdb->insert(
        $table_name,
        [
            'user_id'  => $user_id,
            'prompt'   => $prompt,
            'response' => $response,
        ],
        [
            '%d',
            '%s',
            '%s',
        ]
    );
}