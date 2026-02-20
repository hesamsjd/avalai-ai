<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_avalai_chat_request', 'avalai_handle_chat_request');
add_action('wp_ajax_nopriv_avalai_chat_request', 'avalai_handle_chat_request');

function avalai_handle_chat_request()
{
    check_ajax_referer('avalai_chat', 'nonce');

    $prompt = isset($_POST['prompt']) ? sanitize_text_field($_POST['prompt']) : '';

    if (empty($prompt)) {
        wp_send_json_error('ورودی نامعتبر است.');
    }

    $api_key = get_option('avalai_api_key');

    if (empty($api_key)) {
        wp_send_json_error('کلید API تنظیم نشده است.');
    }

    $response = avalai_send_prompt_to_api($prompt, $api_key);

    if (is_wp_error($response)) {
        wp_send_json_error($response->get_error_message());
    }

    /**
     * در صورت تمایل می‌توانیم مکالمه را ذخیره کنیم
     * avalai_save_conversation($prompt, $response, get_current_user_id());
     */

    wp_send_json_success([
        'message' => $response
    ]);
}