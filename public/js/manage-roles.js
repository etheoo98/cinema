$(function() {
    $('#page-content').on('submit', 'form', function(e) {
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
                    if (response.trim() !== '') {
                        let responseObject = JSON.parse(response);

                        if (responseObject.status === 'Failed') {
                            $('.error-message').find('span').html(responseObject.message);
                            $('.error-message').css('visibility', 'unset');
                        } else if (responseObject.status === 'Success' && responseObject.message === 'User successfully promoted to Admin') {
                            let username = responseObject.username;
                            let adminSection = $('.section-header:contains("Current Admins")').parent();
                            adminSection.append(', <a style="color: var(--secondary-red)" href="/cinema/users/' + responseObject.user_id + '">' + username + '</a>')
                        } else if (responseObject.status === 'Success' && responseObject.message === 'Admin successfully demoted to user') {
                            let username = responseObject.username;
                            let adminSection = $('.section-header:contains("Current Admins")').parent();
                            adminSection.find('a:contains(' + username + ')').remove();
                        }
                    }
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
