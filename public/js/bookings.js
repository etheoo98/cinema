$(document).ready(function () {
    $('.rating-container').each(function() {
        var movieID = $(this).data('movie-id');
        var ratingContainer = $(this);

        $.ajax({
            url: 'ajax',
            type: 'POST',
            data: {
                action: 'get-rating',
                movie_id: movieID
            },
            dataType: 'json',
            success: function(response) {
                console.log('Server Response:', response);
                if (response.success) {
                    var ratingValue = response.data.rating_value;
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
                $(this).find('path').css('fill', 'yellow');
            } else {
                $(this).find('path').css('fill', 'white');
            }
        });
    }

    var dataValue = -1;
    var movieID = -1;

    $('.rating-container').each(function() {
        var container = $(this);
        var ratedIndex = -1;
        var prevRatedIndex = -1;

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
            var prevRatedIndex = container.data('prev-rated-index');

            if (prevRatedIndex != undefined && prevRatedIndex != -1) {
                for (var i= 0; i <= prevRatedIndex; i++) {
                    container.find('.star-icon:eq('+i+') path').css('fill', 'yellow');
                }
            }
        });


        container.find('.star-icon').mouseover(function () {
            resetStarColors(container);

            var currentIndex = parseInt($(this).data('index'));

            for (var i = 0; i <= currentIndex; i++) {
                container.find('.star-icon:eq(' + i + ') path').css('fill', 'yellow');
            }

            prevRatedIndex = ratedIndex;
        });

        function resetStarColors(container) {
            container.find('.star-icon path').css('fill', 'white');
        }
    });


    function saveRating() {
        $.ajax({
            url: 'ajax',
            method: 'POST',
            data: {
                action: 'rate',
                rating: dataValue,
                movie_id: movieID
            },
            success: function(response) {
                console.log('Server Response:', response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle any errors that occurred during the AJAX request
            }
        });
    }
});
