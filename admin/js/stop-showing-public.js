jQuery(function ($) {
    $('#stop_shown_enabled_switch_opt').on("click", function () {

        if ($(this).val() === "unchecked") {
            $(this).val("checked");
        } else {
            $(this).val("unchecked");
        }

        let data = $(this).val();

        $.ajax({
            type: "post",
            url: junu_show_hide_ajax_calling.ajaxurl,
            data: {
                action: "junu_images_hide_show",
                data: data,
                nonce: junu_show_hide_ajax_calling.nonce
            },
            success: function (response) {
                var url = window.location.href;
                $('body').load(url);
            }
        });
    });
});