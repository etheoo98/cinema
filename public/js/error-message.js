function EmptyFields() {
    document.getElementById("error-message").innerHTML = "All fields must be filled out!";
}
function InvalidEmailFormat() {
    document.getElementById("error-message").innerHTML = "Invalid Email Format!";
}
function EmailInUse() {
    document.getElementById("error-message").innerHTML = "Email is already in use!";
}
function UsernameInUse() {
    document.getElementById("error-message").innerHTML = "Username is already in use!";
}
function PasswordMatch() {
    document.getElementById("error-message").innerHTML = "Password don't match!";
}