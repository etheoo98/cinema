$(function() {
    $('#page-content').on('submit', '#add-movie-form', function(e) {
        e.preventDefault();
        if ($(this).valid()) {

            let formData = new FormData(this);
            let action = $(this).data('action');
            console.log('Action:', action);
            formData.append('action', action);

            $.ajax({
                url: 'ajax',
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

$(function() {
    $('#page-content').on('submit', '#loadTitle', function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            let formData = new FormData(this);
            let action = $(this).data('action');
            formData.append('action', action);
            $.ajax({
                url: 'ajax',
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    console.log('Server Response:', response);
                    let data = JSON.parse(response).data;
                    populateForm(data);
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

function populateForm(data) {
    const form = document.querySelector('#edit-title-form');
    form.querySelector('#title').value = data[0].title;
    form.querySelector('#premiere').value = data[0].premiere;
    form.querySelector('#description').value = data[0].description;
    form.querySelector('#genre').value = data[0].genre;
    form.querySelector('#length').value = data[0].length;
    form.querySelector('#language').value = data[0].language;
    form.querySelector('#age-limit').value = data[0].age_limit;

    if (data[0].subtitles === 1) {
        form.querySelector('#subtitles-yes').checked = true;
    } else {
        form.querySelector('#subtitles-no').checked = true;
    }

    if (data[0].showing === 1) {
        form.querySelector('#screening-yes').checked = true;
    } else {
        form.querySelector('#screening-no').checked = true;
    }

    const posterImg = form.querySelector('#poster-img');
    posterImg.src = '/cinema/public/img/title/poster/' + data[0].poster;

    const heroImg = form.querySelector('#hero-img');
    heroImg.src = '/cinema/public/img/title/hero/' + data[0].hero;

    const logoImg = form.querySelector('#logo-img');
    logoImg.src = '/cinema/public/img/title/logo/' + data[0].logo;
}
