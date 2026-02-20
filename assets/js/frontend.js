(function ($) {
    $(function () {
        const form = $('.avalai-chat-form');

        if (!form.length) {
            return;
        }

        form.on('submit', function (e) {
            e.preventDefault();

            const formEl = $(this);
            const messageField = formEl.find('textarea[name="prompt"]');
            const submitButton = formEl.find('button[type="submit"]');
            const responseContainer = formEl
                .closest('.avalai-chat-widget')
                .find('.avalai-chat-response');

            const prompt = messageField.val().trim();

            if (!prompt) {
                responseContainer.text('لطفاً پیام خود را وارد کنید.');
                return;
            }

            submitButton.prop('disabled', true).text('در حال ارسال...');

            $.ajax({
                url: AvalAIChat.ajax_url,
                type: 'POST',
                data: {
                    action: 'avalai_chat_request',
                    nonce: AvalAIChat.nonce,
                    prompt
                },
                success: function (res) {
                    if (res.success) {
                        responseContainer.text(res.data.message || 'پاسخی دریافت نشد.');
                    } else {
                        responseContainer.text(res.data || 'خطایی رخ داد.');
                    }
                },
                error: function () {
                    responseContainer.text('اتصال برقرار نشد، لطفاً دوباره تلاش کنید.');
                },
                complete: function () {
                    submitButton.prop('disabled', false).text('ارسال پیام');
                }
            });
        });
    });
})(jQuery);