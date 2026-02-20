<?php defined('ABSPATH') || exit; ?>

<?php if (!empty($conversations)) : ?>
    <ul class="avalai-conversation-list">
        <?php foreach ($conversations as $item) : ?>
            <li>
                <strong>شما:</strong> <?php echo esc_html($item->prompt); ?><br>
                <strong>AvalAI:</strong> <?php echo esc_html($item->response); ?><br>
                <em><?php echo esc_html($item->created_at); ?></em>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>گفتگویی ثبت نشده است.</p>
<?php endif; ?>