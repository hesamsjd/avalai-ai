<?php
/**
 * Plugin Name: AvalAI Chat
 * Plugin URI:  https://github.com/hesamsjd/avalai-ai
 * Description: افزونه اتصال به AvalAI برای ایجاد فرم چت هوش مصنوعی در وردپرس.
 * Version:     1.0.0
 * Author:      AvalAI
 * Author URI:  https://avalai.ir
 * Text Domain: avalai-ai
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * تعریف ثابت‌ها
 */
define('AVALAI_AI_PATH', plugin_dir_path(__FILE__));
define('AVALAI_AI_URL', plugin_dir_url(__FILE__));

/**
 * لود فایل‌های مورد نیاز
 */
require_once AVALAI_AI_PATH . 'includes/helpers.php';
require_once AVALAI_AI_PATH . 'includes/database.php';
require_once AVALAI_AI_PATH . 'includes/api.php';
require_once AVALAI_AI_PATH . 'includes/ajax-handlers.php';
require_once AVALAI_AI_PATH . 'includes/admin-settings.php';

/**
 * فعال‌سازی پلاگین
 */
function avalai_ai_activate()
{
    avalai_create_tables();
}
register_activation_hook(__FILE__, 'avalai_ai_activate');

/**
 * ثبت اسکریپت‌ها و استایل‌ها
 */
function avalai_enqueue_assets()
{
    wp_enqueue_style(
        'avalai-frontend',
        AVALAI_AI_URL . 'assets/css/frontend.css',
        [],
        '1.0.0'
    );

    wp_enqueue_script(
        'avalai-frontend',
        AVALAI_AI_URL . 'assets/js/frontend.js',
        ['jquery'],
        '1.0.0',
        true
    );

    wp_localize_script('avalai-frontend', 'AvalAIChat', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('avalai_chat'),
    ]);
}
add_action('wp_enqueue_scripts', 'avalai_enqueue_assets');

/**
 * شورت‌کد فرم چت
 */
function avalai_chat_shortcode()
{
    ob_start();
    $template = AVALAI_AI_PATH . 'templates/chat-form.php';

    if (file_exists($template)) {
        include $template;
    } else {
        echo '<p>قالب فرم چت پیدا نشد.</p>';
    }

    return ob_get_clean();
}
add_shortcode('avalai-chat', 'avalai_chat_shortcode');