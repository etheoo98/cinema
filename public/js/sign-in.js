function toggleForms() {
    // Get references to the two divs
    const Sign_In = document.getElementById("sign-in");
    const Sign_Up = document.getElementById("sign-up");
    const Error_Messages = document.getElementsByClassName("error-message");

    // Hide div1 and show div2
    if (Sign_In.style.display === "none") {
        Sign_In.style.display = "flex";
        Sign_Up.style.display = "none";
    } else {
        Sign_In.style.display = "none";
        Sign_Up.style.display = "flex";
    }
    // Hide error messages
    for (let i = 0; i < Error_Messages.length; i++) {
        Error_Messages[i].style.visibility = "hidden";
    }
}

$(function() {

    $('#page-container').on('submit', 'form', function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        let action = $(this).data('action');
        console.log('Action:', action);
        formData.append('action', action);

        $.ajax({
            type: 'POST',
            data: formData,
            contentType: false,
            processData:false,
            success: function(response) {
                console.log('Server Response:', response);

                    let responseObject = JSON.parse(response);
                    if (responseObject.status === false) {
                        $('.error-message').find('span').html(responseObject.message);
                        $('.error-message').css('visibility', 'unset');
                    } else {
                        // Redirect to a new page
                        window.location.replace('catalog');
                    }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    });

});

