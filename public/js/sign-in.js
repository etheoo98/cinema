function toggleForms() {
    // Get references to the two divs
    var Sign_In = document.getElementById("sign-in");
    var Sign_Up = document.getElementById("sign-up");
    var Error_Messages = document.getElementsByClassName("error-message");

    // Hide div1 and show div2
    if (Sign_In.style.display === "none") {
        Sign_In.style.display = "flex";
        Sign_Up.style.display = "none";
    } else {
        Sign_In.style.display = "none";
        Sign_Up.style.display = "flex";
    }
    // Hide error messages
    for (var i = 0; i < Error_Messages.length; i++) {
        Error_Messages[i].style.visibility = "hidden";
    }
}

$(function() {

    $('#page-container').on('submit', 'form', function(e) {
        e.preventDefault();
        if ($(this).valid()) {

            let formData = new FormData(this);
            let action = $(this).data('action');
            console.log('Action:', action);
            formData.append('action', action);

            $.ajax({
                url: '',
                type: 'POST',
                data: formData,
                contentType: false,
                processData:false,
                success: function(response) {
                    console.log('Server Response:', response);
                    if (response.trim() !== '') {
                        let responseObject = JSON.parse(response);

                        if (responseObject.status === 'Sign-In Failed' || responseObject.status === 'Sign-Up Failed') {
                            $('.error-message').find('span').html(responseObject.message);
                            $('.error-message').css('visibility', 'unset');
                        } else {
                            // Redirect to a new page
                            window.location.replace('catalog');
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

