"use strict";
var csrf_test_name = $("#CSRF_TOKEN").val();

// Product selection start
$('body').on('change', '#country', function () {
    var country_id = $(this).val();
    $.ajax({
        url: base_url + 'web/customer/Customer_dashboard/select_city_country_id', // Fix the URL
        data: { csrf_test_name: csrf_test_name, country_id: country_id },
        type: "post",
        success: function (data) {
            $('#state').html(data);
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText); // Log any errors to the console
        }
    });
});
