<script type="text/javascript">
    jQuery(function($) {
        const $searchInput = $('#ajax-search');
        const $resultsList = $('#search-results');
        let typingTimer;
        const delay = 300;

        const searchingText = '<?php echo esc_js(__('جاري البحث...', 'saint')); ?>';
        const noResultsText = '<?php echo esc_js(__('لا توجد نتائج', 'saint')); ?>';
        const errorText = '<?php echo esc_js(__('حدث خطأ أثناء البحث', 'saint')); ?>';
        const ajaxUrl = '<?php echo admin_url("admin-ajax.php"); ?>';

        $searchInput.on('input', function () {
            clearTimeout(typingTimer);
            const searchTerm = $searchInput.val().trim();

            if (!searchTerm) {
                $resultsList.empty().removeClass('open');
                return;
            }

            $resultsList.html('<li class="loading">' + searchingText + '</li>').addClass('open');

            typingTimer = setTimeout(function () {
                $.ajax({
                    type: 'POST',
                    url: ajaxUrl,
                    dataType: 'json',
                    data: {
                        action: 'getbytext',
                        textword: searchTerm
                    },
                    success: function (response) {
                        if (response) {
                            $resultsList.html(response).addClass('open');
                        } else {
                            $resultsList.html('<li>' + noResultsText + '</li>').addClass('open');
                        }
                    },
                    error: function () {
                        $resultsList.html('<li>' + errorText + '</li>').addClass('open');
                    }
                });
            }, delay);
        });

        // لا تغلق النتائج عند التركيز
        $('.search-form').on('focusin', function() {
            if ($resultsList.children().length > 0) {
                $resultsList.addClass('open');
            }
        });

        // إغلاق النتائج عند فقدان التركيز بعد تأخير بسيط
        $('.search-form').on('focusout', function(e) {
            setTimeout(() => {
                if (!$(document.activeElement).closest('.search-form').length) {
                    $resultsList.removeClass('open').empty();
                }
            }, 200);
        });
    });
</script>
