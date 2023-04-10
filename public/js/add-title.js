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

// Clone row and increment value of label 'for' and input 'name' in add-actor
$(document).ready(function() {
    var newRowNum = 1;

    $(".new-actor").click(function() {
        newRowNum++;

        var newActor = $(".actor-section .input-row").first().clone();

        newActor.find('input[type="text"], input[type="number"]').val('');

        newActor.find('label').each(function() {
            var labelFor = $(this).attr('for');
            $(this).attr('for', labelFor.replace(/\d+/, newRowNum));
        });

        newActor.find('input[type="text"], input[type="number"]').each(function() {
            var inputName = $(this).attr('name');
            $(this).attr('name', inputName.replace(/\d+/, newRowNum));
        });

        $("#add-movie-form .new-actor").before(newActor);
    });
});
