$(function() {
    $('main').on('submit', '.remove-booking-form', function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            let movieID = $(this).find('input[name="remove"]').val(); // get the value of the remove input field
            let formData = new FormData(this);
            let action = $(this).data('action');
            console.log('Action:', action);
            formData.append('action', action);

            let $booking = $('#booking-' + movieID); // select the booking element to remove
            $.ajax({
                url: 'ajax',
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                success: function(response) {
                    console.log('Server Response:', response);
                    if (response.trim() !== '') {
                        let responseObject = JSON.parse(response);

                        if (responseObject.status === 'Success') {
                            $booking.remove(); // remove the parent div
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }
    });
});
