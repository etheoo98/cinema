// Display selected images in add-movie and edit-movie
function onFileSelected(event, imgId) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(event) {
        const img = document.getElementById(imgId);
        img.addEventListener('load', () => {
            URL.revokeObjectURL(img.src);
        });
        img.src = URL.createObjectURL(file);
    };

    reader.readAsDataURL(file);
}


// Responsive nav
function handleCheckbox() {
    var checkbox = document.getElementById("nav-toggle");
    if (window.innerWidth <= 768) { // max-width is 768px
        checkbox.checked = false;
    } else {
        checkbox.checked = true;
    }
}
handleCheckbox();
