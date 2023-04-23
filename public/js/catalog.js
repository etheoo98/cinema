$(document).ready(function() {

    $(function() {

        $('#search-form').on('submit', function(e) {

            e.preventDefault();
            let action = 'search';
            const query = $('#search-input').val();
            console.log(action);
            console.log(query);

            $.ajax({
                method: 'POST',
                data: { query: query, action: action },
                dataType: 'json',
                success: function(data) {
                    console.log('Server Response:', data);
                    $('#movie-list-container').empty();
                    $.each(data.data, function(index, movie) {
                        const html = '<div class="movie-list-content">' +
                            '<img class="poster" src="/cinema/public/img/movie/poster/' + movie.poster + '" alt="Poster of ' + movie.title + '">' +
                            '<div class="movie-list-desc">' +
                            '<a href="/cinema/movie/' + movie.movie_id + '">' +
                            '<h3 class="movie-list-title">' + movie.title + '</h3>' +
                            '</a>' +
                            '<div class="movie-list-metadata">' +
                            '<ul>' +
                            '<li>' + movie.genre + '</li>' +
                            '<li>' + Math.floor(movie.length / 60) + 'h ' + movie.length % 60 + 'min</li>' +
                            '<li>From ' + movie.age_limit + ' years</li>' +
                            '</ul>' +
                            '</div>' +
                            '</div>' +
                            '</div>';
                        $('#movie-list-container').append(html);
                    });
                },

                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                }

            });
        });
    });
});

const listIcon = document.querySelector('.list-icon');
listIcon.addEventListener('click', listOrder);

function listOrder() {
    const movieListContents = document.querySelectorAll('.movie-list-content');
    movieListContents.forEach((content) => {
        content.style.width = "100%";
        content.style.height = "128px";
        content.style.padding = "0.5rem 0";
        content.style.borderBottom = "2px solid var(--secondary-gray)";
    });
}

const columnIcon = document.querySelector('.column-icon');
columnIcon.addEventListener('click', columnOrder);

function columnOrder() {
    const movieListContents = document.querySelectorAll('.movie-list-content');
    movieListContents.forEach((content) => {
        content.style.width = "50%";
        content.style.height = "auto";
        content.style.padding = "1.5rem 0";
        content.style.borderBottom = "none";
    });
}

