jQuery(document).ready(function($) {
    $('#myform').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        console.log(form_data);
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: form_data + '&action=send_mail_action',
            success: function(response) {
                $('#success-message').html(response);
            }
            
        });
    });
});



