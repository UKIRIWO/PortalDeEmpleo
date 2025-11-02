<?php
include_once __DIR__ . "/../Loaders/miAutoLoader.php";
echo "<h2>Crear alumno (siempre con usuario)</h2>";

// Crear objeto User
$nuevoUser = new User(
    null,                                           // id (null porque es nuevo)
    "juan.perez",                                   // nombre_usuario
    password_hash("Pass123!", PASSWORD_DEFAULT),    // password (hasheado)
    3                                               // id_rol_fk (asumiendo que 3 es rol alumno)
);

// Crear objeto Alumno
$nuevoAlumno = new Alumno(
    null,                       // id
    null,                       // id_user_fk (se asignará automáticamente)
    "12345678A",                // dni
    "Juan",                     // nombre
    "Pérez",                    // ape1
    "García",                   // ape2
    file_get_contents("./prueba/curriculum.pdf"),  // curriculum (como BLOB)
    "2000-05-15",               // fecha_nacimiento
    "Calle Mayor 123, Madrid",  // direccion
    "foto_juan.jpg"             // foto
);

// SIEMPRE se guardan juntos - no hay otra opción
$resultado = RepoAlumno::save($nuevoUser, $nuevoAlumno);

if ($resultado) {
    echo "<p style='color: green;'>✓ Alumno y usuario creados correctamente</p>";
    echo "<p>ID Usuario: " . $nuevoUser->getId() . "</p>";
    echo "<p>ID Alumno: " . $nuevoAlumno->getId() . "</p>";
} else {
    echo "<p style='color: red;'>✗ Error: Ninguno fue insertado (transacción revertida)</p>";
}

/* 
// --------------------------------------------
// 2. CREAR EMPRESA (SIEMPRE CON USUARIO)
// --------------------------------------------
echo "<h2>Crear empresa (siempre con usuario)</h2>";

// Crear objeto User
$userEmpresa = new User(
    null,
    "techsolutions_sa",
    password_hash("EmpresaPass456!", PASSWORD_DEFAULT),
    2  // id_rol_fk (asumiendo que 2 es rol empresa)
);

// Crear objeto Empresa
$nuevaEmpresa = new Empresa(
    null,                               // id
    null,                               // id_user_fk (se asignará automáticamente)
    "Polígono Industrial, Nave 5",      // direccion
    "María López Fernández",            // persona_de_contacto
    "maria.lopez@techsolutions.com",    // correo_de_contacto
    "+34 912345678",                    // telefono_de_contacto
    "logo_techsolutions.png"            // logo
);

// SIEMPRE se guardan juntos
$resultado = RepoEmpresa::save($userEmpresa, $nuevaEmpresa);

if ($resultado) {
    echo "<p style='color: green;'>✓ Empresa y usuario creados correctamente</p>";
    echo "<p>ID Usuario: " . $userEmpresa->getId() . "</p>";
    echo "<p>ID Empresa: " . $nuevaEmpresa->getId() . "</p>";
} else {
    echo "<p style='color: red;'>✗ Error: Ninguno fue insertado (transacción revertida)</p>";
}

// --------------------------------------------
// 3. ACTUALIZAR SOLO DATOS DEL ALUMNO
// --------------------------------------------
echo "<h2>Actualizar datos del alumno (sin tocar el usuario)</h2>";

$alumnoActualizar = RepoAlumno::findById(1);

if ($alumnoActualizar != null) {
    // Modificar solo datos del alumno
    $alumnoActualizar->setDireccion("Nueva dirección 789");
    $alumnoActualizar->setFoto("nueva_foto.jpg");
    
    // Solo actualiza los datos del alumno, no del usuario
    RepoAlumno::update($alumnoActualizar);
    echo "<p style='color: green;'>✓ Alumno actualizado correctamente</p>";
} else {
    echo "<p>No se encontró el alumno</p>";
}

// --------------------------------------------
// 4. ACTUALIZAR SOLO DATOS DE LA EMPRESA
// --------------------------------------------
echo "<h2>Actualizar datos de la empresa (sin tocar el usuario)</h2>";

$empresaActualizar = RepoEmpresa::findById(1);

if ($empresaActualizar != null) {
    // Modificar solo datos de la empresa
    $empresaActualizar->setDireccion("Avenida Principal 456, Barcelona");
    $empresaActualizar->setTelefonoDeContacto("+34 934567890");
    
    // Solo actualiza los datos de la empresa, no del usuario
    RepoEmpresa::update($empresaActualizar);
    echo "<p style='color: green;'>✓ Empresa actualizada correctamente</p>";
} else {
    echo "<p>No se encontró la empresa</p>";
}
    */