class Validator {
    // Validaciones
    static validarUsername(username) {
        if (username.length < 3) {
            return "El nombre de usuario debe tener al menos 3 caracteres";
        }
        if (!/^[a-zA-Z0-9_]+$/.test(username)) {
            return "El nombre de usuario solo puede contener letras, números y guiones bajos";
        }
        return null;
    }

    static validarPassword(password) {
        if (password.length < 4) {
            return "La contraseña debe tener al menos 4 caracteres";
        }
        if (!/(?=.*[a-zA-Z])(?=.*[0-9])/.test(password)) {
            return "La contraseña debe contener al menos una letra y un número";
        }
        return null;
    }

    static validarNombre(nombre) {
        if (nombre.length < 2) {
            return "El nombre debe tener al menos 2 caracteres";
        }
        if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-']+$/.test(nombre)) {
            return "El nombre solo puede contener letras, espacios y algunos caracteres especiales";
        }
        return null;
    }

    static validarDNI(dni) {
        if (!/^\d{8}[A-Za-z]$/.test(dni)) {
            return "El DNI debe tener 8 números seguidos de una letra";
        }
        return null;
    }

    static validarEmail(email) {
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            return "El email no tiene un formato válido";
        }
        return null;
    }

    // Valida que fecha fin no sea posterior a hoy
    static validarFechaInicio(fechaInicio) {
        if (!fechaInicio) return null; // Opcional

        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        const dateFin = new Date(fechaInicio);

        if (dateFin > hoy) {
            return "No puedes empezar un curso posterior a hoy";
        }
        return null;
    }

    // Valida que fecha fin no sea posterior a hoy
    static validarFechaFin(fechaFin) {
        if (!fechaFin) return null; // Opcional

        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        const dateFin = new Date(fechaFin);

        if (dateFin > hoy) {
            return "La fecha de finalización no puede ser posterior a hoy";
        }
        return null;
    }

    // Valida que fecha inicio no sea posterior a fecha fin
    static validarFechas(fechaInicio, fechaFin) {
        if (!fechaInicio || !fechaFin) return null; // Si falta alguna, no validar

        const dateInicio = new Date(fechaInicio);
        const dateFin = new Date(fechaFin);

        if (dateInicio > dateFin) {
            return "La fecha de inicio no puede ser posterior a la fecha de finalización";
        }
        return null;
    }

    static mostrarError(inputId, mensaje) {
        const input = document.getElementById(inputId);
        let errorDiv = input.parentElement.querySelector('.error-message');

        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.color = 'red';
            errorDiv.style.fontSize = '12px';
            errorDiv.style.marginTop = '5px';
            input.parentElement.appendChild(errorDiv);
        }

        errorDiv.textContent = mensaje;
        input.style.borderColor = 'red';
    }

    static limpiarError(inputId) {
        const input = document.getElementById(inputId);
        const errorDiv = input.parentElement.querySelector('.error-message');

        if (errorDiv) {
            errorDiv.remove();
        }

        input.style.borderColor = '';
    }

    static validarFormulario() {
        let esValido = true;

        // Limpiar errores anteriores
        ['nuevoUsername', 'nuevoPassword', 'repetirPassword', 'nuevoDni',
            'nuevoEmail', 'nuevoNombre', 'nuevoApe1'].forEach(id => Validator.limpiarError(id));

        // Validar username
        const username = document.getElementById("nuevoUsername").value.trim();
        const errorUsername = Validator.validarUsername(username);
        if (errorUsername) {
            Validator.mostrarError("nuevoUsername", errorUsername);
            esValido = false;
        }

        // Validar password
        const password = document.getElementById("nuevoPassword").value;
        const errorPassword = Validator.validarPassword(password);
        if (errorPassword) {
            Validator.mostrarError("nuevoPassword", errorPassword);
            esValido = false;
        }

        // Validar que las contraseñas coincidan
        const passwordConfirm = document.getElementById("repetirPassword").value;
        if (password !== passwordConfirm) {
            Validator.mostrarError("repetirPassword", "Las contraseñas no coinciden");
            esValido = false;
        }

        // Validar DNI
        const dni = document.getElementById("nuevoDni").value.trim().toUpperCase();
        const errorDni = Validator.validarDNI(dni);
        if (errorDni) {
            Validator.mostrarError("nuevoDni", errorDni);
            esValido = false;
        }

        // Validar Email
        const email = document.getElementById("nuevoEmail").value.trim();
        const errorEmail = Validator.validarEmail(email);
        if (errorEmail) {
            Validator.mostrarError("nuevoEmail", errorEmail);
            esValido = false;
        }

        // Validar Nombre
        const nombre = document.getElementById("nuevoNombre").value.trim();
        const errorNombre = Validator.validarNombre(nombre);
        if (errorNombre) {
            Validator.mostrarError("nuevoNombre", errorNombre);
            esValido = false;
        }

        // Validar Apellido 1
        const ape1 = document.getElementById("nuevoApe1").value.trim();
        const errorApe1 = Validator.validarNombre(ape1);
        if (errorApe1) {
            Validator.mostrarError("nuevoApe1", errorApe1);
            esValido = false;
        }

        // Validar Ciclo (obligatorio)
        const ciclo = document.getElementById("selectCiclo").value;
        if (!ciclo) {
            Validator.mostrarError("selectCiclo", "Debes seleccionar un ciclo formativo");
            esValido = false;
        }

        // Obtener valores
        const fechaInicio = document.getElementById("fechaInicio").value;
        const fechaFin = document.getElementById("fechaFin").value;

        // Validar fecha Inicio
        const errorFechaInicio = Validator.validarFechaInicio(fechaInicio);
        if (errorFechaInicio) {
            Validator.mostrarError("fechaInicio", errorFechaInicio);
            esValido = false;
        }

        // Validar fecha fin
        const errorFechaFin = Validator.validarFechaFin(fechaFin);
        if (errorFechaFin) {
            Validator.mostrarError("fechaFin", errorFechaFin);
            esValido = false;
        }

        // Validar relación entre fechas
        const errorFechas = Validator.validarFechas(fechaInicio, fechaFin);
        if (errorFechas) {
            Validator.mostrarError("fechaFin", errorFechas);
            esValido = false;
        }

        return esValido;
    }
}



