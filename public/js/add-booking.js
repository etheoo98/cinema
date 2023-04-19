$(document).ready(function () {

    $(function() {
        $('main').on('submit', '#add-booking-form', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let action = 'add-booking';
            formData.append('action', action);

            $.ajax({
                type: 'POST',
                data: formData,
                contentType: false,
                processData:false,
                success: function(response) {
                    console.log('Server Response:', response);

                    let responseObject = JSON.parse(response);

                    if (responseObject.status === true || responseObject.message === 'You already have a ticket for this movie.') {
                        window.location.replace('/cinema/bookings');
                    }

                    if (responseObject.status === false && responseObject.message === 'Attempted to add booking while not signed-in.') {
                        window.location.replace('/cinema/sign-in');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        });
    });

});