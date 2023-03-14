function toggle(source) {
    let checkBoxes = document.getElementsByName('checkBoxes[]');
    let i = 0, n = checkBoxes.length;
    for(; i<n; i++) {
        checkBoxes[i].checked = source.checked;
    }
}