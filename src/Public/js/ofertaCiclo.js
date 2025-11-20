// Cargo familias al abrir la página
fetch('/API/OfertaAjax.php?action=familias')
    .then(res => res.json())
    .then(familias => {
        const selectFamilia = document.getElementById("selectFamilia");

        selectFamilia.innerHTML = '<option value="">-- Selecciona familia --</option>';

        familias.forEach(f => {
            const option = document.createElement("option");
            option.value = f.id;
            option.textContent = f.nombre;
            selectFamilia.appendChild(option);
        });
    });


// Cuando selecciono una familiacargo los ciclos
document.getElementById("selectFamilia").addEventListener("change", function () {
    const familiaId = this.value;
    const selectCiclo = document.getElementById("selectCiclo");

    if (!familiaId) {
        selectCiclo.innerHTML = '<option value="">Selecciona una familia primero</option>';
        selectCiclo.disabled = true;
        return;
    }

    fetch(`API/OfertaAjax.php?action=ciclos&familia_id=${familiaId}`)
        .then(res => res.json())
        .then(ciclos => {
            selectCiclo.innerHTML = '<option value="">-- Selecciona ciclo --</option>';

            ciclos.forEach(c => {
                const option = document.createElement("option");
                option.value = c.id;
                option.textContent = `${c.nombre} (${c.nivel})`;
                selectCiclo.appendChild(option);
            });

            selectCiclo.disabled = false;
        });
});