$(document).ready(function () {

    /*
    This function requests the user rating for each movie that exists on the page and sets the colors of the stars
    according to the server response.
    */
    $('.rating-container').each(function() {
        const movieID = $(this).data('movie-id');
        const ratingContainer = $(this);

        $.ajax({
            type: 'POST',
            data: {
                action: 'get-rating',
                movie_id: movieID
            },
            dataType: 'json',
            success: function(response) {
                console.log('Server Response:', response);

                if (response.success) {
                    const ratingValue = response.data.rating_value;
                    ratingContainer.attr('data-rating-value', ratingValue);
                    setStarsColor(ratingContainer, ratingValue);
                    ratingContainer.data('prev-rated-index', ratingValue - 1);
                }
            },
            error: function() {
                console.log('Unable to fetch user rating for movie ' + movieID);
            }
        });
    });

    function setStarsColor(ratingContainer, ratingValue) {
        ratingContainer.find('.star-icon').each(function(index) {
            if (index < ratingValue) {
                $(this).find('path').css('fill', '#FFD700');
            } else {
                $(this).find('path').css('fill', '#FFFFFF');
            }
        });
    }

    let dataValue = -1;
    let movieID = -1;

    $('.rating-container').each(function() {
        const container = $(this);
        let ratedIndex = -1;
        let prevRatedIndex = -1;

        container.find('.star-icon').on('click', function () {
            ratedIndex = parseInt($(this).data('index'));
            dataValue = container.find('.star-icon[data-index="' + ratedIndex + '"]').data('value');
            movieID = container.data('movie-id');
            container.data('prev-rated-index', ratedIndex);
            saveRating();
        });

        container.find('.star-icon').mouseleave(function () {
            resetStarColors(container);

            // Get the previously rated index
            const prevRatedIndex = container.data('prev-rated-index');

            if (prevRatedIndex !== undefined && prevRatedIndex !== -1) {
                for (let i= 0; i <= prevRatedIndex; i++) {
                    container.find('.star-icon:eq('+i+') path').css('fill', '#FFD700');
                }
            }
        });


        container.find('.star-icon').mouseover(function () {
            resetStarColors(container);

            const currentIndex = parseInt($(this).data('index'));

            for (let i = 0; i <= currentIndex; i++) {
                container.find('.star-icon:eq(' + i + ') path').css('fill', '#FFD700');
            }

            prevRatedIndex = ratedIndex;
        });

        function resetStarColors(container) {
            container.find('.star-icon path').css('fill', '#FFFFFF');
        }
    });


    function saveRating() {
        $.ajax({
            method: 'POST',
            data: {
                action: 'rate',
                rating: dataValue,
                movie_id: movieID
            },
            success: function(response) {
                console.log('Server Response:', response);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    }
});

$(function() {
    $('main').on('submit', '.remove-booking-form', function(e) {
        e.preventDefault();

        let movieID = $(this).find('input[name="remove"]').val(); // get the value of the remove input field
        let formData = new FormData(this);
        let action = 'remove-booking'
        let $booking = $('#booking-' + movieID); // select the booking element to remove

        formData.append('action', action);

        $.ajax({
            type: 'POST',
            data: formData,
            contentType: false,
            processData:false,
            success: function(response) {
                console.log('Server Response:', response);

                let responseObject = JSON.parse(response);

                if (responseObject.status === true) {
                    $booking.remove(); // remove the parent div
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    });

});