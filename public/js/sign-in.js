function toggleForms() {
    // Get references to the two divs
    var Sign_In = document.getElementById("sign-in");
    var Sign_Up = document.getElementById("sign-up");

    // Hide div1 and show div2
    if (Sign_In.style.display === "none") {
        Sign_In.style.display = "flex";
        Sign_Up.style.display = "none";
    } else {
        Sign_In.style.display = "none";
        Sign_Up.style.display = "flex";
    }
}
function CatalogRedirect() {
    window.location.href = "catalog";
}
function EmptyFields() {
    document.getElementById("error-message").innerHTML = "All fields must be filled out!";
}
function InvalidCredentials() {
    document.getElementById("error-message").innerHTML = "Invalid Credentials!";
}