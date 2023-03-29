$(document).ready(function() {
    $('nav a').click(function(e) {
        console.log("HEY");
        e.preventDefault();
        var page = $(this).data('page');
        $.ajax({
            url: '/cinema/public/scripts/AdminSanitizePath.php',
            method: 'POST',
            data: { page: page },
            success: function(sanitizedPath) {
                $('#page-content').load(sanitizedPath);
            },
            error: function() {
                $('#page-content').html('<p>An error occurred while loading the page.</p>');
            }
        });
    });
});

function onFileSelected(event, imgId) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(event) {
        const img = document.getElementById(imgId);
        img.src = event.target.result;
    };

    reader.readAsDataURL(file);
}


// UI
function handleCheckbox() {
    var checkbox = document.getElementById("nav-toggle");
    if (window.innerWidth <= 768) { // max-width is 768px
        checkbox.checked = false;
    } else {
        checkbox.checked = true;
    }
}
handleCheckbox();