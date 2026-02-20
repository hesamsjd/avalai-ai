<?php

if (!defined('ABSPATH')) {
    exit;
}

function avalai_send_prompt_to_api($prompt, $conversation_history = [])
{
    $api_key = get_option('avalai_api_key');
    $model   = get_option('avalai_default_model', 'glm-5');

    if (empty($api_key)) {
        return new WP_Error('no_api_key', __('کلید API تنظیم نشده است.', 'avalai-ai'));
    }

    $messages = avalai_build_messages($prompt, $conversation_history);

    $payload = [
        'model'       => $model,
        'messages'    => $messages,
        'temperature' => 0.7,
        'max_tokens'  => 512,
    ];

    $response = wp_remote_post(
        'https://api.avalai.ir/v1/chat/completions',
        [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $api_key,
            ],
            'body'    => wp_json_encode($payload),
            'timeout' => 45,
        ]
    );

    if (is_wp_error($response)) {
        error_log('AvalAI API WP_Error: ' . print_r($response, true));

        return new WP_Error(
            'api_error',
            sprintf(
                __('پاسخی از سرور دریافت نشد. (خطا: %s)', 'avalai-ai'),
                $response->get_error_message()
            )
        );
    }

    $code = wp_remote_retrieve_response_code($response);

    if ($code !== 200) {
        $body = wp_remote_retrieve_body($response);
        error_log('AvalAI API error response: ' . $body);

        return new WP_Error(
            'api_error',
            sprintf(__('خطای API (%1$s): %2$s', 'avalai-ai'), $code, $body)
        );
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (!$data || !isset($data['choices'][0]['message']['content'])) {
        return new WP_Error('invalid_response', __('پاسخ نامعتبر از API دریافت شد.', 'avalai-ai'));
    }

    return [
        'reply' => $data['choices'][0]['message']['content'],
    ];
}

function avalai_build_messages($prompt, $conversation_history = [])
{
    $messages = [];

    if (!empty($conversation_history)) {
        foreach ($conversation_history as $entry) {
            $messages[] = [
                'role'    => $entry['role'],
                'content' => $entry['content'],
            ];
        }
    }

    $messages[] = [
        'role'    => 'user',
        'content' => $prompt,
    ];

    return $messages;
}