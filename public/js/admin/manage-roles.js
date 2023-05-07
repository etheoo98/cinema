$(function() {
    $('#page-content').on('submit', 'form', function(e) {
        e.preventDefault();

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

                    if (responseObject.status === true) {
                        $('.alert-icon path').attr('d', 'M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z');
                        $('.alert-box').find('span').html(responseObject.message);
                        $('.alert-icon path').css('fill', 'green');
                        $('.alert-box').css('visibility', 'unset');

                        if (responseObject.status === true && responseObject.message === 'User successfully promoted to Admin') {
                            let username = responseObject.username;
                            let adminSection = $('.section-header:contains("Current Admins")').parent();
                            adminSection.append(', <a style="color: var(--secondary-red)" href="/cinema/users/' + responseObject.user_id + '">' + username + '</a>')
                        } else if (responseObject.status === true && responseObject.message === 'Admin successfully demoted to user') {
                            let username = responseObject.username;
                            let adminSection = $('.section-header:contains("Current Admins")').parent();
                            adminSection.find('a:contains(' + username + ')').remove();
                        }
                    }

                    if (responseObject.status === false) {
                        $('.alert-icon path').attr('d', 'M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z');
                        $('.alert-box').find('span').html(responseObject.message);
                        $('.alert-icon path').css('fill', 'orange');
                        $('.alert-box').css('visibility', 'unset');
                    }

                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    });

});
