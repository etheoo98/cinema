document.addEventListener("DOMContentLoaded", function() {

    let slideIndex = 3;
    let enableAutoSlide = true;
    let autoSlideTimeoutId = null;

    showSlides();

    function showSlides() {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        let dots = document.getElementsByClassName("dot");
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        if (enableAutoSlide) {
            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1;
            }
            autoSlideTimeoutId = setTimeout(showSlides, 6000); // Change image every 2 seconds
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex-1].style.display = "flex";
        dots[slideIndex-1].className += " active";
    }

    function currentSlide(n) {
        enableAutoSlide = false;
        clearTimeout(autoSlideTimeoutId);
        showSlides(slideIndex = n);
    }

});