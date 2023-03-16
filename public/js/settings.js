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