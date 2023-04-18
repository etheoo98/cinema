$(document).ready(function() {

    $(function() {
        $('#page-content').on('submit', '#edit-movie-form', function(e) {
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

                        let responseObject = JSON.parse(response);

                        if (responseObject.status === true) {
                            const errorMessage = document.querySelector('.error-message');
                            if (errorMessage.style.visibility === 'unset') {
                                errorMessage.style.visibility = 'hidden';
                            }
                        }

                        if (responseObject.status === false) {
                            $('.error-message').find('span').html(responseObject.message);
                            $('.error-message').css('visibility', 'unset');
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

    // Find the maximum index of existing actor input elements
    let maxIndex = 1;
    $(".actor-section input[name^='actor-']").each(function() {
        const index = parseInt($(this).attr('name').replace(/^actor-(\d+)$/, '$1'));
        if (index > maxIndex) {
            maxIndex = index;
        }
    });

// Increment the index to get the new index for the new element
    let newRowNum = maxIndex + 1;

    $(".new-actor").click(function() {
        if (newRowNum <= 3) {
            const newActor = $(".actor-section .input-row").first().clone();

            newActor.find('input[type="text"], input[type="number"]').val('');

            newActor.find('label').each(function() {
                const labelFor = $(this).attr('for');
                $(this).attr('for', labelFor.replace(/\d+/, newRowNum));
            });

            newActor.find('input[type="text"], input[type="number"]').each(function() {
                const inputName = $(this).attr('name');
                const inputId = $(this).attr('id');
                $(this).attr('name', inputName.replace(/\d+/, newRowNum));
                $(this).attr('id', inputId.replace(/\d+/, newRowNum));
            });

            $("#edit-movie-form .new-actor").before(newActor);

            newRowNum++;
        }
    });

});