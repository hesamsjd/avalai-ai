<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ثبت صفحه تنظیمات در منوی ادمین
 */
function avalai_register_admin_menu()
{
    add_menu_page(
        __('AvalAI Settings', 'avalai-ai'),
        __('AvalAI', 'avalai-ai'),
        'manage_options',
        'avalai-ai-settings',
        'avalai_render_settings_page',
        'dashicons-art',
        65
    );
}
add_action('admin_menu', 'avalai_register_admin_menu');

/**
 * ثبت تنظیمات
 */
function avalai_register_settings()
{
    register_setting('avalai_ai_settings', 'avalai_api_key');

    add_settings_section(
        'avalai_ai_main_section',
        __('تنظیمات اتصال', 'avalai-ai'),
        function () {
            echo '<p>لطفاً کلید API خود را وارد کنید.</p>';
        },
        'avalai-ai-settings'
    );

    add_settings_field(
        'avalai_api_key_field',
        __('کلید API', 'avalai-ai'),
        'avalai_render_api_key_field',
        'avalai-ai-settings',
        'avalai_ai_main_section'
    );
}
add_action('admin_init', 'avalai_register_settings');

/**
 * فیلد کلید API
 */
function avalai_render_api_key_field()
{
    $api_key = esc_attr(get_option('avalai_api_key'));

    echo '<input type="text" name="avalai_api_key" value="' . $api_key . '" class="regular-text" />';
}

/**
 * صفحه تنظیمات
 */
function avalai_render_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('AvalAI Settings', 'avalai-ai'); ?></h1>

        <form method="post" action="options.php">
            <?php
            settings_fields('avalai_ai_settings');
            do_settings_sections('avalai-ai-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}