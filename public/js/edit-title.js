$(function() {
    $('#page-content').on('submit', '#edit-title-form', function(e) {
        e.preventDefault();
        if ($(this).valid()) {

            let formData = new FormData(this);
            let action = $(this).data('action');
            console.log('Action:', action);
            formData.append('action', action);

            $.ajax({
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                success: function(response) {
                    console.log('Server Response:', response);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }
    });


    $('#page-content').on('focusout', 'form input', function() {
        $(this).valid();
    });

    $('#page-content').validate();
});