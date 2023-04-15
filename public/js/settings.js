// Collapsible button
const coll = document.getElementsByClassName("collapsible");
let i;

for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
        const active = document.querySelector(".collapsible.active");
        if (active && active !== this) {
            active.classList.remove("active");
            active.nextElementSibling.style.maxHeight = null;
        }
        this.classList.toggle("active");
        const content = this.nextElementSibling;
        if (content.style.maxHeight) {
            content.style.maxHeight = null;
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
        }
    });
}

// Change Email
$(function() {
    $('main').on('submit', '#update-email-form', function(e) {
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
                    if (response.trim() !== '') {
                        let responseObject = JSON.parse(response);
                        if (responseObject.status === 'Success') {
                            $('#new-email').attr('placeholder', responseObject.email);
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

// Check all in sessions
function toggle(source) {
    let checkBoxes = document.getElementsByName('checkBoxes[]');
    let i = 0, n = checkBoxes.length;
    for(; i<n; i++) {
        checkBoxes[i].checked = source.checked;
    }
}

// Terminate Session(s)
$(function() {
    $('main').on('submit', '#terminate-session-form', function(e) {
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
                    if (response.trim() !== '') {
                        let responseObject = JSON.parse(response);
                        if (responseObject.status === 'Success') {
                            $('input[type="checkbox"]:checked').each(function() {
                                $(this).parent('.grid-item').nextAll('.grid-item').slice(0, 4).remove();
                                $(this).parent('.grid-item').remove();
                            });
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