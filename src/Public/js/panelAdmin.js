const selectSize = document.getElementById("selectSize");
selectSize.addEventListener("change", function () {
    const nuevoSize = this.value;
    window.location.href = `index.php?menu=PanelAdmin&page=1&size=${nuevoSize}`;
});
